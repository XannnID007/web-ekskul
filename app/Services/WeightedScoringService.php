<?php

namespace app\Services;

use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;

class WeightedScoringService
{
     /**
      * Hitung rekomendasi untuk siswa tertentu
      *
      * @param Siswa $siswa
      * @return array
      */
     public function hitungRekomendasi(Siswa $siswa)
     {
          $ekstrakurikuler = Ekstrakurikuler::where('is_active', true)->get();
          $kriteria = Kriteria::where('is_active', true)->get();
          $penilaianSiswa = $siswa->penilaianSiswa->keyBy('kriteria_id');

          $hasil = [];

          foreach ($ekstrakurikuler as $ekskul) {
               $skorData = $this->hitungSkorEkstrakurikuler($siswa, $ekskul, $kriteria, $penilaianSiswa);

               $hasil[] = [
                    'ekstrakurikuler' => $ekskul,
                    'skor_akhir' => $skorData['skor_akhir'],
                    'detail_skor' => $skorData['detail_skor'],
                    'rekomendasi' => $this->getRekomendasiLabel($skorData['skor_akhir']),
                    'confidence_level' => $this->getConfidenceLevel($skorData['skor_akhir'])
               ];
          }

          // Sort by score descending
          usort($hasil, function ($a, $b) {
               return $b['skor_akhir'] <=> $a['skor_akhir'];
          });

          return $hasil;
     }

     /**
      * Hitung skor untuk ekstrakurikuler tertentu
      */
     private function hitungSkorEkstrakurikuler(Siswa $siswa, Ekstrakurikuler $ekskul, $kriteria, $penilaianSiswa)
     {
          $totalSkor = 0;
          $totalBobot = 0;
          $detailSkor = [];

          foreach ($kriteria as $k) {
               $penilaian = $penilaianSiswa->get($k->id);

               if ($penilaian) {
                    // Normalisasi nilai (0-100 ke 0-1)
                    $nilaiNormal = $this->normalisasiNilai($penilaian->nilai, $k);

                    // Jika tipe cost, inversi nilai
                    if ($k->tipe === 'cost') {
                         $nilaiNormal = 1 - $nilaiNormal;
                    }

                    // Tambahan faktor kontekstual berdasarkan kriteria
                    $nilaiNormal = $this->applyContextualFactor($nilaiNormal, $k, $siswa, $ekskul);

                    $skorKriteria = $nilaiNormal * $k->bobot;
                    $totalSkor += $skorKriteria;
                    $totalBobot += $k->bobot;

                    $detailSkor[] = [
                         'kriteria' => $k->nama_kriteria,
                         'nilai_asli' => $penilaian->nilai,
                         'nilai_normal' => $nilaiNormal,
                         'bobot' => $k->bobot,
                         'skor' => $skorKriteria,
                         'tipe' => $k->tipe
                    ];
               }
          }

          $skorAkhir = $totalBobot > 0 ? ($totalSkor / $totalBobot) * 100 : 0;

          return [
               'skor_akhir' => round($skorAkhir, 2),
               'detail_skor' => $detailSkor
          ];
     }

     /**
      * Normalisasi nilai berdasarkan range kriteria
      */
     private function normalisasiNilai($nilai, Kriteria $kriteria)
     {
          // Normalisasi dasar 0-100 ke 0-1
          $normalized = $nilai / 100;

          // Pastikan nilai dalam range 0-1
          return max(0, min(1, $normalized));
     }

     /**
      * Terapkan faktor kontekstual
      */
     private function applyContextualFactor($nilaiNormal, Kriteria $kriteria, Siswa $siswa, Ekstrakurikuler $ekskul)
     {
          // Faktor minat siswa
          if ($siswa->minat && in_array($ekskul->kategori, $siswa->minat)) {
               $nilaiNormal *= 1.1; // Boost 10% jika sesuai minat
          }

          // Faktor kapasitas ekstrakurikuler
          if ($ekskul->sisaKuota() <= 3 && $ekskul->sisaKuota() > 0) {
               $nilaiNormal *= 0.95; // Sedikit penalty jika hampir penuh
          } elseif ($ekskul->isFull()) {
               $nilaiNormal *= 0.5; // Penalty besar jika sudah penuh
          }

          // Faktor jenis kelamin untuk olahraga tertentu
          if ($ekskul->kategori === 'Olahraga') {
               // Implementasi logika khusus jika diperlukan
          }

          return min(1, $nilaiNormal); // Pastikan tidak melebihi 1
     }

