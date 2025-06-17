<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function kehadiran()
    {
        $siswa = auth()->user()->siswa;
        $pendaftaranAktif = $siswa->pendaftaran()
            ->where('status', 'approved')
            ->with(['ekstrakurikuler', 'kehadiran' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            }])
            ->get();

        // Calculate attendance statistics for each
        $attendanceStats = [];
        foreach ($pendaftaranAktif as $pendaftaran) {
            $totalPertemuan = $pendaftaran->kehadiran->count();
            $hadir = $pendaftaran->kehadiran->where('status', 'hadir')->count();
            $persentase = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;

            $attendanceStats[$pendaftaran->id] = [
                'total_pertemuan' => $totalPertemuan,
                'hadir' => $hadir,
                'persentase' => round($persentase, 1)
            ];
        }

        return view('siswa.kehadiran', compact('pendaftaranAktif', 'attendanceStats'));
    }
}
