@extends('layouts.admin')

@section('title', 'Edit Penilaian Siswa')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white p-3 rounded-3 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.penilaian.index') }}" class="text-decoration-none">Penilaian</a>
                </li>
                <li class="breadcrumb-item active">Edit {{ $siswa->user->name }}</li>
            </ol>
        </nav>

        <!-- Student Profile Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fas fa-user me-2"></i>Profil Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="https://ui-avatars.com/api/?name={{ $siswa->user->name }}&background=667eea&color=ffffff&size=80"
                                    class="rounded-circle" width="80" height="80">
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-bold">Nama:</td>
                                                <td>{{ $siswa->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">NISN:</td>
                                                <td>{{ $siswa->nisn }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Kelas:</td>
                                                <td>{{ $siswa->kelas }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-bold">Jenis Kelamin:</td>
                                                <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Nilai Akademik:</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $siswa->nilai_akademik >= 80 ? 'success' : ($siswa->nilai_akademik >= 70 ? 'warning' : 'danger') }} fs-6">
                                                        {{ $siswa->nilai_akademik }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Minat:</td>
                                                <td>
                                                    @if ($siswa->minat)
                                                        @foreach ($siswa->minat as $minat)
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
            </div>
        </div>

        <!-- Assessment Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-calculator me-2"></i>Penilaian Kriteria</h5>
                        <div>
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#helpModal">
                                <i class="fas fa-question-circle me-1"></i>
                                Panduan Penilaian
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.penilaian.siswa.update', $siswa) }}" method="POST"
                            id="assessmentForm">
                            @csrf

                            <div class="row">
                                @foreach ($kriteria as $k)
                                    @php
                                        $currentValue = $penilaian->get($k->id)?->nilai ?? 50;
                                    @endphp
                                    <div class="col-lg-6 mb-4">
                                        <div class="card h-100 border-{{ $k->tipe == 'benefit' ? 'success' : 'warning' }}">
                                            <div
                                                class="card-header bg-{{ $k->tipe == 'benefit' ? 'success' : 'warning' }} text-white">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">{{ $k->nama_kriteria }}</h6>
                                                    <div>
                                                        <span class="badge bg-light text-dark">Bobot:
                                                            {{ $k->bobot }}</span>
                                                        <span class="badge bg-light text-dark">
                                                            {{ $k->tipe == 'benefit' ? 'Benefit' : 'Cost' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @if ($k->deskripsi)
                                                    <p class="text-muted small mb-3">{{ $k->deskripsi }}</p>
                                                @endif

                                                <!-- Slider Input -->
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Nilai:
                                                        <span class="fw-bold text-primary"
                                                            id="value-display-{{ $k->id }}">{{ $currentValue }}</span>
                                                    </label>
                                                    <input type="range" class="form-range kriteria-slider"
                                                        name="penilaian[{{ $k->id }}]"
                                                        id="slider-{{ $k->id }}" min="0" max="100"
                                                        step="1" value="{{ $currentValue }}"
                                                        data-kriteria-id="{{ $k->id }}">
                                                    <div class="d-flex justify-content-between">
                                                        <small class="text-muted">0</small>
                                                        <small class="text-muted">50</small>
                                                        <small class="text-muted">100</small>
                                                    </div>
                                                </div>

                                                <!-- Numeric Input -->
                                                <div class="mb-3">
                                                    <label class="form-label">Input Manual</label>
                                                    <input type="number" class="form-control kriteria-input"
                                                        id="input-{{ $k->id }}" min="0" max="100"
                                                        step="0.1" value="{{ $currentValue }}"
                                                        data-kriteria-id="{{ $k->id }}">
                                                </div>

                                                <!-- Visual Indicator -->
                                                <div class="progress mb-2" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $k->tipe == 'benefit' ? 'success' : 'warning' }}"
                                                        id="progress-{{ $k->id }}"
                                                        style="width: {{ $currentValue }}%"></div>
                                                </div>

                                                <!-- Rating Label -->
                                                <div class="text-center">
                                                    <span class="badge fs-6" id="rating-{{ $k->id }}">
                                                        {{ $currentValue >= 80 ? 'Sangat Baik' : ($currentValue >= 70 ? 'Baik' : ($currentValue >= 60 ? 'Cukup' : ($currentValue >= 40 ? 'Kurang' : 'Sangat Kurang'))) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Preview Calculation -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-calculator me-2"></i>
                                                Preview Perhitungan Weighted Scoring
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Kriteria</th>
                                                            <th class="text-center">Nilai</th>
                                                            <th class="text-center">Nilai Normal</th>
                                                            <th class="text-center">Bobot</th>
                                                            <th class="text-center">Skor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="calculationPreview">
                                                        @foreach ($kriteria as $k)
                                                            @php $currentValue = $penilaian->get($k->id)?->nilai ?? 50; @endphp
                                                            <tr data-kriteria="{{ $k->id }}">
                                                                <td>{{ $k->nama_kriteria }}</td>
                                                                <td class="text-center nilai-cell">{{ $currentValue }}
                                                                </td>
                                                                <td class="text-center normal-cell">
                                                                    {{ number_format($currentValue / 100, 3) }}</td>
                                                                <td class="text-center">{{ $k->bobot }}</td>
                                                                <td class="text-center skor-cell">
                                                                    {{ number_format(($currentValue / 100) * $k->bobot, 3) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-dark">
                                                        <tr>
                                                            <th colspan="4">Total Skor Weighted</th>
                                                            <th class="text-center" id="totalScore">-</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali
                                </a>
                                <button type="button" class="btn btn-warning" onclick="resetValues()">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Penilaian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-question-circle me-2"></i>
                        Panduan Penilaian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Skala Penilaian:</h6>
                            <ul class="list-unstyled">
                                <li><span class="badge bg-success me-2">80-100</span>Sangat Baik</li>
                                <li><span class="badge bg-info me-2">70-79</span>Baik</li>
                                <li><span class="badge bg-warning me-2">60-69</span>Cukup</li>
                                <li><span class="badge bg-orange me-2">40-59</span>Kurang</li>
                                <li><span class="badge bg-danger me-2">0-39</span>Sangat Kurang</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Tipe Kriteria:</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <span class="badge bg-success me-2">Benefit</span>
                                    Semakin tinggi semakin baik
                                </li>
                                <li>
                                    <span class="badge bg-warning me-2">Cost</span>
                                    Semakin rendah semakin baik
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <h6>Contoh Penilaian:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Contoh Nilai 90</th>
                                    <th>Contoh Nilai 60</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nilai Akademik</td>
                                    <td>Siswa dengan rata-rata 90+</td>
                                    <td>Siswa dengan rata-rata 75-80</td>
                                </tr>
                                <tr>
                                    <td>Minat dan Bakat</td>
                                    <td>Sangat antusias dan berbakat</td>
                                    <td>Cukup berminat</td>
                                </tr>
                                <tr>
                                    <td>Ketersediaan Waktu</td>
                                    <td>Sangat fleksibel</td>
                                    <td>Agak terbatas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-range {
            height: 8px;
        }

        .form-range::-webkit-slider-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #007bff;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .form-range::-moz-range-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #007bff;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            font-size: 0.9rem;
        }

        .progress {
            border-radius: 10px;
        }

        .badge.fs-6 {
            font-size: 0.85rem !important;
            padding: 0.5rem 0.75rem;
        }

        .bg-orange {
            background-color: #fd7e14 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const kriteria = @json($kriteria->keyBy('id'));

        // Initialize calculation
        document.addEventListener('DOMContentLoaded', function() {
            updateCalculation();

            // Sync slider and input
            document.querySelectorAll('.kriteria-slider').forEach(slider => {
                slider.addEventListener('input', function() {
                    const kriteriaId = this.dataset.kriteriaId;
                    const value = parseFloat(this.value);

                    // Update corresponding input
                    document.getElementById(`input-${kriteriaId}`).value = value;
                    updateDisplay(kriteriaId, value);
                    updateCalculation();
                });
            });

            document.querySelectorAll('.kriteria-input').forEach(input => {
                input.addEventListener('input', function() {
                    const kriteriaId = this.dataset.kriteriaId;
                    let value = parseFloat(this.value) || 0;

                    // Clamp value
                    value = Math.max(0, Math.min(100, value));
                    this.value = value;

                    // Update corresponding slider
                    document.getElementById(`slider-${kriteriaId}`).value = value;
                    updateDisplay(kriteriaId, value);
                    updateCalculation();
                });
            });
        });

        function updateDisplay(kriteriaId, value) {
            // Update value display
            document.getElementById(`value-display-${kriteriaId}`).textContent = value;

            // Update progress bar
            document.getElementById(`progress-${kriteriaId}`).style.width = value + '%';

            // Update rating label
            const rating = getRatingLabel(value);
            const ratingElement = document.getElementById(`rating-${kriteriaId}`);
            ratingElement.textContent = rating.text;
            ratingElement.className = `badge fs-6 bg-${rating.color}`;

            // Update hidden input for form submission
            document.querySelector(`input[name="penilaian[${kriteriaId}]"]`).value = value;
        }

        function getRatingLabel(value) {
            if (value >= 80) return {
                text: 'Sangat Baik',
                color: 'success'
            };
            if (value >= 70) return {
                text: 'Baik',
                color: 'info'
            };
            if (value >= 60) return {
                text: 'Cukup',
                color: 'warning'
            };
            if (value >= 40) return {
                text: 'Kurang',
                color: 'orange'
            };
            return {
                text: 'Sangat Kurang',
                color: 'danger'
            };
        }

        function updateCalculation() {
            let totalScore = 0;
            let totalWeight = 0;

            Object.values(kriteria).forEach(k => {
                const slider = document.getElementById(`slider-${k.id}`);
                const nilai = parseFloat(slider.value);
                const nilaiNormal = nilai / 100;
                const bobot = parseFloat(k.bobot);
                const skor = nilaiNormal * bobot;

                totalScore += skor;
                totalWeight += bobot;

                // Update table row
                const row = document.querySelector(`tr[data-kriteria="${k.id}"]`);
                if (row) {
                    row.querySelector('.nilai-cell').textContent = nilai;
                    row.querySelector('.normal-cell').textContent = nilaiNormal.toFixed(3);
                    row.querySelector('.skor-cell').textContent = skor.toFixed(3);
                }
            });

            const finalScore = totalWeight > 0 ? (totalScore / totalWeight) * 100 : 0;
            document.getElementById('totalScore').textContent = finalScore.toFixed(2);
        }

        function resetValues() {
            if (confirm('Apakah Anda yakin ingin mereset semua nilai ke default (50)?')) {
                document.querySelectorAll('.kriteria-slider').forEach(slider => {
                    const kriteriaId = slider.dataset.kriteriaId;
                    slider.value = 50;
                    document.getElementById(`input-${kriteriaId}`).value = 50;
                    updateDisplay(kriteriaId, 50);
                });
                updateCalculation();
            }
        }

        // Form validation
        document.getElementById('assessmentForm').addEventListener('submit', function(e) {
            const totalWeight = Object.values(kriteria).reduce((sum, k) => sum + parseFloat(k.bobot), 0);

            if (Math.abs(totalWeight - 1.0) > 0.01) {
                e.preventDefault();
                alert('Total bobot kriteria harus sama dengan 1.00. Silakan periksa pengaturan kriteria.');
                return false;
            }

            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
            submitBtn.disabled = true;

            // Re-enable after 3 seconds if form doesn't submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
    </script>
@endpush
