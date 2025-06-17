<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSiswa extends Model
{
    use HasFactory;

    protected $table = 'penilaian_siswa';

    protected $fillable = [
        'siswa_id',
        'kriteria_id',
        'nilai'
    ];

    protected $casts = [
        'nilai' => 'decimal:2'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
