@extends('layouts.admin')

@section('title', 'Monitor Kehadiran')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Monitor Kehadiran</h1>
                <p class="text-muted mb-0">Pantau tingkat kehadiran per ekstrakurikuler</p>
            </div>
        </div>

        <!-- Daily Attendance Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line me-2"></i>Tren Kehadiran 7 Hari Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyAttendanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Stats by Ekstrakurikuler -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Statistik Kehadiran per Ekstrakurikuler</h5>
                    </div>
                    <div class="card-body">
                        @if ($kehadiranStats->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ekstrakurikuler</th>
                                            <th class="text-center">Total Pertemuan</th>
                                            <th class="text-center">Total Hadir</th>
                                            <th class="text-center">Persentase Kehadiran</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kehadiranStats as $stat)
                                            <tr>
                                                <td class="fw-bold">{{ $stat->nama_ekskul }}</td>
                                                <td class="text-center">{{ $stat->total_pertemuan }}</td>
                                                <td class="text-center">{{ $stat->total_hadir }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <div class="progress me-2" style="width: 100px; height: 8px;">
                                                            <div class="progress-bar bg-{{ $stat->persentase_kehadiran >= 80 ? 'success' : ($stat->persentase_kehadiran >= 60 ? 'warning' : 'danger') }}"
                                                                style="width: {{ $stat->persentase_kehadiran }}%"></div>
                                                        </div>
                                                        <span
                                                            class="fw-bold">{{ number_format($stat->persentase_kehadiran, 1) }}%</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if ($stat->persentase_kehadiran >= 80)
                                                        <span class="badge bg-success">Sangat Baik</span>
                                                    @elseif($stat->persentase_kehadiran >= 60)
                                                        <span class="badge bg-warning">Baik</span>
                                                    @else
                                                        <span class="badge bg-danger">Perlu Perhatian</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Data Kehadiran</h5>
                                <p class="text-muted">Data kehadiran akan muncul setelah pembina menginput kehadiran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Daily Attendance Chart
        const dailyData = @json($dailyAttendance);
        const ctx = document.getElementById('dailyAttendanceChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    });
                }),
                datasets: [{
                    label: 'Total Kehadiran',
                    data: dailyData.map(item => item.total),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Hadir',
                    data: dailyData.map(item => item.hadir),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
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
