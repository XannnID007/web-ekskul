<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // STATISTIK SISTEM
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_pembina' => User::where('role', 'pembina')->count(),
            'total_ekstrakurikuler' => Ekstrakurikuler::where('is_active', true)->count(),
            'pendaftaran_bulan_ini' => Pendaftaran::whereMonth('created_at', now()->month)->count(),
        ];

        // DATA GRAFIK PENDAFTARAN BULANAN
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = Pendaftaran::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
            $chartData[] = $count;
        }

        // EKSTRAKURIKULER POPULER
        $popularEkskul = Ekstrakurikuler::withCount(['pendaftaran' => function ($q) {
            $q->where('status', 'approved');
        }])
            ->orderBy('pendaftaran_count', 'desc')
            ->limit(5)
            ->get();

        // AKTIVITAS TERBARU
        $recentActivities = Pendaftaran::with(['siswa.user', 'ekstrakurikuler'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'popularEkskul', 'recentActivities'));
    }
}
