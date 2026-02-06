<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kantors')->insert([
            'nama_apk'   => 'APM',
            'logo'          => 'kantor-logo\JiZRftxbHZtwaZZbHA9uPxwWDY71z5L2GKJRR3TV.png',
            'alamat'        => 'Jl. A. Yani Km. 33, Banjarbaru, Kalimantan Selatan',
            'link_maps'     => 'https://maps.google.com',

            'wa_link'       => 'https://wa.me/6281234567890',
            'ig_link'       => 'https://instagram.com/pln_id',

            'hari_kerja'    => json_encode([
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat'
            ]),

            'jam_masuk'     => '07:45:00',
            'jam_keluar'    => '23:00:00',

            'kantor_lat'    => -3.31686840,
            'kantor_lng'    => 114.59018350,

            'radius_absen'  => 100,

            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
