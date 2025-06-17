<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Services\WeightedScoringService;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    protected $weightedScoringService;

    public function __construct(WeightedScoringService $weightedScoringService)
    {
        $this->weightedScoringService = $weightedScoringService;
    }

    public function dashboard()
    {
        $siswa = auth()->user()->siswa;

        // Get student's registrations
        $pendaftaranAktif = $siswa->pendaftaran()
            ->where('status', 'approved')
            ->with('ekstrakurikuler')
            ->get();

        $pendaftaranPending = $siswa->pendaftaran()
            ->where('status', 'pending')
            ->with('ekstrakurikuler')
            ->get();

        // Get recent announcements
        $pengumuman = Pengumuman::where('is_published', true)
            ->whereIn('kategori', ['umum', 'ekstrakurikuler'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get recommendations
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);
        $topRekomendasi = array_slice($rekomendasi, 0, 3);

        return view('siswa.dashboard', compact(
            'siswa',
            'pendaftaranAktif',
            'pendaftaranPending',
            'pengumuman',
            'topRekomendasi'
        ));
    }

    public function ekstrakurikuler(Request $request)
    {
        $query = Ekstrakurikuler::where('is_active', true)
            ->with('pembina')
            ->withCount(['pendaftaran' => function ($q) {
                $q->where('status', 'approved');
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_ekskul', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $ekstrakurikuler = $query->paginate(9);
        $kategoriList = Ekstrakurikuler::where('is_active', true)->distinct()->pluck('kategori');
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Get student's current registrations
        $siswa = auth()->user()->siswa;
        $sudahDaftar = $siswa->pendaftaran()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('ekstrakurikuler_id')
            ->toArray();

        return view('siswa.ekstrakurikuler.index', compact(
            'ekstrakurikuler',
            'kategoriList',
            'hariList',
            'sudahDaftar'
        ));
    }

    public function detailEkstrakurikuler(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->load('pembina', 'anggotaAktif.siswa.user');

        $siswa = auth()->user()->siswa;
        $pendaftaran = $siswa->pendaftaran()
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->first();

        return view('siswa.ekstrakurikuler.detail', compact('ekstrakurikuler', 'pendaftaran'));
    }

    public function daftarEkstrakurikuler(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
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
        $totalRegistrations = $siswa->pendaftaran()
            ->where('status', 'approved')
            ->count();

        if ($totalRegistrations >= 2) { // Max 2 ekstrakurikuler
            return back()->with('error', 'Anda sudah mencapai batas maksimal ekstrakurikuler (2)');
        }

        $request->validate([
            'alasan_daftar' => 'required|string|min:10'
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
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pendaftaran berhasil dikirim. Menunggu persetujuan pembina.');
    }

    public function rekomendasi()
    {
        $siswa = auth()->user()->siswa;
        $rekomendasi = $this->weightedScoringService->hitungRekomendasi($siswa);

        // Get student's current registrations
        $sudahDaftar = $siswa->pendaftaran()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('ekstrakurikuler_id')
            ->toArray();

        return view('siswa.rekomendasi', compact('rekomendasi', 'sudahDaftar'));
    }

    public function pendaftaran()
    {
        $siswa = auth()->user()->siswa;
        $pendaftaran = $siswa->pendaftaran()
            ->with(['ekstrakurikuler.pembina'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.pendaftaran', compact('pendaftaran'));
    }

    public function kehadiran()
    {
        $siswa = auth()->user()->siswa;
        $pendaftaranAktif = $siswa->pendaftaran()
            ->where('status', 'approved')
            ->with(['ekstrakurikuler', 'kehadiran' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            }])
            ->get();

        return view('siswa.kehadiran', compact('pendaftaranAktif'));
    }

    public function profil()
    {
        $siswa = auth()->user()->siswa;
        return view('siswa.profil', compact('siswa'));
    }

    public function updateProfil(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'phone' => 'nullable|string',
            'alamat' => 'required|string',
            'minat' => 'array'
        ]);

        // Update user
        $siswa->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        // Update siswa
        $siswa->update([
            'alamat' => $request->alamat,
            'minat' => $request->minat ?? []
        ]);

        return back()->with('success', 'Profil berhasil diupdate');
    }
}
