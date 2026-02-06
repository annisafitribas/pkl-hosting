<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bagian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kepala',
    ];

    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class, 'bagian_id');
    }

    public function pembimbingProfiles()
    {
        return $this->hasMany(PembimbingProfile::class, 'bagian_id');
    }

    public function isUsed(): bool
    {
        return $this->userProfiles()->exists()
            || $this->pembimbingProfiles()->exists();
    }

}
