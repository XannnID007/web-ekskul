<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $pembina = auth()->user();

        // Get ekstrakurikuler yang dibina
        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        $selectedEkskul = null;
        $anggota = collect();

        if ($request->filled('ekstrakurikuler_id')) {
            $selectedEkskul = Ekstrakurikuler::where('pembina_id', $pembina->id)
                ->where('id', $request->ekstrakurikuler_id)
                ->first();

            if ($selectedEkskul) {
                $query = $selectedEkskul->pendaftaran()
                    ->where('status', 'approved')
                    ->with(['siswa.user', 'kehadiran' => function ($q) {
                        $q->latest()->limit(5);
                    }]);

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->whereHas('siswa.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('siswa', function ($q) use ($search) {
                        $q->where('nisn', 'like', "%{$search}%")
                            ->orWhere('kelas', 'like', "%{$search}%");
                    });
                }

                $anggota = $query->paginate(10);
            }
        }

        return view('pembina.anggota.index', compact(
            'ekstrakurikulerList',
            'selectedEkskul',
            'anggota'
        ));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        $pendaftaran->load([
            'siswa.user',
            'siswa.penilaianSiswa.kriteria',
            'kehadiran' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            }
        ]);

        // Calculate attendance statistics
        $totalPertemuan = $pendaftaran->kehadiran->count();
        $hadir = $pendaftaran->kehadiran->where('status', 'hadir')->count();
        $persentaseKehadiran = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;

        return view('pembina.anggota.detail', compact(
            'pendaftaran',
            'totalPertemuan',
            'hadir',
            'persentaseKehadiran'
        ));
    }

    public function removeAnggota(Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak aktif'
            ]);
        }

        $pendaftaran->update([
            'status' => 'rejected',
            'catatan_pembina' => 'Dikeluarkan dari ekstrakurikuler',
            'tanggal_persetujuan' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dikeluarkan dari ekstrakurikuler'
        ]);
    }
}
