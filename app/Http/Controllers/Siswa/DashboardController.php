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

          // Pastikan siswa memiliki relasi yang benar
          if (!$siswa) {
               return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan');
          }

          // EKSTRAKURIKULER YANG DIIKUTI
          $pendaftaranAktif = $siswa->pendaftaran()
               ->where('status', 'approved')
               ->with('ekstrakurikuler.pembina')
               ->get();

          // STATUS PENDAFTARAN PENDING
          $pendaftaranPending = $siswa->pendaftaran()
               ->where('status', 'pending')
               ->with('ekstrakurikuler')
               ->get();

          // TOP 3 REKOMENDASI
          try {
               $rekomendasi = app(WeightedScoringService::class)->hitungRekomendasi($siswa);
               $topRekomendasi = array_slice($rekomendasi, 0, 3);
          } catch (\Exception $e) {
               $topRekomendasi = [];
          }

          // PENGUMUMAN TERBARU
          $pengumuman = Pengumuman::where('is_published', true)
               ->latest('published_at')
               ->limit(3)
               ->get();

          // STATISTIK PERSONAL
          $stats = [
               'ekstrakurikuler_aktif' => $pendaftaranAktif->count(),
               'pendaftaran_pending' => $pendaftaranPending->count(),
               'nilai_akademik' => $siswa->nilai_akademik ?? 0,
               'rekomendasi_tersedia' => count($topRekomendasi)
          ];

          return view('siswa.dashboard', compact(
               'siswa',
               'pendaftaranAktif',
               'pendaftaranPending',
               'topRekomendasi',
               'pengumuman',
               'stats'
          ));
     }
}
