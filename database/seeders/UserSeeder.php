<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PembimbingProfile;
use App\Models\UserProfile;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ================= ADMIN =================
        $admin = User::create([
            'name'      => 'Admin Utama',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'gender'    => 'L',
            'tgl_lahir' => '1985-01-01',
            'alamat'    => 'Jl. Kantor Pusat No.1',
            'no_hp'     => '08111111111',
            'foto'      => null,
        ]);

        // // ================= PEMBIMBING =================
        // $pembimbing = User::create([
        //     'name'      => 'Pembimbing 1',
        //     'email'     => 'pembimbing1@example.com',
        //     'password'  => Hash::make('password'),
        //     'role'      => 'pembimbing',
        //     'gender'    => 'P',
        //     'tgl_lahir' => '1980-05-15',
        //     'alamat'    => 'Jl. Pembimbing No.1',
        //     'no_hp'     => '08123456789',
        //     'foto'      => null,
        // ]);

        // $pembimbingProfile = PembimbingProfile::create([
        //     'user_id'   => $pembimbing->id,
        //     'nip'       => '1987654321',
        //     'jabatan'   => 'Instruktur',
        //     'bagian_id' => 1, // pastikan bagian sudah ada di table 'bagians'
        // ]);

        // // ================= USER / PESERTA MAGANG =================
        // $user = User::create([
        //     'name'      => 'Mahasiswa Contoh',
        //     'email'     => 'user@example.com',
        //     'password'  => Hash::make('password'),
        //     'role'      => 'user',
        //     'gender'    => 'L',
        //     'tgl_lahir' => '2003-08-20',
        //     'alamat'    => 'Jl. Mahasiswa No.1',
        //     'no_hp'     => '08222222222',
        //     'foto'      => null,
        // ]);

        // UserProfile::create([
        //     'user_id'        => $user->id,
        //     'nomor_induk'    => '2025001',
        //     'tingkatan'      => 'S1',
        //     'pendidikan'     => 'Universitas Contoh',
        //     'kelas'          => 'TI-5A',
        //     'jurusan'        => 'Teknologi Informasi',
        //     'bagian_id'      => 1, // sama dengan pembimbing
        //     'pembimbing_id'  => $pembimbingProfile->id, // <--- benar, FK ke pembimbing_profiles.id
        //     'tgl_masuk'      => now()->subMonth(),
        //     'tgl_keluar'     => now()->addMonths(2),
        // ]);
    }
}
