<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Tampilkan daftar peserta + rekap presensi
     */
    public function index()
    {
        $users = User::where('role', 'user')->get();

        $users->map(function ($user) {
            $rekap = Presensi::where('user_id', $user->id)
                ->selectRaw("
                    SUM(status = 'hadir')        as hadir,
                    SUM(status = 'telat')        as telat,
                    SUM(status = 'sakit')        as sakit,
                    SUM(status = 'izin')         as izin,
                    SUM(status = 'tidak_hadir')  as alpha
                ")
                ->first();

            $user->total_hadir = $rekap->hadir ?? 0;
            $user->total_telat = $rekap->telat ?? 0;
            $user->total_sakit = $rekap->sakit ?? 0;
            $user->total_izin  = $rekap->izin ?? 0;
            $user->total_alpha = $rekap->alpha ?? 0;
        });

        return view('admin.presensi_index', compact('users'));
    }

    /**
     * Detail presensi peserta
     */
    public function show(User $user)
    {
        $presensis = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.presensi_show', compact('user', 'presensis'));
    }

    /**
     * Update presensi (LOGIKA UTAMA ADA DI SINI)
     */
    public function update(Request $request, Presensi $presensi)
    {
        // ================= VALIDASI DASAR =================
        $validated = $request->validate([
            'status'     => 'required|in:hadir,telat,sakit,izin,tidak_hadir',
            'keterangan' => 'nullable|string|max:255',
            'jam_masuk'  => 'nullable',
            'jam_keluar' => 'nullable',
        ]);

        // ================= LOGIKA BISNIS =================
        // Status yang TIDAK BOLEH punya jam
        if (in_array($validated['status'], ['sakit', 'izin', 'tidak_hadir'])) {
            $validated['jam_masuk']  = null;
            $validated['jam_keluar'] = null;
        }

        // Status hadir / telat → jam opsional tapi masuk akal
        if (in_array($validated['status'], ['hadir', 'telat'])) {
            // kalau jam kosong, biarin null (admin bisa isi belakangan)
            // tapi kalau mau dipaksa wajib, tinggal validasi di sini
        }

        // ================= UPDATE =================
        $presensi->update([
            'status'     => $validated['status'],
            'keterangan' => $validated['keterangan'],
            'jam_masuk'  => $validated['jam_masuk'],
            'jam_keluar' => $validated['jam_keluar'],
        ]);

        return redirect()
            ->route('admin.presensi.show', $presensi->user_id)
            ->with('success', 'Presensi berhasil diperbarui');
    }
}
