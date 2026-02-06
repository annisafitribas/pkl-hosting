<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\Kantor;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $presensi = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $rekap = Presensi::where('user_id', $user->id)
            ->selectRaw("
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'telat' THEN 1 ELSE 0 END) as telat,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'tidak_hadir' THEN 1 ELSE 0 END) as tidak_hadir
            ")
            ->first();

        return view('user.laporan', compact(
            'presensi',
            'rekap',
            'user'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user   = Auth::user();
        $kantor = Kantor::first();

        $query = Presensi::where('user_id', $user->id);

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        $presensi = $query->orderBy('tanggal', 'asc')->get();

        $pdf = Pdf::loadView('user.laporan_pdf', compact(
            'presensi',
            'user',
            'kantor'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Presensi.pdf');
    }
}