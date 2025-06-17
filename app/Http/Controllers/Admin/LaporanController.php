<?php

namespace App\Http\Controllers\Admin;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Ekstrakurikuler;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function siswa(Request $request)
    {
        $query = Siswa::with(['user', 'pendaftaran.ekstrakurikuler']);

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $siswa = $query->get();
        $kelasList = Siswa::distinct()->pluck('kelas');

        // Statistik siswa
        $stats = [
            'total_siswa' => $siswa->count(),
            'siswa_aktif_ekskul' => $siswa->filter(function ($s) {
                return $s->pendaftaran->where('status', 'approved')->count() > 0;
            })->count(),
            'rata_rata_nilai' => $siswa->avg('nilai_akademik'),
            'distribusi_kelas' => $siswa->groupBy('kelas')->map->count(),
        ];

        return view('admin.laporan.siswa', compact('siswa', 'kelasList', 'stats'));
    }

    public function ekstrakurikuler(Request $request)
    {
        $ekstrakurikuler = Ekstrakurikuler::with(['pembina', 'pendaftaran' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        // Statistik ekstrakurikuler
        $stats = [
            'total_ekstrakurikuler' => $ekstrakurikuler->count(),
            'rata_rata_anggota' => $ekstrakurikuler->avg(function ($e) {
                return $e->pendaftaran->count();
            }),
            'ekstrakurikuler_penuh' => $ekstrakurikuler->filter(function ($e) {
                return $e->pendaftaran->count() >= $e->kapasitas_maksimal;
            })->count(),
            'kategori_populer' => $ekstrakurikuler->groupBy('kategori')->map->count()->sortDesc()->first(),
        ];

        return view('admin.laporan.ekstrakurikuler', compact('ekstrakurikuler', 'stats'));
    }

    public function rekomendasi(Request $request)
    {
        // Laporan efektivitas sistem rekomendasi
        $rekomendasiStats = DB::table('pendaftaran')
            ->select(
                DB::raw('AVG(skor_rekomendasi) as rata_skor'),
                DB::raw('COUNT(CASE WHEN skor_rekomendasi >= 80 THEN 1 END) as sangat_direkomendasikan'),
                DB::raw('COUNT(CASE WHEN skor_rekomendasi >= 70 AND skor_rekomendasi < 80 THEN 1 END) as direkomendasikan'),
                DB::raw('COUNT(CASE WHEN skor_rekomendasi < 70 THEN 1 END) as kurang_direkomendasikan')
            )
            ->where('status', 'approved')
            ->first();

        return view('admin.laporan.rekomendasi', compact('rekomendasiStats'));
    }

    public function export(Request $request)
    {
        // Implementasi export laporan (PDF/Excel)
        return response()->json([
            'success' => true,
            'message' => 'Laporan sedang diproses dan akan dikirim via email'
        ]);
    }
}
