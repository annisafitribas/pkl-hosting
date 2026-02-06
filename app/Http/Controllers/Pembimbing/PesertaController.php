<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function index()
    {
        $pembimbing = Auth::user();

        $peserta = User::with([
                'profile.bagian',
                'presensi'
            ])
            ->whereHas('profile.pembimbing.user', function ($q) use ($pembimbing) {
                $q->where('users.id', $pembimbing->id);
            })
            ->get();

        // rekap presensi
        $peserta->map(function ($user) {
            $hadir = $user->presensi->whereIn('status', ['hadir', 'telat'])->count();

            $tidakHadir = $user->presensi->whereIn('status', [
                'izin',
                'sakit',
                'tidak_hadir'
            ])->count();

            $user->total_hadir = $hadir;
            $user->total_tidak_hadir = $tidakHadir;
        });

        return view('pembimbing.peserta_index', compact('peserta'));
    }

    public function show(User $user)
    {
        $pembimbing = Auth::user();

        // keamanan: pastikan peserta binaan
        $isBinaan = $user->profile
            && $user->profile->pembimbing
            && $user->profile->pembimbing->user_id === $pembimbing->id;

        abort_unless($isBinaan, 403);

        $presensi = $user->presensi()
            ->orderBy('tanggal', 'desc')
            ->get();

        // ================= REKAP LENGKAP =================
        $hadir = $presensi->where('status', 'hadir')->count();
        $telat = $presensi->where('status', 'telat')->count();
        $izin  = $presensi->where('status', 'izin')->count();
        $sakit = $presensi->where('status', 'sakit')->count();
        $alpha = $presensi->where('status', 'tidak_hadir')->count();

        return view('pembimbing.peserta_show', compact(
            'user',
            'presensi',
            'hadir',
            'telat',
            'izin',
            'sakit',
            'alpha'
        ));
    }

}
