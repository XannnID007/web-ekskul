<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Kehadiran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PembinaController extends Controller
{
    public function dashboard()
    {
        $pembina = auth()->user();

        // Get ekstrakurikuler yang dibina
        $ekstrakurikuler = Ekstrakurikuler::where('pembina_id', $pembina->id)
            ->withCount(['pendaftaran' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->get();

        // Statistik
        $totalEkskul = $ekstrakurikuler->count();
        $totalAnggota = $ekstrakurikuler->sum('pendaftaran_count');
        $pendaftaranPending = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
            $q->where('pembina_id', $pembina->id);
        })->where('status', 'pending')->count();

        // Recent registrations
        $pendaftaranTerbaru = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
            $q->where('pembina_id', $pembina->id);
        })->with(['siswa.user', 'ekstrakurikuler'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pembina.dashboard', compact(
            'ekstrakurikuler',
            'totalEkskul',
            'totalAnggota',
            'pendaftaranPending',
            'pendaftaranTerbaru'
        ));
    }

    public function ekstrakurikuler()
    {
        $pembina = auth()->user();
        $ekstrakurikuler = Ekstrakurikuler::where('pembina_id', $pembina->id)
            ->withCount(['pendaftaran' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->paginate(10);

        return view('pembina.ekstrakurikuler.index', compact('ekstrakurikuler'));
    }

    public function detailEkstrakurikuler(Ekstrakurikuler $ekstrakurikuler)
    {
        // Check authorization
        if ($ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        $ekstrakurikuler->load(['anggotaAktif.siswa.user']);

        return view('pembina.ekstrakurikuler.detail', compact('ekstrakurikuler'));
    }

    public function pendaftaran(Request $request)
    {
        $pembina = auth()->user();

        $query = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
            $q->where('pembina_id', $pembina->id);
        })->with(['siswa.user', 'ekstrakurikuler']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ekstrakurikuler')) {
            $query->where('ekstrakurikuler_id', $request->ekstrakurikuler);
        }

        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(10);

        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        return view('pembina.pendaftaran.index', compact('pendaftaran', 'ekstrakurikulerList'));
    }

    public function approvePendaftaran(Request $request, Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'nullable|string'
        ]);

        // Check capacity
        if ($pendaftaran->ekstrakurikuler->isFull()) {
            return response()->json([
                'success' => false,
                'message' => 'Kapasitas ekstrakurikuler sudah penuh'
            ]);
        }

        $pendaftaran->approve($request->catatan);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil disetujui'
        ]);
    }

    public function rejectPendaftaran(Request $request, Pendaftaran $pendaftaran)
    {
        // Check authorization
        if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'required|string'
        ]);

        $pendaftaran->reject($request->catatan);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil ditolak'
        ]);
    }

    public function kehadiran(Request $request)
    {
        $pembina = auth()->user();

        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        $selectedEkskul = null;
        $anggota = collect();

        if ($request->filled('ekstrakurikuler_id')) {
            $selectedEkskul = Ekstrakurikuler::where('pembina_id', $pembina->id)
                ->where('id', $request->ekstrakurikuler_id)
                ->first();

            if ($selectedEkskul) {
                $anggota = $selectedEkskul->anggotaAktif()
                    ->with(['siswa.user', 'kehadiran' => function ($q) use ($request) {
                        if ($request->filled('tanggal')) {
                            $q->where('tanggal', $request->tanggal);
                        }
                    }])
                    ->get();
            }
        }

        return view('pembina.kehadiran.index', compact(
            'ekstrakurikulerList',
            'selectedEkskul',
            'anggota'
        ));
    }

    public function simpanKehadiran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'required|in:hadir,tidak_hadir,izin,sakit'
        ]);

        foreach ($request->kehadiran as $pendaftaranId => $status) {
            $pendaftaran = Pendaftaran::find($pendaftaranId);

            // Check authorization
            if ($pendaftaran->ekstrakurikuler->pembina_id !== auth()->id()) {
                continue;
            }

            Kehadiran::updateOrCreate(
                [
                    'pendaftaran_id' => $pendaftaranId,
                    'tanggal' => $request->tanggal
                ],
                [
                    'status' => $status,
                    'keterangan' => $request->keterangan[$pendaftaranId] ?? null
                ]
            );
        }

        return back()->with('success', 'Data kehadiran berhasil disimpan');
    }

    public function pengumuman()
    {
        $pembina = auth()->user();
        $pengumuman = Pengumuman::where('author_id', $pembina->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pembina.pengumuman.index', compact('pengumuman'));
    }

    public function createPengumuman()
    {
        return view('pembina.pengumuman.create');
    }

    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:umum,ekstrakurikuler'
        ]);

        $pengumuman = Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'author_id' => auth()->id(),
            'is_published' => $request->has('publish'),
            'published_at' => $request->has('publish') ? now() : null
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }
}
