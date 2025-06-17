<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function ekstrakurikulerPembina()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'author_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPembina()
    {
        return $this->role === 'pembina';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
