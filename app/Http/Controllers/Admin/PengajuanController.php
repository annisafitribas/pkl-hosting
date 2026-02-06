<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Presensi;
use App\Models\Kantor;
use Carbon\Carbon;

class PengajuanController extends Controller
{
    /**
     * List semua pengajuan untuk admin
     */
    public function index()
    {
        $pengajuans = Pengajuan::with('user')
            ->latest()
            ->get();

        $kantor = Kantor::first(); // ambil 1 kantor

        return view('admin.pengajuan_index', compact('pengajuans', 'kantor'));
    }

    /**
     * Update status pengajuan (approve / reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Update status
        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Jika disetujui → buat presensi izin otomatis
        if ($request->status === 'approved') {
            $kantor = Kantor::firstOrFail();

            $start = Carbon::parse($pengajuan->tanggal_mulai);
            $end   = Carbon::parse($pengajuan->tanggal_selesai);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                // Lewati hari libur / bukan hari kerja
                if (!$kantor->isHariKerjaFinal($date)) continue;

                Presensi::updateOrCreate(
                    [
                        'user_id' => $pengajuan->user_id,
                        'tanggal' => $date->toDateString(),
                    ],
                    [
                        'kantor_id' => $kantor->id,
                        'status'    => 'izin',
                        'keterangan'=> 'Izin disetujui admin',
                        'locked'    => true,
                    ]
                );
            }
        }
    return redirect()
        ->route('admin.pengajuan.index')
        ->with('success', 'Status pengajuan berhasil diperbarui');

    }
}
