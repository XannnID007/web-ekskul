<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'status',
        'alasan_daftar',
        'catatan_pembina',
        'skor_rekomendasi',
        'tanggal_daftar',
        'tanggal_persetujuan'
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'skor_rekomendasi' => 'decimal:2'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function approve($catatan = null)
    {
        $this->update([
            'status' => 'approved',
            'catatan_pembina' => $catatan,
            'tanggal_persetujuan' => now()
        ]);
    }

    public function reject($catatan = null)
    {
        $this->update([
            'status' => 'rejected',
            'catatan_pembina' => $catatan,
            'tanggal_persetujuan' => now()
        ]);
    }

    public function persentaseKehadiran()
    {
        $totalPertemuan = $this->kehadiran()->count();
        $hadir = $this->kehadiran()->where('status', 'hadir')->count();

        return $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;
    }
}
