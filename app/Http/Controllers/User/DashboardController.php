<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kantor;
use App\Models\Presensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kantor = Kantor::firstOrFail();

        // Presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->first();

        // Jam masuk / keluar
        $jamMasuk  = $presensiHariIni?->jam_masuk ?? '--:--';
        $jamKeluar = $presensiHariIni?->jam_keluar ?? '--:--';

        // Status hari ini
        $statusPresensi = $presensiHariIni?->status ?? 'belum absen';

        // ================= REKAP BARU =================
        // Hadir  : hadir + telat
        // Tidak  : sakit + izin + tidak_hadir
        $rekap = Presensi::where('user_id', $user->id)
            ->selectRaw("
                SUM(CASE 
                    WHEN status IN ('hadir','telat') THEN 1 
                    ELSE 0 
                END) as hadir,

                SUM(CASE 
                    WHEN status IN ('sakit','izin','tidak_hadir') THEN 1 
                    ELSE 0 
                END) as tidak_hadir
            ")
            ->first();

        return view('user.dashboard', compact(
            'user',
            'kantor',
            'presensiHariIni',
            'jamMasuk',
            'jamKeluar',
            'statusPresensi',
            'rekap'
        ));
    }
}
