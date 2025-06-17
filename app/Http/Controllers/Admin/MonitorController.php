<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Kehadiran;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function aktivitas(Request $request)
    {
        // Monitor aktivitas sistem secara keseluruhan
        $activities = DB::table('pendaftaran')
            ->join('siswa', 'pendaftaran.siswa_id', '=', 'siswa.id')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('ekstrakurikuler', 'pendaftaran.ekstrakurikuler_id', '=', 'ekstrakurikuler.id')
            ->select(
                'pendaftaran.*',
                'users.name as siswa_name',
                'ekstrakurikuler.nama_ekskul',
                'siswa.kelas'
            )
            ->orderBy('pendaftaran.created_at', 'desc')
            ->paginate(20);

        // Statistik aktivitas
        $stats = [
            'total_pendaftaran_hari_ini' => Pendaftaran::whereDate('created_at', today())->count(),
            'total_persetujuan_hari_ini' => Pendaftaran::whereDate('tanggal_persetujuan', today())->count(),
            'pending_approval' => Pendaftaran::where('status', 'pending')->count(),
            'active_users_today' => User::whereDate('updated_at', today())->count(),
        ];

        return view('admin.monitor.aktivitas', compact('activities', 'stats'));
    }

    public function pendaftaran(Request $request)
    {
        $query = Pendaftaran::with(['siswa.user', 'ekstrakurikuler.pembina']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistik pendaftaran
        $stats = [
            'total' => Pendaftaran::count(),
            'pending' => Pendaftaran::where('status', 'pending')->count(),
            'approved' => Pendaftaran::where('status', 'approved')->count(),
            'rejected' => Pendaftaran::where('status', 'rejected')->count(),
        ];

        return view('admin.monitor.pendaftaran', compact('pendaftaran', 'stats'));
    }

    public function kehadiran(Request $request)
    {
        // Monitor kehadiran per ekstrakurikuler
        $kehadiranStats = DB::table('kehadiran')
            ->join('pendaftaran', 'kehadiran.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('ekstrakurikuler', 'pendaftaran.ekstrakurikuler_id', '=', 'ekstrakurikuler.id')
            ->select(
                'ekstrakurikuler.nama_ekskul',
                'ekstrakurikuler.id',
                DB::raw('COUNT(kehadiran.id) as total_pertemuan'),
                DB::raw('SUM(CASE WHEN kehadiran.status = "hadir" THEN 1 ELSE 0 END) as total_hadir'),
                DB::raw('ROUND(AVG(CASE WHEN kehadiran.status = "hadir" THEN 100 ELSE 0 END), 2) as persentase_kehadiran')
            )
            ->groupBy('ekstrakurikuler.id', 'ekstrakurikuler.nama_ekskul')
            ->orderBy('persentase_kehadiran', 'desc')
            ->get();

        // Kehadiran harian (7 hari terakhir)
        $dailyAttendance = DB::table('kehadiran')
            ->select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir')
            )
            ->where('tanggal', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('date')
            ->get();

        return view('admin.monitor.kehadiran', compact('kehadiranStats', 'dailyAttendance'));
    }
}
