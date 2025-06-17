<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::with('user')->get();
        $ekstrakurikuler = Ekstrakurikuler::where('is_active', true)->get();

        $rekomendasi = [];

        if ($request->filled('siswa_id')) {
            $selectedSiswa = Siswa::findOrFail($request->siswa_id);
            $rekomendasi = $this->hitungRekomendasi($selectedSiswa);
        }

        return view('admin.rekomendasi.index', compact('siswa', 'ekstrakurikuler', 'rekomendasi'));
    }

    private function hitungRekomendasi(Siswa $siswa)
    {
        $ekstrakurikuler = Ekstrakurikuler::where('is_active', true)->get();
        $kriteria = Kriteria::where('is_active', true)->get();
        $penilaianSiswa = $siswa->penilaianSiswa->keyBy('kriteria_id');

        $hasil = [];

        foreach ($ekstrakurikuler as $ekskul) {
            $totalSkor = 0;
            $totalBobot = 0;
            $detailSkor = [];

            foreach ($kriteria as $k) {
                $penilaian = $penilaianSiswa->get($k->id);

                if ($penilaian) {
                    // Normalisasi nilai (0-100 ke 0-1)
                    $nilaiNormal = $penilaian->nilai / 100;

                    // Jika tipe cost, inversi nilai
                    if ($k->tipe === 'cost') {
                        $nilaiNormal = 1 - $nilaiNormal;
                    }

                    $skorKriteria = $nilaiNormal * $k->bobot;
                    $totalSkor += $skorKriteria;
                    $totalBobot += $k->bobot;

                    $detailSkor[] = [
                        'kriteria' => $k->nama_kriteria,
                        'nilai_asli' => $penilaian->nilai,
                        'nilai_normal' => $nilaiNormal,
                        'bobot' => $k->bobot,
                        'skor' => $skorKriteria
                    ];
                }
            }

            $skorAkhir = $totalBobot > 0 ? ($totalSkor / $totalBobot) * 100 : 0;

            $hasil[] = [
                'ekstrakurikuler' => $ekskul,
                'skor_akhir' => round($skorAkhir, 2),
                'detail_skor' => $detailSkor,
                'rekomendasi' => $this->getRekomendasiLabel($skorAkhir)
            ];
        }

        // Sort by score descending
        usort($hasil, function ($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        return $hasil;
    }

    private function getRekomendasiLabel($skor)
    {
        if ($skor >= 80) return 'Sangat Direkomendasikan';
        if ($skor >= 70) return 'Direkomendasikan';
        if ($skor >= 60) return 'Cukup Direkomendasikan';
        if ($skor >= 50) return 'Kurang Direkomendasikan';
        return 'Tidak Direkomendasikan';
    }
}
