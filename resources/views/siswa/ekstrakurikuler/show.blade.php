@extends('layouts.siswa')

@section('title', 'Detail Ekstrakurikuler')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('siswa.ekstrakurikuler.index') }}">Ekstrakurikuler</a></li>
            <li class="breadcrumb-item active">{{ $ekstrakurikuler->nama_ekskul }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Header Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3 class="mb-1">{{ $ekstrakurikuler->nama_ekskul }}</h3>
                            <span class="badge bg-info fs-6">{{ $ekstrakurikuler->kategori }}</span>
                            @if ($ekstrakurikuler->isFull())
                                <span class="badge bg-danger fs-6 ms-2">
                                    <i class="fas fa-users me-1"></i>Penuh
                                </span>
                            @endif
                        </div>
                        @if ($rekomendasiData)
                            <div class="text-center">
                                <div class="h2 mb-0 fw-bold"
                                    style="color: {{ $rekomendasiData['skor_akhir'] >= 80 ? '#28a745' : ($rekomendasiData['skor_akhir'] >= 70 ? '#ffc107' : '#17a2b8') }}">
                                    {{ number_format($rekomendasiData['skor_akhir'], 1) }}
                                </div>
                                <small class="text-muted">Match Score</small>
                                <div
                                    class="badge bg-{{ $rekomendasiData['skor_akhir'] >= 80 ? 'success' : ($rekomendasiData['skor_akhir'] >= 70 ? 'warning' : 'info') }} mt-1">
                                    {{ $rekomendasiData['rekomendasi'] }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Info -->
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="h5 text-primary">{{ $ekstrakurikuler->pendaftaran->count() }}</div>
                            <small class="text-muted">Anggota Aktif</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 text-success">{{ $ekstrakurikuler->kapasitas_maksimal }}</div>
                            <small class="text-muted">Kapasitas</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 text-info">{{ $ekstrakurikuler->sisaKuota() }}</div>
                            <small class="text-muted">Sisa Kuota</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 text-warning">{{ $ekstrakurikuler->hari }}</div>
                            <small class="text-muted">Hari Kegiatan</small>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">Kapasitas Terisi</span>
                            <span
                                class="small">{{ $ekstrakurikuler->pendaftaran->count() }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $percentage =
                                    $ekstrakurikuler->kapasitas_maksimal > 0
                                        ? ($ekstrakurikuler->pendaftaran->count() /
                                                $ekstrakurikuler->kapasitas_maksimal) *
                                            100
                                        : 0;
                            @endphp
                            <div class="progress-bar bg-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}"
                                style="width: {{ min(100, $percentage) }}%"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        @if (!$pendaftaran)
                            @if (!$ekstrakurikuler->isFull())
                                <button class="btn btn-primary" onclick="daftarEkskul()">
                                    <i class="fas fa-plus me-1"></i>Daftar Sekarang
                                </button>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-times me-1"></i>Kapasitas Penuh
                                </button>
                            @endif
                        @else
                            <span class="btn btn-success" disabled>
                                <i class="fas fa-check me-1"></i>
                                Status: {{ ucfirst($pendaftaran->status) }}
                            </span>
                            @if ($pendaftaran->status == 'pending')
                                <button class="btn btn-outline-danger" onclick="cancelPendaftaran()">
                                    <i class="fas fa-times me-1"></i>Batalkan
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('siswa.rekomendasi.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-magic me-1"></i>Lihat Rekomendasi Lain
                        </a>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Deskripsi</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ekstrakurikuler->deskripsi }}</p>
                </div>
            </div>

            <!-- Schedule & Location -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Jadwal & Lokasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Hari:</td>
                                    <td>{{ $ekstrakurikuler->hari }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Waktu:</td>
                                    <td>{{ date('H:i', strtotime($ekstrakurikuler->jam_mulai)) }} -
                                        {{ date('H:i', strtotime($ekstrakurikuler->jam_selesai)) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Lokasi:</td>
                                    <td>{{ $ekstrakurikuler->tempat }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <h6>Informasi Tambahan</h6>
                                <ul class="mb-0">
                                    <li>Durasi:
                                        {{ \Carbon\Carbon::parse($ekstrakurikuler->jam_mulai)->diffInMinutes(\Carbon\Carbon::parse($ekstrakurikuler->jam_selesai)) }}
                                        menit</li>
                                    <li>Status: {{ $ekstrakurikuler->is_active ? 'Aktif' : 'Tidak Aktif' }}</li>
                                    <li>Pembina: {{ $ekstrakurikuler->pembina->name }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($rekomendasiData && isset($rekomendasiData['detail_skor']))
                <!-- Recommendation Analysis -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Analisis Rekomendasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Berdasarkan profil Anda, ekstrakurikuler ini memiliki tingkat kesesuaian
                            <strong>{{ $rekomendasiData['rekomendasi'] }}</strong> dengan skor
                            <strong>{{ number_format($rekomendasiData['skor_akhir'], 1) }}</strong>.
                        </p>

                        @foreach ($rekomendasiData['detail_skor'] as $detail)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-bold">{{ $detail['kriteria'] }}</span>
                                    <small class="text-muted">({{ $detail['tipe'] }})</small>
                                </div>
                                <div class="text-end">
                                    <div class="progress" style="width: 100px; height: 6px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ $detail['nilai_normal'] * 100 }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($detail['nilai_asli'], 1) }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pembina Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-user-tie me-2"></i>Pembina</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ $ekstrakurikuler->pembina->name }}&background=059669&color=ffffff&size=80"
                        class="rounded-circle mb-3" width="80" height="80">
                    <h6>{{ $ekstrakurikuler->pembina->name }}</h6>
                    @if ($ekstrakurikuler->pembina->phone)
                        <p class="text-muted small">{{ $ekstrakurikuler->pembina->phone }}</p>
                    @endif
                    <p class="text-muted small">{{ $ekstrakurikuler->pembina->email }}</p>
                </div>
            </div>

            <!-- Members -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-users me-2"></i>Anggota</h5>
                    <span class="badge bg-primary">{{ $ekstrakurikuler->pendaftaran->count() }}</span>
                </div>
                <div class="card-body">
                    @if ($ekstrakurikuler->pendaftaran->count() > 0)
                        @foreach ($ekstrakurikuler->pendaftaran->take(5) as $member)
                            <div class="d-flex align-items-center mb-2">
                                <img src="https://ui-avatars.com/api/?name={{ $member->siswa->user->name }}&background=4f46e5&color=ffffff&size=32"
                                    class="rounded-circle me-2" width="32" height="32">
                                <div>
                                    <div class="small fw-bold">{{ $member->siswa->user->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $member->siswa->kelas }}</div>
                                </div>
                            </div>
                        @endforeach

                        @if ($ekstrakurikuler->pendaftaran->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    +{{ $ekstrakurikuler->pendaftaran->count() - 5 }} anggota lainnya
                                </small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center mb-0">Belum ada anggota</p>
                    @endif
                </div>
            </div>

            <!-- Similar Ekstrakurikuler -->
            @if ($similar->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-star me-2"></i>Ekstrakurikuler Serupa</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($similar as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->nama_ekskul }}</h6>
                                    <small class="text-muted">
                                        {{ $item->hari }} • {{ $item->pendaftaran_count }} anggota
                                    </small>
                                </div>
                                <a href="{{ route('siswa.ekstrakurikuler.detail', $item) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar {{ $ekstrakurikuler->nama_ekskul }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('siswa.pendaftaran.daftar', $ekstrakurikuler) }}">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Registration Modal -->
    @if ($pendaftaran && $pendaftaran->status == 'pending')
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Pendaftaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan pendaftaran untuk ekstrakurikuler
                            <strong>{{ $ekstrakurikuler->nama_ekskul }}</strong>?
                        </p>
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
    @endif
@endsection

@push('scripts')
    <script>
        function daftarEkskul() {
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        }

        @if ($pendaftaran && $pendaftaran->status == 'pending')
            function cancelPendaftaran() {
                const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
                modal.show();
            }

            function confirmCancel() {
                fetch(`/siswa/pendaftaran/{{ $pendaftaran->id }}/cancel`, {
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
        @endif

        // Character counter for textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('textarea[name="alasan_daftar"]');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    const minLength = 20;
                    const currentLength = this.value.length;

                    let counter = document.getElementById('char-counter');
                    if (!counter) {
                        counter = document.createElement('div');
                        counter.id = 'char-counter';
                        counter.className = 'form-text';
                        this.parentNode.appendChild(counter);
                    }

                    if (currentLength < minLength) {
                        counter.textContent =
                            `${currentLength}/${minLength} karakter (minimal ${minLength - currentLength} lagi)`;
                        counter.className = 'form-text text-warning';
                    } else {
                        counter.textContent = `${currentLength} karakter`;
                        counter.className = 'form-text text-success';
                    }
                });
            }
        });
    </script>
@endpush