     /**
      * Get recommendation label
      */
     private function getRekomendasiLabel($skor)
     {
          if ($skor >= 85) return 'Sangat Direkomendasikan';
          if ($skor >= 75) return 'Direkomendasikan';
          if ($skor >= 65) return 'Cukup Direkomendasikan';
          if ($skor >= 55) return 'Kurang Direkomendasikan';
          return 'Tidak Direkomendasikan';
     }

     /**
      * Get confidence level
      */
     private function getConfidenceLevel($skor)
     {
          if ($skor >= 80) return 'Tinggi';
          if ($skor >= 60) return 'Sedang';
          return 'Rendah';
     }

     /**
      * Batch recommendation untuk multiple siswa
      */
     public function batchRecommendation($siswaIds, $limit = 3)
     {
          $hasil = [];

          foreach ($siswaIds as $siswaId) {
               $siswa = Siswa::find($siswaId);
               if ($siswa) {
                    $rekomendasi = $this->hitungRekomendasi($siswa);
                    $hasil[$siswaId] = [
                         'siswa' => $siswa,
                         'rekomendasi' => array_slice($rekomendasi, 0, $limit)
                    ];
               }
          }

          return $hasil;
     }

     /**
      * Analisis kelayakan ekstrakurikuler
      */
     public function analisisKelayakanEkstrakurikuler(Ekstrakurikuler $ekskul)
     {
          $allSiswa = Siswa::all();
          $totalSkor = 0;
          $jumlahSiswa = 0;
          $distribusiSkor = [
               'sangat_cocok' => 0,
               'cocok' => 0,
               'cukup' => 0,
               'kurang' => 0,
               'tidak_cocok' => 0
          ];

          foreach ($allSiswa as $siswa) {
               $rekomendasi = $this->hitungRekomendasi($siswa);
               $skorEkskul = collect($rekomendasi)->where('ekstrakurikuler.id', $ekskul->id)->first();

               if ($skorEkskul) {
                    $skor = $skorEkskul['skor_akhir'];
                    $totalSkor += $skor;
                    $jumlahSiswa++;

                    if ($skor >= 85) $distribusiSkor['sangat_cocok']++;
                    elseif ($skor >= 75) $distribusiSkor['cocok']++;
                    elseif ($skor >= 65) $distribusiSkor['cukup']++;
                    elseif ($skor >= 55) $distribusiSkor['kurang']++;
                    else $distribusiSkor['tidak_cocok']++;
               }
          }

          $rataRataSkor = $jumlahSiswa > 0 ? $totalSkor / $jumlahSiswa : 0;

          return [
               'rata_rata_skor' => round($rataRataSkor, 2),
               'distribusi_skor' => $distribusiSkor,
               'total_siswa_dievaluasi' => $jumlahSiswa,
               'tingkat_popularitas' => $this->getTingkatPopularitas($distribusiSkor),
               'rekomendasi_pengembangan' => $this->getRekomendasiPengembangan($rataRataSkor, $distribusiSkor)
          ];
     }

     /**
      * Get tingkat popularitas ekstrakurikuler
      */
     private function getTingkatPopularitas($distribusi)
     {
          $totalPositif = $distribusi['sangat_cocok'] + $distribusi['cocok'] + $distribusi['cukup'];
          $totalSiswa = array_sum($distribusi);

          if ($totalSiswa == 0) return 'Tidak Ada Data';

          $persentasePositif = ($totalPositif / $totalSiswa) * 100;

          if ($persentasePositif >= 70) return 'Sangat Populer';
          if ($persentasePositif >= 50) return 'Populer';
          if ($persentasePositif >= 30) return 'Cukup Populer';
          return 'Kurang Populer';
     }

     /**
      * Get rekomendasi pengembangan
      */
     private function getRekomendasiPengembangan($rataRata, $distribusi)
     {
          $rekomendasi = [];

          if ($rataRata < 60) {
               $rekomendasi[] = 'Perlu evaluasi ulang program dan metode pembelajaran';
               $rekomendasi[] = 'Pertimbangkan untuk menyesuaikan kriteria penerimaan';
          }

          if ($distribusi['tidak_cocok'] > $distribusi['sangat_cocok']) {
               $rekomendasi[] = 'Tingkatkan promosi dan sosialisasi ekstrakurikuler';
               $rekomendasi[] = 'Buat program persiapan untuk siswa yang berminat';
          }

          if ($distribusi['sangat_cocok'] > 50) {
               $rekomendasi[] = 'Pertimbangkan untuk menambah kuota atau membuka kelas paralel';
               $rekomendasi[] = 'Kembangkan program lanjutan untuk siswa berbakat';
          }

          return $rekomendasi;
     }
}
