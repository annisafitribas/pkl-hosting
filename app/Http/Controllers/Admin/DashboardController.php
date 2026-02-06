<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Bagian;
use App\Models\Pengajuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // ================= RINGKASAN KEHADIRAN HARI INI =================
        $hadir = Presensi::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $terlambat = Presensi::whereDate('tanggal', $today)
            ->where('status', 'telat')
            ->count();

        $izin = Presensi::whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->count();

        $sakit = Presensi::whereDate('tanggal', $today)
            ->where('status', 'sakit')
            ->count();

        $belumAbsen = User::where('role', 'user')
            ->whereDoesntHave('presensi', function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })
            ->count();

        // ================= RINGKASAN DATA SISTEM =================
        $totalPeserta   = User::where('role', 'user')->count();
        $totalPengguna  = User::count();
        $totalBagian    = Bagian::count();

        $pengajuanPending = Pengajuan::where('status', 'pending')->count();

        // ================= TABEL KEHADIRAN HARI INI =================
        $kehadiranHariIni = Presensi::with([
                'user.profile.bagian'
            ])
            ->whereDate('tanggal', $today)
            ->orderBy('jam_masuk')
            ->get();

        return view('admin.dashboard', compact(
            'hadir',
            'terlambat',
            'izin',
            'sakit',
            'belumAbsen',
            'totalPeserta',
            'totalPengguna',
            'totalBagian',
            'pengajuanPending',
            'kehadiranHariIni'
        ));
    }
}