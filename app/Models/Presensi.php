<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'user_id',
        'kantor_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_keluar',
        'lat_masuk',
        'lng_masuk',
        'lat_keluar',
        'lng_keluar',
        'locked',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'locked'  => 'boolean',
    ];

    /* ================= RELASI ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    /* ================= HELPER ================= */

    /** Presensi sudah final (keluar / tidak hadir) */
    public function isLocked(): bool
    {
        return $this->locked === true;
    }

    /** Kunci presensi */
    public function lock(): void
    {
        $this->update(['locked' => true]);
    }

    /** Bisa absen masuk */
    public function bisaMasuk(): bool
    {
        return is_null($this->jam_masuk)
            && $this->status !== 'tidak_hadir'
            && !$this->locked;
    }

    /** Bisa absen keluar */
    public function bisaKeluar(): bool
    {
        return !is_null($this->jam_masuk)
            && is_null($this->jam_keluar)
            && $this->status !== 'tidak_hadir'
            && !$this->locked;
    }

    public static function statusOptions()
    {
        return [
            'hadir' => 'Hadir',
            'telat' => 'Telat',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'tidak_hadir' => 'Alpha',
        ];
    }

}