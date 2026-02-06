<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncHariLibur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:harilibur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

public function handle()
{
    $tahun = now()->year; // AMBIL TAHUN SEKARANG

    $response = Http::get(
        'https://api-harilibur.vercel.app/api',
        ['year' => $tahun]
    );

    if ($response->failed() || empty($response->json())) {
        $this->error("Data hari libur tahun {$tahun} belum tersedia");
        return;
    }

    foreach ($response->json() as $libur) {
        DB::table('hari_libur')->updateOrInsert(
            ['tanggal' => $libur['holiday_date']],
            [
                'nama' => $libur['holiday_name'],
                'nasional' => $libur['is_national_holiday'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    $this->info("Hari libur tahun {$tahun} berhasil disinkronkan");
}

}
