@extends('layouts.pembina')

@section('title', 'Dashboard Pembina')

@section('content')
    <!-- Welcome Section -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
        <div class="card-body text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">Selamat Datang, {{ auth()->user()->name }}! 👨‍🏫</h3>
                    <p class="mb-0 opacity-90">Kelola ekstrakurikuler Anda dengan mudah dan efisien</p>
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

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="h3">{{ $myEkskul->count() }}</div>
                        <div>Ekstrakurikuler Dibina</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="h3">{{ $myEkskul->sum('pendaftaran_count') }}</div>
                        <div>Total Anggota</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="h3">{{ $pendingApprovals->count() }}</div>
                        <div>Perlu Persetujuan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="h3">{{ $weeklyStats['persentase'] }}%</div>
                        <div>Kehadiran Minggu Ini</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- My Ekstrakurikuler -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-star me-2"></i>Ekstrakurikuler Yang Saya Bina</h5>
                    <a href="{{ route('pembina.anggota.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if ($myEkskul->count() > 0)
                        <div class="row">
                            @foreach ($myEkskul as $ekskul)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0 fw-bold">{{ $ekskul->nama_ekskul }}</h6>
                                                <span class="badge bg-info">{{ $ekskul->kategori }}</span>
                                            </div>

                                            <p class="text-muted small mb-3">{{ Str::limit($ekskul->deskripsi, 80) }}</p>

                                            <div class="row text-center">
                                                <div class="col-6 border-end">
                                                    <div class="fw-bold text-primary">{{ $ekskul->pendaftaran_count }}
                                                    </div>
                                                    <small class="text-muted">Anggota</small>
                                                </div>
                                                <div class="col-6">
                                                    <div class="fw-bold text-success">{{ $ekskul->kapasitas_maksimal }}
                                                    </div>
                                                    <small class="text-muted">Kapasitas</small>
                                                </div>
                                            </div>

                                            <div class="progress mt-2" style="height: 6px;">
                                                @php
                                                    $percentage =
                                                        $ekskul->kapasitas_maksimal > 0
                                                            ? ($ekskul->pendaftaran_count /
                                                                    $ekskul->kapasitas_maksimal) *
                                                                100
                                                            : 0;
                                                @endphp
                                                <div class="progress-bar bg-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}"
                                                    style="width: {{ min(100, $percentage) }}%"></div>
                                            </div>

                                            <div class="mt-3 d-flex gap-1">
                                                <a href="{{ route('pembina.anggota.index', ['ekstrakurikuler_id' => $ekskul->id]) }}"
                                                    class="btn btn-sm btn-outline-primary flex-fill">
                                                    <i class="fas fa-users"></i> Anggota
                                                </a>
                                                <a href="{{ route('pembina.kehadiran.index', ['ekstrakurikuler_id' => $ekskul->id]) }}"
                                                    class="btn btn-sm btn-outline-success flex-fill">
                                                    <i class="fas fa-calendar-check"></i> Kehadiran
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h6>Belum Ada Ekstrakurikuler</h6>
                            <p class="text-muted">Anda belum ditugaskan untuk membina ekstrakurikuler manapun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-clock me-2"></i>Perlu Persetujuan</h5>
                    <a href="{{ route('pembina.pendaftaran.index', ['status' => 'pending']) }}"
                        class="btn btn-sm btn-outline-warning">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if ($pendingApprovals->count() > 0)
                        @foreach ($pendingApprovals as $pendaftaran)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <img src="https://ui-avatars.com/api/?name={{ $pendaftaran->siswa->user->name }}&background=059669&color=ffffff&size=40"
                                    class="rounded-circle me-3" width="40" height="40">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $pendaftaran->siswa->user->name }}</h6>
                                    <p class="text-muted mb-1 small">{{ $pendaftaran->ekstrakurikuler->nama_ekskul }}</p>
                                    <small class="text-muted">{{ $pendaftaran->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item text-success" href="#"
                                                onclick="approvePendaftaran({{ $pendaftaran->id }})">
                                                <i class="fas fa-check me-2"></i>Setujui
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                onclick="rejectPendaftaran({{ $pendaftaran->id }})">
                                                <i class="fas fa-times me-2"></i>Tolak
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('pembina.pendaftaran.show', $pendaftaran) }}">
                                                <i class="fas fa-eye me-2"></i>Detail
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6>Semua Terkini!</h6>
                            <p class="text-muted">Tidak ada pendaftaran yang perlu disetujui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line me-2"></i>Statistik Kehadiran Minggu Ini</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 text-primary">{{ $weeklyStats['total_anggota'] }}</div>
                                <small class="text-muted">Total Anggota</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 text-success">{{ $weeklyStats['hadir_minggu_ini'] }}</div>
                                <small class="text-muted">Total Kehadiran</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 text-warning">{{ $weeklyStats['persentase'] }}%</div>
                                <small class="text-muted">Persentase Kehadiran</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 text-info">{{ now()->format('W') }}</div>
                            <small class="text-muted">Minggu ke-{{ now()->format('W') }}</small>
                        </div>
                    </div>

                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-{{ $weeklyStats['persentase'] >= 80 ? 'success' : ($weeklyStats['persentase'] >= 60 ? 'warning' : 'danger') }}"
                            style="width: {{ $weeklyStats['persentase'] }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">0%</small>
                        <small class="text-muted">Target: 80%</small>
                        <small class="text-muted">100%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Modal -->
    <div class="modal fade" id="quickActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickActionTitle">Aksi Cepat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickActionForm">
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Berikan catatan (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentAction = null;
        let currentPendaftaranId = null;

        function approvePendaftaran(pendaftaranId) {
            currentAction = 'approve';
            currentPendaftaranId = pendaftaranId;

            document.getElementById('quickActionTitle').textContent = 'Setujui Pendaftaran';
            document.getElementById('confirmAction').textContent = 'Setujui';
            document.getElementById('confirmAction').className = 'btn btn-success';

            const modal = new bootstrap.Modal(document.getElementById('quickActionModal'));
            modal.show();
        }

        function rejectPendaftaran(pendaftaranId) {
            currentAction = 'reject';
            currentPendaftaranId = pendaftaranId;

            document.getElementById('quickActionTitle').textContent = 'Tolak Pendaftaran';
            document.getElementById('confirmAction').textContent = 'Tolak';
            document.getElementById('confirmAction').className = 'btn btn-danger';

            const modal = new bootstrap.Modal(document.getElementById('quickActionModal'));
            modal.show();
        }

        document.getElementById('confirmAction').addEventListener('click', function() {
            if (!currentAction || !currentPendaftaranId) return;

            const catatan = document.querySelector('textarea[name="catatan"]').value;
            const url = `/pembina/pendaftaran/${currentPendaftaranId}/${currentAction}`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        catatan: catatan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        });

        // Reset form when modal is hidden
        document.getElementById('quickActionModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('quickActionForm').reset();
            currentAction = null;
            currentPendaftaranId = null;
        });
    </script>
@endpush
