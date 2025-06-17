@extends('layouts.admin')

@section('title', 'Monitor Aktivitas Sistem')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Monitor Aktivitas Sistem</h1>
                <p class="text-muted mb-0">Pantau aktivitas pengguna dan sistem secara real-time</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="refreshPage()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card primary h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $stats['total_pendaftaran_hari_ini'] }}</div>
                            <div class="label">Pendaftaran Hari Ini</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $stats['total_persetujuan_hari_ini'] }}</div>
                            <div class="label">Persetujuan Hari Ini</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card warning h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $stats['pending_approval'] }}</div>
                            <div class="label">Menunggu Persetujuan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card info h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $stats['active_users_today'] }}</div>
                            <div class="label">User Aktif Hari Ini</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if ($activities->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Siswa</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Status</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activities as $activity)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $activity->created_at->format('H:i') }}</div>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->format('d M Y') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $activity->siswa_name }}&background=1e40af&color=ffffff&size=32"
                                                            class="rounded-circle me-2" width="32" height="32">
                                                        <div>
                                                            <div class="fw-bold">{{ $activity->siswa_name }}</div>
                                                            <small class="text-muted">{{ $activity->kelas }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $activity->nama_ekskul }}</td>
                                                <td>
                                                    @if ($activity->status == 'pending')
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif($activity->status == 'approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($activity->status == 'pending')
                                                        <span class="text-muted">Mendaftar</span>
                                                    @elseif($activity->status == 'approved')
                                                        <span class="text-success">Bergabung</span>
                                                    @else
                                                        <span class="text-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $activities->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Aktivitas</h5>
                                <p class="text-muted">Aktivitas sistem akan muncul di sini</p>
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
        function refreshPage() {
            location.reload();
        }

        // Auto refresh every 30 seconds
        setInterval(refreshPage, 30000);
    </script>
@endpush
