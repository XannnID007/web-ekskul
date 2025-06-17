<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Kehadiran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

     public function index()
     {
          $pembina = auth()->user();

          // EKSTRAKURIKULER YANG DIBINA
          $myEkskul = Ekstrakurikuler::where('pembina_id', $pembina->id)
               ->withCount(['pendaftaran' => function ($q) {
                    $q->where('status', 'approved');
               }])
               ->get();

          // PENDAFTARAN YANG PERLU DIPROSES
          $pendingApprovals = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembina) {
               $q->where('pembina_id', $pembina->id);
          })
               ->where('status', 'pending')
               ->with(['siswa.user', 'ekstrakurikuler'])
               ->latest()
               ->limit(5)
               ->get();

          // STATISTIK KEHADIRAN MINGGU INI
          $weeklyStats = $this->getWeeklyAttendanceStats($pembina->id);

          return view('pembina.dashboard', compact('myEkskul', 'pendingApprovals', 'weeklyStats'));
     }

     private function getWeeklyAttendanceStats($pembinaId)
     {
          $startOfWeek = now()->startOfWeek();
          $endOfWeek = now()->endOfWeek();

          $totalAnggota = Pendaftaran::whereHas('ekstrakurikuler', function ($q) use ($pembinaId) {
               $q->where('pembina_id', $pembinaId);
          })
               ->where('status', 'approved')
               ->count();

          $hadirMingguIni = Kehadiran::whereHas('pendaftaran.ekstrakurikuler', function ($q) use ($pembinaId) {
               $q->where('pembina_id', $pembinaId);
          })
               ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
               ->where('status', 'hadir')
               ->count();

          return [
               'total_anggota' => $totalAnggota,
               'hadir_minggu_ini' => $hadirMingguIni,
               'persentase' => $totalAnggota > 0 ? round(($hadirMingguIni / $totalAnggota) * 100, 1) : 0
          ];
     }
}
