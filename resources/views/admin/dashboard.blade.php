@extends('layouts.admin')

@section('title', 'Dashboard Sistem')
@section('page-title', 'Dashboard Sistem')

@section('content')
    <!-- WELCOME SECTION -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">Selamat Datang, Admin! 👨‍💼</h3>
                            <p class="mb-0 opacity-90">Kelola sistem ekstrakurikuler dengan mudah dan efisien</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="text-white opacity-75">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ now()->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card primary h-100">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="number">{{ $stats['total_siswa'] }}</div>
                        <div class="label">Total Siswa</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card success h-100">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="number">{{ $stats['total_ekstrakurikuler'] }}</div>
                        <div class="label">Ekstrakurikuler Aktif</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card info h-100">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="number">{{ $stats['total_pembina'] }}</div>
                        <div class="label">Total Pembina</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card warning h-100">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="number">{{ $stats['pendaftaran_bulan_ini'] }}</div>
                        <div class="label">Pendaftaran Bulan Ini</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS AND ANALYTICS -->
    <div class="row mb-4">
        <!-- Monthly Registration Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Tren Pendaftaran Bulanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Popular Ekstrakurikuler -->
        <div class="col-xl-4 col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Ekstrakurikuler Populer</h5>
                </div>
                <div class="card-body">
                    @forelse($popularEkskul as $ekskul)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $ekskul->nama_ekskul }}</h6>
                                <small class="text-muted">{{ $ekskul->pendaftaran_count }} anggota</small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary rounded-pill">{{ $ekskul->pendaftaran_count }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ACTIVITIES -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Aktivitas Terbaru</h5>
                    <a href="" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentActivities as $activity)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ $activity->siswa->user->name }}&background=1e40af&color=ffffff&size=40"
                                    class="rounded-circle" width="40" height="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $activity->siswa->user->name }}</h6>
                                <p class="text-muted mb-1">Mendaftar {{ $activity->ekstrakurikuler->nama_ekskul }}</p>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($activity->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($activity->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Monthly Chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendaftaran',
                    data: @json($chartData),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
