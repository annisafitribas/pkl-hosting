<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Presensi;
use App\Models\Pengajuan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pembimbing = Auth::user();
        $today = Carbon::today();

        // ALPHA PESERTA BIMBINGAN ALPHA
        $peserta = User::whereHas('profile.pembimbing.user', function ($q) use ($pembimbing) {
                $q->where('users.id', $pembimbing->id);
            })
            ->with('profile.bagian')
            ->get();

        $pesertaIds = $peserta->pluck('id');

        // ALPHA PRESENSI HARI INI ALPHA
        $presensiHariIni = Presensi::whereIn('user_id', $pesertaIds)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('user_id');

        // ALPHA STATISTIK HARI INI ALPHA
        // NOTE:
        // hadir ≠ telat (dipisah biar konsisten dashboard lain)

        $hadir = $presensiHariIni
            ->where('status', 'hadir')
            ->count();

        $telat = $presensiHariIni
            ->where('status', 'telat')
            ->count();

        $izin = $presensiHariIni
            ->where('status', 'izin')
            ->count();

        $sakit = $presensiHariIni
            ->where('status', 'sakit')
            ->count();

        // Peserta yang BELUM presensi hari ini
        $belumPresensi = $peserta->count() - $presensiHariIni->count();

        // ALPHA PENGAJUAN IZIN PENDING ALPHA
        $izinPending = Pengajuan::whereIn('user_id', $pesertaIds)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pembimbing.dashboard', compact(
            'peserta',
            'presensiHariIni',
            'hadir',
            'telat',
            'izin',
            'sakit',
            'belumPresensi',
            'izinPending'
        ));
    }
}
