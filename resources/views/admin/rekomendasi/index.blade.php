@extends('layouts.admin')

@section('title', 'Sistem Rekomendasi Ekstrakurikuler')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Sistem Rekomendasi</h1>
                <p class="text-muted mb-0">Menggunakan Algoritma Weighted Scoring untuk memberikan rekomendasi
                    ekstrakurikuler yang sesuai</p>
            </div>
            <div>
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-sliders-h me-1"></i>
                    Kelola Kriteria
                </a>
                <a href="{{ route('admin.penilaian.index') }}" class="btn btn-primary">
                    <i class="fas fa-calculator me-1"></i>
                    Kelola Penilaian
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-filter me-2"></i>Filter Rekomendasi</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.rekomendasi.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Pilih Siswa</label>
                                    <select name="siswa_id" class="form-select" required>
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach ($siswa as $s)
                                            <option value="{{ $s->id }}"
                                                {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->user->name }} - {{ $s->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-magic me-1"></i>
                                        Generate Rekomendasi
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    @if (request('siswa_id'))
                                        <a href="{{ route('admin.rekomendasi.export', request('siswa_id')) }}"
                                            class="btn btn-success w-100">
                                            <i class="fas fa-download me-1"></i>
                                            Export PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($rekomendasi))
            <!-- Selected Student Info -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="fas fa-user me-2"></i>Profil Siswa</h5>
                        </div>
                        <div class="card-body">
                            @php $selectedSiswa = App\Models\Siswa::find(request('siswa_id')); @endphp
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img src="https://ui-avatars.com/api/?name={{ $selectedSiswa->user->name }}&background=667eea&color=ffffff&size=100"
                                        class="rounded-circle mb-3" width="100" height="100">
                                </div>
                                <div class="col-md-5">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold">Nama:</td>
                                            <td>{{ $selectedSiswa->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">NISN:</td>
                                            <td>{{ $selectedSiswa->nisn }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Kelas:</td>
                                            <td>{{ $selectedSiswa->kelas }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Jenis Kelamin:</td>
                                            <td>{{ $selectedSiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-5">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold">Nilai Akademik:</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $selectedSiswa->nilai_akademik >= 80 ? 'success' : ($selectedSiswa->nilai_akademik >= 70 ? 'warning' : 'danger') }} fs-6">
                                                    {{ $selectedSiswa->nilai_akademik }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Minat:</td>
                                            <td>
                                                @if ($selectedSiswa->minat)
                                                    @foreach ($selectedSiswa->minat as $minat)
                                                        <span class="badge bg-info me-1">{{ $minat }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Belum ada data</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendation Results -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-star me-2"></i>Hasil Rekomendasi Ekstrakurikuler</h5>
                            <div class="text-muted small">
                                Berdasarkan Algoritma Weighted Scoring
                            </div>
                        </div>
                        <div class="card-body">
                            @if (count($rekomendasi) > 0)
                                <div class="row">
                                    @foreach ($rekomendasi as $index => $item)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div
                                                class="card h-100 border-0 shadow-sm position-relative 
                                        {{ $index == 0 ? 'border-warning' : ($index < 3 ? 'border-success' : 'border-secondary') }}">

                                                <!-- Ranking Badge -->
                                                @if ($index < 3)
                                                    <div class="position-absolute top-0 start-0 translate-middle">
                                                        <span
                                                            class="badge rounded-pill 
                                                {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-success' : 'bg-info') }} 
                                                fs-6 px-3 py-2">
                                                            #{{ $index + 1 }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="card-body">
                                                    <!-- Header -->
                                                    <div class="text-center mb-3">
                                                        <h5 class="card-title text-primary fw-bold">
                                                            {{ $item['ekstrakurikuler']->nama_ekskul }}
                                                        </h5>
                                                        <p class="text-muted small mb-2">
                                                            {{ $item['ekstrakurikuler']->kategori }}</p>

                                                        <!-- Score Circle -->
                                                        <div class="mx-auto mb-3" style="width: 80px; height: 80px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-5"
                                                                style="width: 100%; height: 100%; background: linear-gradient(135deg, 
                                                        {{ $item['skor_akhir'] >= 80
                                                            ? '#28a745, #20c997'
                                                            : ($item['skor_akhir'] >= 70
                                                                ? '#ffc107, #fd7e14'
                                                                : ($item['skor_akhir'] >= 60
                                                                    ? '#17a2b8, #6f42c1'
                                                                    : '#dc3545, #e83e8c')) }});">
                                                                {{ number_format($item['skor_akhir'], 1) }}
                                                            </div>
                                                        </div>

                                                        <!-- Recommendation Label -->
                                                        <span
                                                            class="badge 
                                                    {{ $item['skor_akhir'] >= 80
                                                        ? 'bg-success'
                                                        : ($item['skor_akhir'] >= 70
                                                            ? 'bg-warning'
                                                            : ($item['skor_akhir'] >= 60
                                                                ? 'bg-info'
                                                                : 'bg-danger')) }} 
                                                    fs-6 px-3 py-2">
                                                            {{ $item['rekomendasi'] }}
                                                        </span>
                                                    </div>

                                                    <!-- Ekstrakurikuler Info -->
                                                    <div class="mb-3">
                                                        <div class="row text-center">
                                                            <div class="col-6 border-end">
                                                                <div class="text-muted small">Hari</div>
                                                                <div class="fw-bold">{{ $item['ekstrakurikuler']->hari }}
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="text-muted small">Waktu</div>
                                                                <div class="fw-bold">
                                                                    {{ date('H:i', strtotime($item['ekstrakurikuler']->jam_mulai)) }}
                                                                    -
                                                                    {{ date('H:i', strtotime($item['ekstrakurikuler']->jam_selesai)) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Detail Score Button -->
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailModal{{ $item['ekstrakurikuler']->id }}">
                                                            <i class="fas fa-eye me-1"></i>
                                                            Lihat Detail Perhitungan
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Footer with capacity -->
                                                <div class="card-footer bg-light text-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-users me-1"></i>
                                                        Kapasitas:
                                                        {{ $item['ekstrakurikuler']->jumlahAnggota() }}/{{ $item['ekstrakurikuler']->kapasitas_maksimal }}
                                                        @if ($item['ekstrakurikuler']->sisaKuota() <= 5 && $item['ekstrakurikuler']->sisaKuota() > 0)
                                                            <span class="badge bg-warning ms-1">Hampir Penuh</span>
                                                        @elseif($item['ekstrakurikuler']->isFull())
                                                            <span class="badge bg-danger ms-1">Penuh</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detail Modal for each ekstrakurikuler -->
                                        <div class="modal fade" id="detailModal{{ $item['ekstrakurikuler']->id }}"
                                            tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Detail Perhitungan: {{ $item['ekstrakurikuler']->nama_ekskul }}
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Algoritma Explanation -->
                                                        <div class="alert alert-info">
                                                            <h6 class="alert-heading">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                Algoritma Weighted Scoring
                                                            </h6>
                                                            <p class="mb-0">
                                                                Perhitungan menggunakan rumus: <strong>Skor = Σ(Nilai ×
                                                                    Bobot) / Σ(Bobot) × 100</strong>
                                                                <br>
                                                                Nilai dinormalisasi ke skala 0-1, kemudian dikalikan dengan
                                                                bobot masing-masing kriteria.
                                                            </p>
                                                        </div>

                                                        <!-- Calculation Table -->
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="table-primary">
                                                                    <tr>
                                                                        <th>Kriteria</th>
                                                                        <th class="text-center">Nilai Asli</th>
                                                                        <th class="text-center">Nilai Normal</th>
                                                                        <th class="text-center">Bobot</th>
                                                                        <th class="text-center">Skor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $totalSkor = 0;
                                                                        $totalBobot = 0;
                                                                    @endphp
                                                                    @foreach ($item['detail_skor'] as $detail)
                                                                        @php
                                                                            $totalSkor += $detail['skor'];
                                                                            $totalBobot += $detail['bobot'];
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="fw-bold">{{ $detail['kriteria'] }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ $detail['nilai_asli'] }}</td>
                                                                            <td class="text-center">
                                                                                {{ number_format($detail['nilai_normal'], 3) }}
                                                                            </td>
                                                                            <td class="text-center">{{ $detail['bobot'] }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ number_format($detail['skor'], 3) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="table-dark">
                                                                    <tr>
                                                                        <th>Total</th>
                                                                        <th class="text-center">-</th>
                                                                        <th class="text-center">-</th>
                                                                        <th class="text-center">{{ $totalBobot }}</th>
                                                                        <th class="text-center">
                                                                            {{ number_format($totalSkor, 3) }}</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="4">Skor Akhir (Total Skor / Total
                                                                            Bobot × 100)</th>
                                                                        <th class="text-center text-warning">
                                                                            {{ number_format($item['skor_akhir'], 2) }}
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>

                                                        <!-- Ekstrakurikuler Details -->
                                                        <div class="row mt-4">
                                                            <div class="col-md-6">
                                                                <h6>Informasi Ekstrakurikuler</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td class="fw-bold">Kategori:</td>
                                                                        <td>{{ $item['ekstrakurikuler']->kategori }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Jadwal:</td>
                                                                        <td>
                                                                            {{ $item['ekstrakurikuler']->hari }},
                                                                            {{ date('H:i', strtotime($item['ekstrakurikuler']->jam_mulai)) }}
                                                                            -
                                                                            {{ date('H:i', strtotime($item['ekstrakurikuler']->jam_selesai)) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Tempat:</td>
                                                                        <td>{{ $item['ekstrakurikuler']->tempat }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fw-bold">Pembina:</td>
                                                                        <td>{{ $item['ekstrakurikuler']->pembina->name }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Analisis Rekomendasi</h6>
                                                                <div
                                                                    class="alert alert-{{ $item['skor_akhir'] >= 80 ? 'success' : ($item['skor_akhir'] >= 70 ? 'warning' : ($item['skor_akhir'] >= 60 ? 'info' : 'danger')) }}">
                                                                    <strong>{{ $item['rekomendasi'] }}</strong>
                                                                    <br>
                                                                    @if ($item['skor_akhir'] >= 80)
                                                                        Ekstrakurikuler ini sangat cocok dengan profil dan
                                                                        kemampuan siswa.
                                                                    @elseif($item['skor_akhir'] >= 70)
                                                                        Ekstrakurikuler ini cukup sesuai dengan kemampuan
                                                                        siswa.
                                                                    @elseif($item['skor_akhir'] >= 60)
                                                                        Ekstrakurikuler ini dapat dipertimbangkan dengan
                                                                        beberapa penyesuaian.
                                                                    @elseif($item['skor_akhir'] >= 50)
                                                                        Ekstrakurikuler ini kurang sesuai dengan profil
                                                                        siswa saat ini.
                                                                    @else
                                                                        Ekstrakurikuler ini tidak direkomendasikan untuk
                                                                        siswa ini.
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Summary Statistics -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-chart-pie me-2"></i>
                                                    Ringkasan Rekomendasi
                                                </h6>
                                                <div class="row text-center">
                                                    @php
                                                        $sangat_direkomendasikan = collect($rekomendasi)
                                                            ->where('skor_akhir', '>=', 80)
                                                            ->count();
                                                        $direkomendasikan = collect($rekomendasi)
                                                            ->whereBetween('skor_akhir', [70, 79.99])
                                                            ->count();
                                                        $cukup = collect($rekomendasi)
                                                            ->whereBetween('skor_akhir', [60, 69.99])
                                                            ->count();
                                                        $kurang = collect($rekomendasi)
                                                            ->whereBetween('skor_akhir', [50, 59.99])
                                                            ->count();
                                                        $tidak = collect($rekomendasi)
                                                            ->where('skor_akhir', '<', 50)
                                                            ->count();
                                                    @endphp
                                                    <div class="col">
                                                        <div class="text-success fw-bold fs-4">
                                                            {{ $sangat_direkomendasikan }}</div>
                                                        <div class="text-muted small">Sangat Direkomendasikan</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-warning fw-bold fs-4">{{ $direkomendasikan }}
                                                        </div>
                                                        <div class="text-muted small">Direkomendasikan</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-info fw-bold fs-4">{{ $cukup }}</div>
                                                        <div class="text-muted small">Cukup</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-secondary fw-bold fs-4">{{ $kurang }}</div>
                                                        <div class="text-muted small">Kurang</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-danger fw-bold fs-4">{{ $tidak }}</div>
                                                        <div class="text-muted small">Tidak Direkomendasikan</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5>Tidak Ada Data Rekomendasi</h5>
                                    <p class="text-muted">Pastikan kriteria penilaian dan data siswa telah diatur dengan
                                        benar.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-primary me-2">
                                            Kelola Kriteria
                                        </a>
                                        <a href="{{ route('admin.penilaian.index') }}" class="btn btn-success">
                                            Kelola Penilaian
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-magic fa-5x text-primary opacity-50"></i>
                            </div>
                            <h3 class="text-primary mb-3">Sistem Rekomendasi Ekstrakurikuler</h3>
                            <p class="text-muted mb-4 lead">
                                Pilih siswa di atas untuk melihat rekomendasi ekstrakurikuler yang sesuai
                                <br>berdasarkan algoritma Weighted Scoring
                            </p>

                            <!-- Algorithm Info -->
                            <div class="row justify-content-center mt-5">
                                <div class="col-lg-8">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h5 class="text-primary mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Tentang Algoritma Weighted Scoring
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Cara Kerja:</h6>
                                                    <ul class="text-start text-muted">
                                                        <li>Setiap kriteria memiliki bobot kepentingan</li>
                                                        <li>Nilai siswa dinormalisasi ke skala 0-1</li>
                                                        <li>Skor = Σ(Nilai × Bobot) / Σ(Bobot)</li>
                                                        <li>Hasil diurutkan dari tertinggi ke terendah</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Keunggulan:</h6>
                                                    <ul class="text-start text-muted">
                                                        <li>Objektif dan transparan</li>
                                                        <li>Dapat disesuaikan dengan kebutuhan</li>
                                                        <li>Mudah dipahami dan dijelaskan</li>
                                                        <li>Konsisten dalam penilaian</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .progress-circle {
            position: relative;
            display: inline-block;
        }

        .progress-circle canvas {
            transform: rotate(-90deg);
        }

        .progress-circle .progress-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 14px;
        }

        .modal-lg {
            max-width: 900px;
        }

        .table th {
            font-size: 0.9rem;
        }

        .table td {
            font-size: 0.85rem;
        }

        .badge {
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(function(element) {
                new bootstrap.Tooltip(element);
            });
        });

        // Auto-refresh notification for real-time updates
        function refreshRecommendations() {
            const siswaId = document.querySelector('select[name="siswa_id"]').value;
            if (siswaId) {
                window.location.href = `{{ route('admin.rekomendasi.index') }}?siswa_id=${siswaId}`;
            }
        }

        // Export functionality
        function exportToPDF(siswaId) {
            window.open(`{{ route('admin.rekomendasi.export', '') }}/${siswaId}`, '_blank');
        }
    </script>
@endpush
