<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.pengajuan_index', compact('pengajuans'));
    }

    public function store(Request $request)
    {
        $hariIni = Carbon::today();

        $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
                function ($attr, $value, $fail) use ($hariIni) {
                    if (Carbon::parse($value)->lt($hariIni)) {
                        $fail('Tanggal mulai tidak boleh sebelum hari ini.');
                    }
                },
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'file_pdf'   => 'required|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $filePath = $request->file('file_pdf')
            ->store('pengajuan_pdf', 'public');

        Pengajuan::create([
            'user_id'         => Auth::id(),
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
            'file_pdf'        => $filePath,
            'status'          => 'pending',
        ]);

        return redirect()
            ->route('user.pengajuan.index')
            ->with('success', 'Pengajuan izin berhasil dikirim');
    }
}
