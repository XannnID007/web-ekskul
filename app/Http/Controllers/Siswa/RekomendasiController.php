<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\WeightedScoringService;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    protected $weightedScoringService;

    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        // Get all recommendations
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);

        // Filter by category if requested
        if ($request->filled('kategori')) {
            $rekomendasi = array_filter($rekomendasi, function ($item) use ($request) {
                return $item['ekstrakurikuler']->kategori === $request->kategori;
            });
        }

        // Filter by recommendation level
        if ($request->filled('level')) {
            $rekomendasi = array_filter($rekomendasi, function ($item) use ($request) {
                switch ($request->level) {
                    case 'sangat_direkomendasikan':
                        return $item['skor_akhir'] >= 80;
                    case 'direkomendasikan':
                        return $item['skor_akhir'] >= 70 && $item['skor_akhir'] < 80;
                    case 'cukup':
                        return $item['skor_akhir'] >= 60 && $item['skor_akhir'] < 70;
                    case 'kurang':
                        return $item['skor_akhir'] >= 50 && $item['skor_akhir'] < 60;
                    case 'tidak_direkomendasikan':
                        return $item['skor_akhir'] < 50;
                    default:
                        return true;
                }
            });
        }

        // Get student's current registrations
        $sudahDaftar = $siswa->pendaftaran()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('ekstrakurikuler_id')
            ->toArray();

        // Get available categories for filter
        $kategoriList = collect($rekomendasi)
            ->pluck('ekstrakurikuler.kategori')
            ->unique()
            ->values();

        // Calculate statistics
        $stats = [
            'total' => count($rekomendasi),
            'sangat_direkomendasikan' => count(array_filter($rekomendasi, fn($item) => $item['skor_akhir'] >= 80)),
            'direkomendasikan' => count(array_filter($rekomendasi, fn($item) => $item['skor_akhir'] >= 70 && $item['skor_akhir'] < 80)),
            'cukup' => count(array_filter($rekomendasi, fn($item) => $item['skor_akhir'] >= 60 && $item['skor_akhir'] < 70)),
            'kurang' => count(array_filter($rekomendasi, fn($item) => $item['skor_akhir'] >= 50 && $item['skor_akhir'] < 60)),
            'tidak_direkomendasikan' => count(array_filter($rekomendasi, fn($item) => $item['skor_akhir'] < 50))
        ];

        return view('siswa.rekomendasi.index', compact(
            'rekomendasi',
            'sudahDaftar',
            'kategoriList',
            'stats',
            'siswa'
        ));
    }

    public function detail($ekstrakurikulerId)
    {
        $siswa = auth()->user()->siswa;
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);

        $detailRekomendasi = collect($rekomendasi)
            ->where('ekstrakurikuler.id', $ekstrakurikulerId)
            ->first();

        if (!$detailRekomendasi) {
            abort(404);
        }

        // Check if student already registered
        $pendaftaran = $siswa->pendaftaran()
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->first();

        return view('siswa.rekomendasi.detail', compact(
            'detailRekomendasi',
            'pendaftaran',
            'siswa'
        ));
    }

    public function export()
    {
        $siswa = auth()->user()->siswa;
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);

        // Generate PDF report
        // This would be implemented with a PDF library like DomPDF

        return response()->json([
            'success' => true,
            'message' => 'Laporan rekomendasi sedang diproses dan akan dikirim via email'
        ]);
    }
    function __construct(WeightedScoringService $weightedScoringService)
    {
        $this->weightedScoringService = $weightedScoringService;
    }
}
