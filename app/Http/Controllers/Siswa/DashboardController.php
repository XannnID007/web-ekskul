<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\{Pendaftaran, Pengumuman};
use App\Services\WeightedScoringService;

class DashboardController extends Controller
{
     public function index()
     {
          $siswa = auth()->user()->siswa;

          // EKSTRAKURIKULER YANG DIIKUTI
          $myEkskul = $siswa->pendaftaran()
               ->where('status', 'approved')
               ->with('ekstrakurikuler.pembina')
               ->get();

          // STATUS PENDAFTARAN PENDING
          $pendingStatus = $siswa->pendaftaran()
               ->where('status', 'pending')
               ->with('ekstrakurikuler')
               ->get();

          // TOP 3 REKOMENDASI
          $rekomendasi = app(WeightedScoringService::class)->hitungRekomendasi($siswa);
          $topRekomendasi = array_slice($rekomendasi, 0, 3);

          // PENGUMUMAN TERBARU
          $pengumuman = Pengumuman::where('is_published', true)
               ->latest('published_at')
               ->limit(3)
               ->get();

          // STATISTIK PERSONAL
          $stats = [
               'ekstrakurikuler_aktif' => $myEkskul->count(),
               'pendaftaran_pending' => $pendingStatus->count(),
               'nilai_akademik' => $siswa->nilai_akademik,
               'rekomendasi_tersedia' => count($rekomendasi)
          ];

          return view('siswa.dashboard', compact(
               'siswa',
               'myEkskul',
               'pendingStatus',
               'topRekomendasi',
               'pengumuman',
               'stats'
          ));
     }
}
