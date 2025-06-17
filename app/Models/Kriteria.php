<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'bobot',
        'tipe',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function penilaianSiswa()
    {
        return $this->hasMany(PenilaianSiswa::class);
    }
}
