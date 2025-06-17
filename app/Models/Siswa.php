<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nisn',
        'kelas',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'minat',
        'nilai_akademik'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'minat' => 'array',
        'nilai_akademik' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function penilaianSiswa()
    {
        return $this->hasMany(PenilaianSiswa::class);
    }

    public function ekstrakurikulerAktif()
    {
        return $this->pendaftaran()
            ->where('status', 'approved')
            ->with('ekstrakurikuler');
    }

    public function hitungSkorRekomendasi($ekstrakurikulerId)
    {
        // Implementasi algoritma Weighted Scoring
        $kriteria = Kriteria::where('is_active', true)->get();
        $totalSkor = 0;
        $totalBobot = 0;

        foreach ($kriteria as $k) {
            $nilai = $this->penilaianSiswa()
                ->where('kriteria_id', $k->id)
                ->first();

            if ($nilai) {
                // Normalisasi nilai (0-100 ke 0-1)
                $nilaiNormal = $nilai->nilai / 100;

                // Jika tipe cost, inversi nilai
                if ($k->tipe === 'cost') {
                    $nilaiNormal = 1 - $nilaiNormal;
                }

                $totalSkor += $nilaiNormal * $k->bobot;
                $totalBobot += $k->bobot;
            }
        }

        return $totalBobot > 0 ? ($totalSkor / $totalBobot) * 100 : 0;
    }
}
