<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Services\WeightedScoringService;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    protected $weightedScoringService;

    public function __construct(WeightedScoringService $weightedScoringService)
    {
        $this->weightedScoringService = $weightedScoringService;
    }

    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $query = $siswa->pendaftaran()->with(['ekstrakurikuler.pembina']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get counts for status badges
        $counts = [
            'all' => $siswa->pendaftaran()->count(),
            'pending' => $siswa->pendaftaran()->where('status', 'pending')->count(),
            'approved' => $siswa->pendaftaran()->where('status', 'approved')->count(),
            'rejected' => $siswa->pendaftaran()->where('status', 'rejected')->count(),
        ];

        return view('siswa.pendaftaran.index', compact('pendaftaran', 'counts'));
    }

    public function daftar(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        if (!$ekstrakurikuler->is_active) {
            return back()->with('error', 'Ekstrakurikuler tidak aktif');
        }

        $siswa = auth()->user()->siswa;

        // Check if already registered
        $existingRegistration = $siswa->pendaftaran()
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Anda sudah mendaftar ekstrakurikuler ini');
        }

        // Check capacity
        if ($ekstrakurikuler->isFull()) {
            return back()->with('error', 'Kapasitas ekstrakurikuler sudah penuh');
        }

        // Check maximum registrations per student (optional rule)
        $totalActiveRegistrations = $siswa->pendaftaran()
            ->where('status', 'approved')
            ->count();

        if ($totalActiveRegistrations >= 2) { // Max 2 ekstrakurikuler
            return back()->with('error', 'Anda sudah mencapai batas maksimal ekstrakurikuler (2)');
        }

        $request->validate([
            'alasan_daftar' => 'required|string|min:20|max:500'
        ]);

        // Calculate recommendation score
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);
        $skorEkskul = collect($rekomendasi)->where('ekstrakurikuler.id', $ekstrakurikuler->id)->first();
        $skorRekomendasi = $skorEkskul ? $skorEkskul['skor_akhir'] : 0;

        Pendaftaran::create([
            'siswa_id' => $siswa->id,
            'ekstrakurikuler_id' => $ekstrakurikuler->id,
            'alasan_daftar' => $request->alasan_daftar,
            'skor_rekomendasi' => $skorRekomendasi,
            'status' => 'pending',
            'tanggal_daftar' => now()
        ]);

        return back()->with('success', 'Pendaftaran berhasil dikirim. Menunggu persetujuan pembina.');
    }

    public function show(Pendaftaran $pendaftaran)
    {
        // Check ownership
        if ($pendaftaran->siswa_id !== auth()->user()->siswa->id) {
            abort(403);
        }

        $pendaftaran->load([
            'ekstrakurikuler.pembina',
            'kehadiran' => function ($q) {
                $q->orderBy('tanggal', 'desc')->limit(10);
            }
        ]);

        // Calculate attendance statistics if approved
        $attendanceStats = null;
        if ($pendaftaran->status === 'approved') {
            $totalPertemuan = $pendaftaran->kehadiran->count();
            $hadir = $pendaftaran->kehadiran->where('status', 'hadir')->count();
            $attendanceStats = [
                'total_pertemuan' => $totalPertemuan,
                'hadir' => $hadir,
                'persentase' => $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0
            ];
        }

        return view('siswa.pendaftaran.detail', compact('pendaftaran', 'attendanceStats'));
    }

    public function cancel(Pendaftaran $pendaftaran)
    {
        // Check ownership
        if ($pendaftaran->siswa_id !== auth()->user()->siswa->id) {
            abort(403);
        }

        // Only allow cancellation for pending registrations
        if ($pendaftaran->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pendaftaran dengan status pending yang dapat dibatalkan'
            ]);
        }

        $pendaftaran->update([
            'status' => 'rejected',
            'catatan_pembina' => 'Dibatalkan oleh siswa',
            'tanggal_persetujuan' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dibatalkan'
        ]);
    }
}
