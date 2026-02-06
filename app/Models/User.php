<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password', 
        'role', 
        'foto', 
        'gender', 
        'tgl_lahir', 
        'alamat', 
        'no_hp'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['tgl_lahir' => 'date'];

    // ================= RELASI =================
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class, 'bagian_id');
    }

    // ================= HELPERS =================
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPembimbing(): bool
    {
        return $this->role === 'pembimbing';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function pembimbingProfile()
    {
        return $this->hasOne(PembimbingProfile::class, 'user_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function isDeletable(): bool
    {
        // ❌ tidak boleh hapus diri sendiri
        if ($this->id === Auth::id()) {
            return false;
        }

        // ❌ pembimbing masih punya peserta
        if (
            $this->role === 'pembimbing'
            && ($this->pembimbingProfile?->usersDibimbing?->count() ?? 0) > 0
        ) {
            return false;
        }

        // ❌ peserta masih aktif magang
        if (
            $this->role === 'user'
            && $this->profile?->status_magang === 'Aktif'
        ) {
            return false;
        }

        return true;
    }

}
