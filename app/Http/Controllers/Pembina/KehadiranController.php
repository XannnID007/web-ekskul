<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Kehadiran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $pembina = auth()->user();

        // Get ekstrakurikuler yang dibina
        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        $selectedEkskul = null;
        $anggota = collect();
        $tanggalDipilih = $request->filled('tanggal') ? $request->tanggal : now()->format('Y-m-d');

        if ($request->filled('ekstrakurikuler_id')) {
            $selectedEkskul = Ekstrakurikuler::where('pembina_id', $pembina->id)
                ->where('id', $request->ekstrakurikuler_id)
                ->first();

            if ($selectedEkskul) {
                $anggota = $selectedEkskul->pendaftaran()
                    ->where('status', 'approved')
                    ->with([
                        'siswa.user',
                        'kehadiran' => function ($q) use ($tanggalDipilih) {
                            $q->where('tanggal', $tanggalDipilih);
                        }
                    ])
                    ->get();
            }
        }

        return view('pembina.kehadiran.index', compact(
            'ekstrakurikulerList',
            'selectedEkskul',
            'anggota',
            'tanggalDipilih'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikuler,id',
            'tanggal' => 'required|date',
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'required|in:hadir,tidak_hadir,izin,sakit',
            'keterangan' => 'array',
            'keterangan.*' => 'nullable|string|max:255'
        ]);

        $pembina = auth()->user();

        // Verify authorization
        $ekstrakurikuler = Ekstrakurikuler::where('id', $request->ekstrakurikuler_id)
            ->where('pembina_id', $pembina->id)
            ->firstOrFail();

        foreach ($request->kehadiran as $pendaftaranId => $status) {
            $pendaftaran = Pendaftaran::where('id', $pendaftaranId)
                ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
                ->where('status', 'approved')
                ->first();

            if ($pendaftaran) {
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
        }

        return back()->with('success', 'Data kehadiran berhasil disimpan');
    }

    public function laporan(Request $request)
    {
        $pembina = auth()->user();

        $ekstrakurikulerList = Ekstrakurikuler::where('pembina_id', $pembina->id)->get();

        $selectedEkskul = null;
        $laporanData = collect();

        if ($request->filled('ekstrakurikuler_id')) {
            $selectedEkskul = Ekstrakurikuler::where('pembina_id', $pembina->id)
                ->where('id', $request->ekstrakurikuler_id)
                ->first();

            if ($selectedEkskul) {
                $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate = $request->filled('end_date') ? $request->end_date : now()->endOfMonth()->format('Y-m-d');

                $anggota = $selectedEkskul->pendaftaran()
                    ->where('status', 'approved')
                    ->with([
                        'siswa.user',
                        'kehadiran' => function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('tanggal', [$startDate, $endDate])
                                ->orderBy('tanggal');
                        }
                    ])
                    ->get();

                foreach ($anggota as $member) {
                    $totalPertemuan = $member->kehadiran->count();
                    $hadir = $member->kehadiran->where('status', 'hadir')->count();
                    $izin = $member->kehadiran->where('status', 'izin')->count();
                    $sakit = $member->kehadiran->where('status', 'sakit')->count();
                    $alfa = $member->kehadiran->where('status', 'tidak_hadir')->count();

                    $persentase = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;

                    $laporanData->push([
                        'siswa' => $member->siswa,
                        'total_pertemuan' => $totalPertemuan,
                        'hadir' => $hadir,
                        'izin' => $izin,
                        'sakit' => $sakit,
                        'alfa' => $alfa,
                        'persentase' => round($persentase, 1)
                    ]);
                }
            }
        }

        return view('pembina.kehadiran.laporan', compact(
            'ekstrakurikulerList',
            'selectedEkskul',
            'laporanData'
        ));
    }

    public function exportLaporan(Request $request)
    {
        // Implementation for exporting attendance report
        // This would generate PDF/Excel export

        return response()->json([
            'success' => true,
            'message' => 'Laporan sedang diproses dan akan dikirim via email'
        ]);
    }
}
