@extends('layouts.siswa')

@section('title', 'Rekomendasi Ekstrakurikuler')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Rekomendasi Ekstrakurikuler Untuk Anda</h4>
            <p class="text-muted mb-0">Berdasarkan profil dan minat Anda</p>
        </div>
        <button class="btn btn-outline-primary" onclick="exportRekomendasi()">
            <i class="fas fa-download me-1"></i>Export PDF
        </button>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.rekomendasi.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Filter Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter Level Rekomendasi</label>
                        <select name="level" class="form-select">
                            <option value="">Semua Level</option>
                            <option value="sangat_direkomendasikan"
                                {{ request('level') == 'sangat_direkomendasikan' ? 'selected' : '' }}>
                                Sangat Direkomendasikan
                            </option>
                            <option value="direkomendasikan" {{ request('level') == 'direkomendasikan' ? 'selected' : '' }}>
                                Direkomendasikan
                            </option>
                            <option value="cukup" {{ request('level') == 'cukup' ? 'selected' : '' }}>
                                Cukup Direkomendasikan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-primary">{{ $stats['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-success">{{ $stats['sangat_direkomendasikan'] }}</div>
                <small class="text-muted">Sangat Cocok</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-info">{{ $stats['direkomendasikan'] }}</div>
                <small class="text-muted">Cocok</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-warning">{{ $stats['cukup'] }}</div>
                <small class="text-muted">Cukup</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-secondary">{{ $stats['kurang'] }}</div>
                <small class="text-muted">Kurang</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="text-center">
                <div class="h4 text-danger">{{ $stats['tidak_direkomendasikan'] }}</div>
                <small class="text-muted">Tidak Cocok</small>
            </div>
        </div>
    </div>

    <!-- Rekomendasi List -->
    <div class="row">
        @forelse($rekomendasi as $index => $item)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 {{ $index < 3 ? 'border-warning' : '' }}">
                    @if ($index === 0)
                        <div class="position-absolute top-0 start-0 p-2">
                            <span class="badge bg-warning">
                                <i class="fas fa-crown me-1"></i>TOP PICK
                            </span>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $item['ekstrakurikuler']->nama_ekskul }}</h5>
                                <span class="badge bg-info">{{ $item['ekstrakurikuler']->kategori }}</span>
                            </div>
                            <div class="text-center">
                                <div class="h4 mb-0 fw-bold"
                                    style="color: {{ $item['skor_akhir'] >= 80 ? '#28a745' : ($item['skor_akhir'] >= 70 ? '#ffc107' : '#17a2b8') }}">
                                    {{ number_format($item['skor_akhir'], 1) }}
                                </div>
                                <small class="text-muted">Skor</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small">{{ $item['rekomendasi'] }}</span>
                                <span
                                    class="badge bg-{{ $item['skor_akhir'] >= 80 ? 'success' : ($item['skor_akhir'] >= 70 ? 'warning' : 'secondary') }}">
                                    {{ $item['confidence_level'] }}
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $item['skor_akhir'] >= 80 ? 'success' : ($item['skor_akhir'] >= 70 ? 'warning' : 'info') }}"
                                    style="width: {{ $item['skor_akhir'] }}%"></div>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">{{ Str::limit($item['ekstrakurikuler']->deskripsi, 100) }}</p>

                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="small text-muted">Jadwal</div>
                                <div class="fw-bold">{{ $item['ekstrakurikuler']->hari }}</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Anggota</div>
                                <div class="fw-bold">{{ $item['ekstrakurikuler']->jumlahAnggota() }}</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Sisa Kuota</div>
                                <div class="fw-bold">{{ $item['ekstrakurikuler']->sisaKuota() }}</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('siswa.ekstrakurikuler.detail', $item['ekstrakurikuler']) }}"
                                class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>

                            @if (!in_array($item['ekstrakurikuler']->id, $sudahDaftar))
                                @if (!$item['ekstrakurikuler']->isFull())
                                    <button class="btn btn-primary btn-sm flex-fill"
                                        onclick="daftarEkskul({{ $item['ekstrakurikuler']->id }}, '{{ $item['ekstrakurikuler']->nama_ekskul }}')">
                                        <i class="fas fa-plus me-1"></i>Daftar
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm flex-fill" disabled>
                                        <i class="fas fa-times me-1"></i>Penuh
                                    </button>
                                @endif
                            @else
                                <button class="btn btn-success btn-sm flex-fill" disabled>
                                    <i class="fas fa-check me-1"></i>Terdaftar
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
                        <i class="fas fa-magic fa-4x text-muted mb-3"></i>
                        <h5>Rekomendasi Tidak Tersedia</h5>
                        <p class="text-muted">Lengkapi profil Anda untuk mendapatkan rekomendasi yang akurat</p>
                        <a href="{{ route('siswa.profil.index') }}" class="btn btn-primary">
                            <i class="fas fa-user me-1"></i>Lengkapi Profil
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Ekstrakurikuler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="registrationForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Anda akan mendaftar ekstrakurikuler: <strong id="ekskulName"></strong></p>

                        <div class="mb-3">
                            <label class="form-label">Alasan Mendaftar *</label>
                            <textarea class="form-control" name="alasan_daftar" rows="4"
                                placeholder="Jelaskan mengapa Anda tertarik dengan ekstrakurikuler ini..." required></textarea>
                            <div class="form-text">Minimal 20 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function daftarEkskul(id, nama) {
            document.getElementById('ekskulName').textContent = nama;
            document.getElementById('registrationForm').action = `/siswa/ekstrakurikuler/${id}/daftar`;

            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        }

        function exportRekomendasi() {
            fetch('{{ route('siswa.rekomendasi.export') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Laporan sedang diproses dan akan dikirim via email');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    </script>
@endpush
