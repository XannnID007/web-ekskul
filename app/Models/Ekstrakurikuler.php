<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler';

    protected $fillable = [
        'nama_ekskul',
        'deskripsi',
        'kategori',
        'kapasitas_maksimal',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'pembina_id',
        'is_active'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    public function pembina()
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function anggotaAktif()
    {
        return $this->pendaftaran()
            ->where('status', 'approved')
            ->with('siswa.user');
    }

    public function jumlahAnggota()
    {
        return $this->pendaftaran()
            ->where('status', 'approved')
            ->count();
    }

    public function sisaKuota()
    {
        return $this->kapasitas_maksimal - $this->jumlahAnggota();
    }

    public function isFull()
    {
        return $this->sisaKuota() <= 0;
    }
}
