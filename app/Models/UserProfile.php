<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bagian_id',
        'pembimbing_id',
        'nomor_induk',
        'tingkatan',
        'pendidikan',
        'kelas',
        'jurusan',
        'tgl_masuk',
        'tgl_keluar',
        'status_magang',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_keluar' => 'date',
    ];

    /* ================= RELATIONS ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class, 'bagian_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(PembimbingProfile::class, 'pembimbing_id');
    }

    /* ================= AUTOMATIC STATUS ================= */

    protected static function booted()
    {
        static::saving(function ($profile) {
            $today = Carbon::today();

            if ($profile->tgl_masuk && $profile->tgl_keluar) {
                $profile->status_magang = $today->between($profile->tgl_masuk, $profile->tgl_keluar)
                    ? 'Aktif'
                    : 'Tidak Aktif';
            } else {
                $profile->status_magang = 'Tidak Aktif';
            }
        });
    }

    /* ================= CUSTOM ATTRIBUTES ================= */

    /**
     * Sisa Hari Magang
     */
    public function getSisaHariAttribute()
    {
        $today = Carbon::today();

        if ($this->tgl_keluar && $today->lessThanOrEqualTo($this->tgl_keluar)) {
            return $today->diffInDays($this->tgl_keluar);
        }

        return 0;
    }

}
