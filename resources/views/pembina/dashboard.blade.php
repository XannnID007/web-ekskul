@extends('layouts.pembina')

@section('title', 'Dashboard Pembina')

@section('content')
    <!-- WELCOME SECTION -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
        <div class="card-body text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">Selamat Datang, {{ auth()->user()->name }}! 👨‍🏫</h3>
                    <p class="mb-0 opacity-90">Kelola kegiatan ekstrakurikuler Anda dengan efektif</p>
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

    <!-- STATISTICS -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ $myEkskul->count() }}</div>
                        <div>Ekstrakurikuler Dibina</div>
                    </div>
                    <i class="fas fa-star fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ $myEkskul->sum('pendaftaran_count') }}</div>
                        <div>Total Anggota</div>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ $pendingApprovals->count() }}</div>
                        <div>Perlu Persetujuan</div>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- EKSTRAKURIKULER YANG DIBINA -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Ekstrakurikuler Saya</h5>
                </div>
                <div class="card-body">
                    @forelse($myEkskul as $ekskul)
                        <div class="d-flex align-items-center p-3 border rounded mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $ekskul->nama_ekskul }}</h6>
                                <div class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>{{ $ekskul->hari }}
                                    <i class="fas fa-clock ms-2 me-1"></i>
                                    {{ date('H:i', strtotime($ekskul->jam_mulai)) }} -
                                    {{ date('H:i', strtotime($ekskul->jam_selesai)) }}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $ekskul->tempat }}
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-center">
                                <div class="h5 mb-1 text-primary">{{ $ekskul->pendaftaran_count }}</div>
                                <small class="text-muted">Anggota</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h6>Belum Ada Ekstrakurikuler</h6>
                            <p class="text-muted">Hubungi admin untuk mendapatkan ekstrakurikuler</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- PENDAFTARAN PENDING -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Perlu Persetujuan</h5>
                    <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @forelse($pendingApprovals as $pendaftaran)
                        <div class="d-flex align-items-center p-3 border rounded mb-3">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ $pendaftaran->siswa->user->name }}&background=059669&color=ffffff&size=40"
                                    class="rounded-circle" width="40" height="40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $pendaftaran->siswa->user->name }}</h6>
                                <small class="text-muted">{{ $pendaftaran->ekstrakurikuler->nama_ekskul }}</small>
                                <div class="text-muted small">{{ $pendaftaran->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="approvePendaftaran({{ $pendaftaran->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="rejectPendaftaran({{ $pendaftaran->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6>Semua Sudah Diproses</h6>
                            <p class="text-muted small">Tidak ada pendaftaran yang perlu disetujui</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- WEEKLY ATTENDANCE SUMMARY -->
    @if ($weeklyStats['total_anggota'] > 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Ringkasan Kehadiran Minggu Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="h4 text-primary">{{ $weeklyStats['total_anggota'] }}</div>
                                <div class="text-muted">Total Anggota</div>
                            </div>
                            <div class="col-md-4">
                                <div class="h4 text-success">{{ $weeklyStats['hadir_minggu_ini'] }}</div>
                                <div class="text-muted">Hadir Minggu Ini</div>
                            </div>
                            <div class="col-md-4">
                                <div class="h4 text-info">{{ $weeklyStats['persentase'] }}%</div>
                                <div class="text-muted">Persentase Kehadiran</div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $weeklyStats['persentase'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function approvePendaftaran(id) {
            if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')) {
                fetch(`/pembina/pendaftaran/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    });
            }
        }

        function rejectPendaftaran(id) {
            const alasan = prompt('Masukkan alasan penolakan:');
            if (alasan) {
                fetch(`/pembina/pendaftaran/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            catatan: alasan
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    });
            }
        }
    </script>
@endpush
