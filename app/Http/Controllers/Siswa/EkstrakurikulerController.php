<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Services\WeightedScoringService;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    protected $weightedScoringService;

    public function __construct(WeightedScoringService $weightedScoringService)
    {
        $this->weightedScoringService = $weightedScoringService;
    }

    public function index(Request $request)
    {
        $query = Ekstrakurikuler::where('is_active', true)
            ->with(['pembina'])
            ->withCount(['pendaftaran' => function ($q) {
                $q->where('status', 'approved');
            }]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_ekskul', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by day
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        // Filter by availability
        if ($request->filled('tersedia')) {
            if ($request->tersedia === 'ya') {
                $query->whereRaw('(SELECT COUNT(*) FROM pendaftaran WHERE ekstrakurikuler_id = ekstrakurikuler.id AND status = "approved") < kapasitas_maksimal');
            } else {
                $query->whereRaw('(SELECT COUNT(*) FROM pendaftaran WHERE ekstrakurikuler_id = ekstrakurikuler.id AND status = "approved") >= kapasitas_maksimal');
            }
        }

        // Sort by recommendation if requested
        if ($request->filled('sort') && $request->sort === 'rekomendasi') {
            $siswa = auth()->user()->siswa;
            $ekstrakurikuler = $query->get();
            $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);

            // Sort ekstrakurikuler by recommendation score
            $sortedIds = collect($rekomendasi)->pluck('ekstrakurikuler.id')->toArray();
            $ekstrakurikuler = $ekstrakurikuler->sortBy(function ($item) use ($sortedIds) {
                return array_search($item->id, $sortedIds);
            });

            $ekstrakurikuler = new \Illuminate\Pagination\LengthAwarePaginator(
                $ekstrakurikuler->forPage($request->get('page', 1), 9),
                $ekstrakurikuler->count(),
                9,
                $request->get('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            $ekstrakurikuler = $query->paginate(9);
        }

        // Get filter options
        $kategoriList = Ekstrakurikuler::where('is_active', true)->distinct()->pluck('kategori');
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Get student's current registrations
        $siswa = auth()->user()->siswa;
        $sudahDaftar = $siswa->pendaftaran()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('ekstrakurikuler_id')
            ->toArray();

        // Get recommendation scores for each ekstrakurikuler
        $rekomendasiScores = [];
        if ($siswa) {
            $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);
            foreach ($rekomendasi as $item) {
                $rekomendasiScores[$item['ekstrakurikuler']->id] = $item['skor_akhir'];
            }
        }

        return view('siswa.ekstrakurikuler.index', compact(
            'ekstrakurikuler',
            'kategoriList',
            'hariList',
            'sudahDaftar',
            'rekomendasiScores'
        ));
    }

    public function detail(Ekstrakurikuler $ekstrakurikuler)
    {
        if (!$ekstrakurikuler->is_active) {
            abort(404);
        }

        $ekstrakurikuler->load(['pembina', 'pendaftaran' => function ($q) {
            $q->where('status', 'approved')->with('siswa.user');
        }]);

        $siswa = auth()->user()->siswa;

        // Check if student already registered
        $pendaftaran = $siswa->pendaftaran()
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->first();

        // Get recommendation data for this ekstrakurikuler
        $rekomendasiData = null;
        if ($siswa) {
            $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);
            $rekomendasiData = collect($rekomendasi)
                ->where('ekstrakurikuler.id', $ekstrakurikuler->id)
                ->first();
        }

        // Get similar ekstrakurikuler (same category)
        $similar = Ekstrakurikuler::where('is_active', true)
            ->where('kategori', $ekstrakurikuler->kategori)
            ->where('id', '!=', $ekstrakurikuler->id)
            ->withCount(['pendaftaran' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->limit(3)
            ->get();

        return view('siswa.ekstrakurikuler.detail', compact(
            'ekstrakurikuler',
            'pendaftaran',
            'rekomendasiData',
            'similar'
        ));
    }
}
