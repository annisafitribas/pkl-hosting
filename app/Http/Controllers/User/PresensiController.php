<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kantor;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /* ================= FORM PRESENSI ================= */
    public function create()
    {
        $user   = Auth::user();
        $kantor = Kantor::firstOrFail();

        // Cek hari kerja
        if (!$kantor->isHariKerjaFinal()) {
            return redirect()->route('user.dashboard')
                ->with('error', '⛔ Presensi tidak tersedia karena hari libur atau bukan hari kerja');
        }

        // Presensi hari ini (boleh null)
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->first();

        // Status tampilan (BUKAN disimpan ke DB)
        $belumPresensi = is_null($presensiHariIni);

        $bisaPresensiMasuk  = !$presensiHariIni || $presensiHariIni->bisaMasuk();
        $bisaPresensiKeluar = $presensiHariIni && $presensiHariIni->bisaKeluar();

        return view('user.presensi', compact(
            'kantor',
            'presensiHariIni',
            'bisaPresensiMasuk',
            'bisaPresensiKeluar',
            'belumPresensi'
        ));
    }

    /* ================= SIMPAN PRESENSI ================= */
    public function store(Request $request)
    {
        $user   = Auth::user();
        $kantor = Kantor::firstOrFail();

        // Validasi hari kerja
        if (!$kantor->isHariKerjaFinal()) {
            return redirect()->route('user.dashboard')
                ->with('error', '⛔ Presensi tidak dapat dilakukan karena hari libur atau bukan hari kerja');
        }

        // Validasi awal
        $request->validate([
            'type'       => 'required|in:masuk,keluar,tidak_hadir',
            'status'     => 'nullable|in:izin,sakit',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Ambil / buat presensi hari ini
        $presensi = Presensi::firstOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => today()->toDateString(),
            ],
            [
                'kantor_id' => $kantor->id,
                'status'    => 'hadir',
                'locked'    => false,
            ]
        );

        if ($presensi->isLocked()) {
            return back()->with('error', '⚠️ Presensi hari ini sudah selesai');
        }

        /* ================= IZIN / SAKIT ================= */
        if ($request->type === 'tidak_hadir') {

            // BATAS IZIN TANPA PENGAJUAN (MAKS 2 HARI)
            if ($request->status === 'izin') {
                $izinBeruntun = Presensi::where('user_id', $user->id)
                    ->where('status', 'izin')
                    ->whereDate('tanggal', '>=', now()->subDays(2))
                    ->count();

                if ($izinBeruntun >= 2) {
                    return redirect()->route('user.pengajuan.create')
                        ->with('error', '⚠️ Izin sudah 2 hari berturut-turut. Silakan ajukan izin resmi.');
                }
            }

            $presensi->update([
                'status'     => $request->status, // izin / sakit
                'keterangan' => $request->keterangan,
                'locked'     => true,
            ]);

            return redirect()->route('user.dashboard')
                ->with('success', '✅ Presensi dicatat sebagai ' . strtoupper($request->status));
        }

        /* ================= VALIDASI LOKASI ================= */
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $jarak = $this->hitungJarak(
            $kantor->kantor_lat,
            $kantor->kantor_lng,
            $request->latitude,
            $request->longitude
        );

        if ($jarak > $kantor->radius_absen) {
            return back()->with('error', '❌ Kamu berada di luar radius kantor');
        }

        /* ================= ABSEN MASUK ================= */
        if ($request->type === 'masuk') {

            if (!$presensi->bisaMasuk()) {
                return back()->with('error', '⚠️ Tidak bisa absen masuk');
            }

            $jamMasukKantor = Carbon::parse($kantor->jam_masuk);
            $jamSekarang    = now();
            $toleransiMenit = 5;

            $status = $jamSekarang->greaterThan(
                $jamMasukKantor->copy()->addMinutes($toleransiMenit)
            ) ? 'telat' : 'hadir';

            $presensi->update([
                'jam_masuk' => $jamSekarang->format('H:i:s'),
                'lat_masuk' => $request->latitude,
                'lng_masuk' => $request->longitude,
                'status'    => $status,
            ]);

            return redirect()->route('user.dashboard')
                ->with('success', "✅ Absen masuk berhasil (Status: $status)");
        }

        /* ================= ABSEN KELUAR ================= */
        if ($request->type === 'keluar') {

            if (!$presensi->bisaKeluar()) {
                return back()->with('error', '⚠️ Tidak bisa absen keluar');
            }

            $presensi->update([
                'jam_keluar' => now()->format('H:i:s'),
                'lat_keluar' => $request->latitude,
                'lng_keluar' => $request->longitude,
                'keterangan' => $request->keterangan,
                'locked'     => true,
            ]);

            return redirect()->route('user.dashboard')
                ->with('success', '✅ Absen keluar berhasil');
        }
    }

    /* ================= HITUNG JARAK ================= */
    private function hitungJarak($lat1, $lon1, $lat2, $lon2): float
    {
        $R = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $R * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}