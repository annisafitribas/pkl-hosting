<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembimbingProfile extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_profiles';

    protected $fillable = [
        'user_id',
        'bagian_id',
        'nip',
        'jabatan',
    ];

    /* ALPHARELATION ALPHA*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    /**
     * Peserta magang yang dibimbing
     */
    public function mentees()
    {
        return $this->hasMany(UserProfile::class, 'pembimbing_id');
    }

    /**
     * Alias kompatibilitas kode lama
     */
    public function usersDibimbing()
    {
        return $this->mentees();
    }
}
