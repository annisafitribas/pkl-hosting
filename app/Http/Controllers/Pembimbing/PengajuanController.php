<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * ================= DAFTAR PENGAJUAN =================
     */
    public function index()
    {
        $pembimbing = Auth::user();

        // ambil semua peserta binaan
        $pesertaIds = User::whereHas('profile.pembimbing.user', function ($q) use ($pembimbing) {
            $q->where('users.id', $pembimbing->id);
        })->pluck('id');

        // ambil semua pengajuan peserta binaan
        $pengajuans = Pengajuan::with('user')
            ->whereIn('user_id', $pesertaIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembimbing.pengajuan_index', compact('pengajuans'));
    }

    /**
     * ================= DETAIL PENGAJUAN =================
     */
    public function show(Pengajuan $pengajuan)
    {
        $pembimbing = Auth::user();

        // keamanan: pastikan pengajuan milik peserta binaan
        $isBinaan = $pengajuan->user->profile
            && $pengajuan->user->profile->pembimbing
            && $pengajuan->user->profile->pembimbing->user_id === $pembimbing->id;

        abort_unless($isBinaan, 403);

        return view('pembimbing.pengajuan_show', compact('pengajuan'));
    }
}