<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kantor extends Model
{
    protected $table = 'kantors';

    protected $fillable = [
        'nama_apk',
        'nama_pt',
        'logo',
        'alamat',
        'link_maps',
        'wa_link',
        'ig_link',
        'hari_kerja',
        'jam_masuk',
        'jam_keluar',
        'kantor_lat',
        'kantor_lng',
        'radius_absen',
    ];

    protected $casts = [
        'hari_kerja'   => 'array',
        'kantor_lat'   => 'float',
        'kantor_lng'   => 'float',
        'radius_absen' => 'integer',
        // jam_masuk & jam_keluar BIARKAN STRING
    ];

    /* ===================== RELASI ===================== */
    public function hariLibur()
    {
        return $this->hasMany(HariLibur::class, 'kantor_id');
    }

    /* ===================== LOGIKA BISNIS ===================== */

    public function isHariKerja($tanggal = null): bool
    {
        $mapHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        $tanggal = $tanggal ? Carbon::parse($tanggal) : now();
        $hariIni = $mapHari[$tanggal->dayOfWeekIso] ?? null;

        if (!$hariIni || empty($this->hari_kerja)) return false;

        return in_array(strtolower($hariIni), $this->hari_kerja);
    }

    public function isHariLibur($tanggal = null): bool
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return DB::table('hari_libur')
            ->whereDate('tanggal', $tanggal)
            ->exists();
    }

    public function hariLiburDetail($tanggal = null)
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return DB::table('hari_libur')
            ->whereDate('tanggal', $tanggal)
            ->first();
    }

    public function isHariKerjaFinal($tanggal = null): bool
    {
        $tanggal = $tanggal ?? now()->toDateString();
        return $this->isHariKerja($tanggal) && !$this->isHariLibur($tanggal);
    }

    /* ===================== JAM KERJA ===================== */

    public function jamMasukTime(): string
    {
        return substr($this->jam_masuk, 0, 5); // HH:MM
    }

    public function jamKeluarTime(): string
    {
        return substr($this->jam_keluar, 0, 5); // HH:MM
    }

    public function isDalamJamKerja($waktu = null): bool
    {
        $waktu = $waktu ? Carbon::parse($waktu)->format('H:i') : now()->format('H:i');

        return $waktu >= $this->jamMasukTime()
            && $waktu <= $this->jamKeluarTime();
    }
    
}
