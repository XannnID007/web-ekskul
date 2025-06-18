@extends('layouts.siswa')

@section('title', 'Jelajahi Ekstrakurikuler')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Jelajahi Ekstrakurikuler</h4>
            <p class="text-muted mb-0">Temukan ekstrakurikuler yang sesuai dengan minat Anda</p>
        </div>
        <a href="{{ route('siswa.rekomendasi.index') }}" class="btn btn-primary">
            <i class="fas fa-magic me-1"></i>Lihat Rekomendasi
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.ekstrakurikuler.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Cari Ekstrakurikuler</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Nama ekstrakurikuler...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-select">
                            <option value="">Semua</option>
                            @foreach ($hariList as $hari)
                                <option value="{{ $hari }}" {{ request('hari') == $hari ? 'selected' : '' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ketersediaan</label>
                        <select name="tersedia" class="form-select">
                            <option value="">Semua</option>
                            <option value="ya" {{ request('tersedia') == 'ya' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak" {{ request('tersedia') == 'tidak' ? 'selected' : '' }}>Penuh</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="">Default</option>
                            <option value="rekomendasi" {{ request('sort') == 'rekomendasi' ? 'selected' : '' }}>
                                Rekomendasi
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ekstrakurikuler Grid -->
    <div class="row">
        @forelse($ekstrakurikuler as $ekskul)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 {{ in_array($ekskul->id, $sudahDaftar) ? 'border-success' : '' }}">
                    @if (in_array($ekskul->id, $sudahDaftar))
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Terdaftar
                            </span>
                        </div>
                    @endif

                    @if ($ekskul->isFull())
                        <div class="position-absolute top-0 start-0 p-2">
                            <span class="badge bg-danger">
                                <i class="fas fa-users me-1"></i>Penuh
                            </span>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $ekskul->nama_ekskul }}</h5>
                                <span class="badge bg-info">{{ $ekskul->kategori }}</span>
                            </div>
                            @if (isset($rekomendasiScores[$ekskul->id]))
                                <div class="text-center">
                                    <div class="h6 mb-0 fw-bold"
                                        style="color: {{ $rekomendasiScores[$ekskul->id] >= 80 ? '#28a745' : ($rekomendasiScores[$ekskul->id] >= 70 ? '#ffc107' : '#17a2b8') }}">
                                        {{ number_format($rekomendasiScores[$ekskul->id], 0) }}
                                    </div>
                                    <small class="text-muted">Match</small>
                                </div>
                            @endif
                        </div>

                        <p class="text-muted small mb-3">{{ Str::limit($ekskul->deskripsi, 100) }}</p>

                        <div class="mb-3">
                            <div class="row text-center small">
                                <div class="col-4">
                                    <div class="text-muted">Pembina</div>
                                    <div class="fw-bold">{{ $ekskul->pembina->name }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Jadwal</div>
                                    <div class="fw-bold">{{ $ekskul->hari }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Waktu</div>
                                    <div class="fw-bold">{{ date('H:i', strtotime($ekskul->jam_mulai)) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Kapasitas</small>
                                <small
                                    class="text-muted">{{ $ekskul->pendaftaran_count }}/{{ $ekskul->kapasitas_maksimal }}</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $percentage =
                                        $ekskul->kapasitas_maksimal > 0
                                            ? ($ekskul->pendaftaran_count / $ekskul->kapasitas_maksimal) * 100
                                            : 0;
                                @endphp
                                <div class="progress-bar bg-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}"
                                    style="width: {{ min(100, $percentage) }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('siswa.ekstrakurikuler.detail', $ekskul) }}"
                                class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>

                            @if (!in_array($ekskul->id, $sudahDaftar))
                                @if (!$ekskul->isFull())
                                    <button class="btn btn-primary btn-sm flex-fill"
                                        onclick="daftarEkskul({{ $ekskul->id }}, '{{ $ekskul->nama_ekskul }}')">
                                        <i class="fas fa-plus me-1"></i>Daftar
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm flex-fill" disabled>
                                        <i class="fas fa-times me-1"></i>Penuh
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('siswa.pendaftaran.index') }}" class="btn btn-success btn-sm flex-fill">
                                    <i class="fas fa-check me-1"></i>Terdaftar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h5>Tidak Ada Ekstrakurikuler</h5>
                        <p class="text-muted">Tidak ada ekstrakurikuler yang sesuai dengan filter Anda</p>
                        <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-1"></i>Reset Filter
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($ekstrakurikuler->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $ekstrakurikuler->links() }}
        </div>
    @endif

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

        // Auto-submit on filter change for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll(
                'select[name="kategori"], select[name="hari"], select[name="tersedia"], select[name="sort"]');

            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
@endpush
