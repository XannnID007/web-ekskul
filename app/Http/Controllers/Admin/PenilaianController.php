<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'penilaianSiswa.kriteria']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%");
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $siswa = $query->paginate(10);
        $kriteria = Kriteria::where('is_active', true)->get();
        $kelasList = Siswa::distinct()->pluck('kelas');

        return view('admin.penilaian.index', compact('siswa', 'kriteria', 'kelasList'));
    }

    public function editSiswa(Siswa $siswa)
    {
        $kriteria = Kriteria::where('is_active', true)->get();
        $penilaian = $siswa->penilaianSiswa->keyBy('kriteria_id');

        // Ensure all criteria have assessment records
        foreach ($kriteria as $k) {
            if (!$penilaian->has($k->id)) {
                PenilaianSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kriteria_id' => $k->id,
                    'nilai' => 50 // Default value
                ]);
            }
        }

        $penilaian = $siswa->fresh()->penilaianSiswa->keyBy('kriteria_id');

        return view('admin.penilaian.edit', compact('siswa', 'kriteria', 'penilaian'));
    }

    public function updateSiswa(Request $request, Siswa $siswa)
    {
        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*' => 'required|numeric|min:0|max:100'
        ]);

        DB::transaction(function () use ($request, $siswa) {
            foreach ($request->penilaian as $kriteriaId => $nilai) {
                PenilaianSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'kriteria_id' => $kriteriaId
                    ],
                    ['nilai' => $nilai]
                );
            }
        });

        return redirect()->route('admin.penilaian.index')
            ->with('success', 'Penilaian siswa berhasil diupdate');
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kriteria_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        foreach ($request->siswa_ids as $siswaId) {
            PenilaianSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'kriteria_id' => $request->kriteria_id
                ],
                ['nilai' => $request->nilai]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Penilaian batch berhasil diupdate'
        ]);
    }
}
