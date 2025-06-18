@extends('layouts.siswa')

@section('title', 'Pendaftaran Saya')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Pendaftaran Ekstrakurikuler Saya</h4>
            <p class="text-muted mb-0">Pantau status pendaftaran Anda</p>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-primary">{{ $counts['all'] }}</div>
                    <div class="text-muted">Total Pendaftaran</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-warning">{{ $counts['pending'] }}</div>
                    <div class="text-muted">Menunggu</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-success">{{ $counts['approved'] }}</div>
                    <div class="text-muted">Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-danger">{{ $counts['rejected'] }}</div>
                    <div class="text-muted">Ditolak</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.pendaftaran.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Menunggu Persetujuan
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pendaftaran List -->
    <div class="row">
        @forelse($pendaftaran as $item)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $item->ekstrakurikuler->nama_ekskul }}</h5>
                                <span class="badge bg-info">{{ $item->ekstrakurikuler->kategori }}</span>
                            </div>
                            <span
                                class="badge bg-{{ $item->status == 'approved' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }} fs-6">
                                @switch($item->status)
                                    @case('pending')
                                        <i class="fas fa-clock me-1"></i>Menunggu
                                    @break

                                    @case('approved')
                                        <i class="fas fa-check me-1"></i>Disetujui
                                    @break

                                    @case('rejected')
                                        <i class="fas fa-times me-1"></i>Ditolak
                                    @break
                                @endswitch
                            </span>
                        </div>

                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="small text-muted">Pembina</div>
                                    <div class="fw-bold small">{{ $item->ekstrakurikuler->pembina->name }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Jadwal</div>
                                    <div class="fw-bold small">{{ $item->ekstrakurikuler->hari }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Skor</div>
                                    <div class="fw-bold small">{{ number_format($item->skor_rekomendasi, 1) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted mb-1">Tanggal Pendaftaran:</div>
                            <div class="fw-bold">{{ $item->created_at->format('d M Y H:i') }}</div>
                        </div>

                        @if ($item->tanggal_persetujuan)
                            <div class="mb-3">
                                <div class="small text-muted mb-1">Tanggal
                                    {{ $item->status == 'approved' ? 'Disetujui' : 'Ditolak' }}:</div>
                                <div class="fw-bold">{{ $item->tanggal_persetujuan->format('d M Y H:i') }}</div>
                            </div>
                        @endif

                        @if ($item->catatan_pembina)
                            <div class="mb-3">
                                <div class="small text-muted mb-1">Catatan Pembina:</div>
                                <div class="small bg-light p-2 rounded">{{ $item->catatan_pembina }}</div>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('siswa.pendaftaran.show', $item) }}"
                                class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>

                            @if ($item->status == 'pending')
                                <button class="btn btn-outline-danger btn-sm flex-fill"
                                    onclick="cancelPendaftaran({{ $item->id }}, '{{ $item->ekstrakurikuler->nama_ekskul }}')">
                                    <i class="fas fa-times me-1"></i>Batalkan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h5>Belum Ada Pendaftaran</h5>
                            <p class="text-muted">Anda belum mendaftar ekstrakurikuler apapun</p>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Daftar Ekstrakurikuler
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($pendaftaran->hasPages())
            <div class="d-flex justify-content-center">
                {{ $pendaftaran->links() }}
            </div>
        @endif

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Pendaftaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan pendaftaran untuk ekstrakurikuler <strong
                                id="ekskulNameCancel"></strong>?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Pendaftaran yang dibatalkan tidak dapat dikembalikan. Anda harus mendaftar ulang jika ingin
                            bergabung.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="button" class="btn btn-danger" onclick="confirmCancel()">Ya, Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            let pendaftaranToCancel = null;

            function cancelPendaftaran(id, nama) {
                pendaftaranToCancel = id;
                document.getElementById('ekskulNameCancel').textContent = nama;

                const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
                modal.show();
            }

            function confirmCancel() {
                if (!pendaftaranToCancel) return;

                fetch(`/siswa/pendaftaran/${pendaftaranToCancel}/cancel`, {
                        method: 'DELETE',
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
                            alert('Gagal: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        </script>
    @endpush
