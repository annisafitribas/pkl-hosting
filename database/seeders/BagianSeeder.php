<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bagian;

class BagianSeeder extends Seeder
{
    public function run(): void
    {
        $bagians = [
            [
                'nama_bagian'   => 'Keuangan',
                'kepala_bagian' => 'Emy Astuti',
                'nip'           => '19801111 202012 1 001',
            ],
            [
                'nama_bagian'   => 'Sales',
                'kepala_bagian' => 'Muhammad Sigit Kurniawan',
                'nip'           => '19802222 202012 1 002',
            ],
            [
                'nama_bagian'   => 'PP',
                'kepala_bagian' => 'Asterina Azizah',
                'nip'           => '19803333 202012 1 003',
            ],
        ];

        foreach ($bagians as $b) {
            Bagian::firstOrCreate(
                ['nama_bagian' => $b['nama_bagian']], // kolom UNIQUE
                $b
            );
        }
    }
}
