<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Kantor;

class TandaiAlpha extends Command
{
    protected $signature = 'presensi:tandai-alpha';
    protected $description = 'Menandai presensi alpha otomatis untuk user PKL saja';

    public function handle()
    {
        $tanggal = now()->subDay()->toDateString();
        $kantor  = Kantor::first();

        // 🔑 HANYA USER DENGAN ROLE "user"
        $users = User::where('role', 'user')->get();

        $totalAlpha = 0;

        foreach ($users as $user) {

            $presensi = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            // 1️⃣ BELUM ADA PRESENSI → BUAT ALPHA
            if (!$presensi) {
                Presensi::create([
                    'user_id'   => $user->id,
                    'kantor_id' => $kantor->id,
                    'tanggal'   => $tanggal,
                    'status'    => 'tidak_hadir', // ALPHA
                    'locked'    => true,
                ]);
                $totalAlpha++;
                continue;
            }

            // 2️⃣ ADA PRESENSI TAPI TIDAK MASUK & BUKAN IZIN/SAKIT
            if (
                is_null($presensi->jam_masuk) &&
                !in_array($presensi->status, ['izin', 'sakit'])
            ) {
                $presensi->update([
                    'status' => 'tidak_hadir',
                    'locked' => true,
                ]);
                $totalAlpha++;
            }
        }

        $this->info("Alpha diproses untuk user PKL: {$totalAlpha}");
    }
}