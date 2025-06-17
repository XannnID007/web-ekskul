@extends('layouts.admin')

@section('title', 'Tambah Kriteria')

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
                    <a href="{{ route('admin.kriteria.index') }}" class="text-decoration-none">Kriteria</a>
                </li>
                <li class="breadcrumb-item active">Tambah Kriteria</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Tambah Kriteria Baru</h1>
                <p class="text-muted mb-0">Buat kriteria penilaian untuk sistem rekomendasi</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus me-2"></i>Form Tambah Kriteria</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.kriteria.store') }}" method="POST">
                            @csrf

                            <!-- Nama Kriteria -->
                            <div class="mb-3">
                                <label class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror"
                                    name="nama_kriteria" value="{{ old('nama_kriteria') }}"
                                    placeholder="Contoh: Nilai Akademik, Minat dan Bakat">
                                @error('nama_kriteria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bobot -->
                            <div class="mb-3">
                                <label class="form-label">Bobot Kriteria <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('bobot') is-invalid @enderror"
                                        name="bobot" value="{{ old('bobot') }}" step="0.01" min="0"
                                        max="1" placeholder="0.25">
                                    <span class="input-group-text">
                                        <span id="bobotPercentage">0%</span>
                                    </span>
                                </div>
                                @error('bobot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Masukkan nilai antara 0.01 - 1.00. Total bobot semua kriteria aktif harus = 1.00
                                </div>
                            </div>

                            <!-- Tipe Kriteria -->
                            <div class="mb-3">
                                <label class="form-label">Tipe Kriteria <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input" type="radio" name="tipe" id="benefit"
                                                value="benefit" {{ old('tipe') == 'benefit' ? 'checked' : '' }}>
                                            <label class="form-check-label card p-3" for="benefit">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-arrow-up text-success fa-2x me-3"></i>
                                                    <div>
                                                        <h6 class="mb-1">Benefit</h6>
                                                        <small class="text-muted">Semakin tinggi semakin baik</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-card">
                                            <input class="form-check-input" type="radio" name="tipe" id="cost"
                                                value="cost" {{ old('tipe') == 'cost' ? 'checked' : '' }}>
                                            <label class="form-check-label card p-3" for="cost">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-arrow-down text-warning fa-2x me-3"></i>
                                                    <div>
                                                        <h6 class="mb-1">Cost</h6>
                                                        <small class="text-muted">Semakin rendah semakin baik</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('tipe')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-4">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3"
                                    placeholder="Jelaskan kriteria ini secara detail...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Kriteria
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-check-card .form-check-input {
            display: none;
        }

        .form-check-card .card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
        }

        .form-check-card .card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .form-check-card input:checked+.card {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Update percentage display
        document.querySelector('input[name="bobot"]').addEventListener('input', function() {
            const value = parseFloat(this.value) || 0;
            const percentage = Math.round(value * 100);
            document.getElementById('bobotPercentage').textContent = percentage + '%';
        });
    </script>
@endpush
