<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $pembina = auth()->user();

        $query = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
            $q->where('pembina_id', $pembina->id);
        })->with(['siswa.user', 'ekstrakurikuler']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by ekstrakurikuler
        if ($request->filled('ekstrakurikuler_id')) {
            $query->where('ekstrakurikuler_id', $request->ekstrakurikuler_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('siswa', function ($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get ekstrakurikuler list for filter
        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        // Get counts for badges
        $counts = [
            'pending' => Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
                $q->where('pembina_id', $pembina->id);
            })->where('status', 'pending')->count(),
            'approved' => Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
                $q->where('pembina_id', $pembina->id);
            })->where('status', 'approved')->count(),
            'rejected' => Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
                $q->where('pembina_id', $pembina->id);
            })->where('status', 'rejected')->count(),
        ];

        return view('pembina.pendaftaran.index', compact(
            'pendaftaran',
            'ekstrakurikulerList',
            'counts'
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
            'ekstrakurikuler'
        ]);

        return view('pembina.pendaftaran.detail', compact('pendaftaran'));
    }

    public function approve(Request $request, Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran sudah diproses sebelumnya'
            ]);
        }

        // Check capacity
        if ($pendaftaran->ekstrakurikuler->isFull()) {
            return response()->json([
                'success' => false,
                'message' => 'Kapasitas ekstrakurikuler sudah penuh'
            ]);
        }

        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);

        $pendaftaran->update([
            'status' => 'approved',
            'catatan_pembina' => $request->catatan,
            'tanggal_persetujuan' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil disetujui'
        ]);
    }

    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran sudah diproses sebelumnya'
            ]);
        }

        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        $pendaftaran->update([
            'status' => 'rejected',
            'catatan_pembina' => $request->catatan,
            'tanggal_persetujuan' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil ditolak'
        ]);
    }

    public function batchApprove(Request $request)
    {
        $pembina = auth()->user();

        $request->validate([
            'pendaftaran_ids' => 'required|array',
            'pendaftaran_ids.*' => 'exists:pendaftaran,id',
            'catatan' => 'nullable|string|max:500'
        ]);

        $pendaftaranList = Pendaftaran::whereIn('id', $request->pendaftaran_ids)
            ->whereHas('ekstrakurikuler', function ($q) use ($pembina) {
                $q->where('pembina_id', $pembina->id);
            })
            ->where('status', 'pending')
            ->get();

        $approved = 0;
        $failed = 0;

        foreach ($pendaftaranList as $pendaftaran) {
            if (!$pendaftaran->ekstrakurikuler->isFull()) {
                $pendaftaran->update([
                    'status' => 'approved',
                    'catatan_pembina' => $request->catatan,
                    'tanggal_persetujuan' => now()
                ]);
                $approved++;
            } else {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$approved} pendaftaran disetujui, {$failed} gagal karena kapasitas penuh"
        ]);
    }
}
