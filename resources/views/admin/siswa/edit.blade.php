@extends('layouts.admin')

@section('title', 'Edit Siswa')

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
                    <a href="{{ route('admin.siswa.index') }}" class="text-decoration-none">Kelola Siswa</a>
                </li>
                <li class="breadcrumb-item active">Edit {{ $siswa->user->name }}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Edit Data Siswa</h1>
                <p class="text-muted mb-0">Update informasi siswa dalam sistem</p>
            </div>
            <div>
                <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-eye me-1"></i>
                    Lihat Detail
                </a>
                <a href="{{ route('admin.penilaian.siswa', $siswa) }}" class="btn btn-success">
                    <i class="fas fa-calculator me-1"></i>
                    Edit Penilaian
                </a>
            </div>
        </div>

        <!-- Student Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fas fa-user me-2"></i>Profil Siswa Saat Ini</h5>
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
                                                <td class="fw-bold">NISN:</td>
                                                <td>{{ $siswa->nisn }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Kelas:</td>
                                                <td>{{ $siswa->kelas }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Bergabung:</td>
                                                <td>{{ $siswa->created_at->format('d M Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-bold">Status:</td>
                                                <td>
                                                    @php
                                                        $ekstrakurikulerAktif = $siswa->pendaftaran
                                                            ->where('status', 'approved')
                                                            ->count();
                                                    @endphp
                                                    @if ($ekstrakurikulerAktif > 0)
                                                        <span class="badge bg-success">
                                                            Aktif ({{ $ekstrakurikulerAktif }} kegiatan)
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Ada Kegiatan</span>
                                                    @endif
                                                </td>
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
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-edit me-2"></i>Form Edit Data Siswa</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" id="siswaForm">
                            @csrf
                            @method('PUT')

                            <!-- User Account Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-circle me-2"></i>Informasi Akun
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $siswa->user->name) }}"
                                            placeholder="Masukkan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $siswa->user->email) }}"
                                            placeholder="contoh@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone', $siswa->user->phone) }}"
                                            placeholder="08xxxxxxxxxx">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-info mb-3">
                                        <small><i class="fas fa-info-circle me-1"></i>
                                            Biarkan kosong jika tidak ingin mengubah password
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Data Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-id-card me-2"></i>Data Siswa
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NISN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                            name="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                                            placeholder="Nomor Induk Siswa Nasional">
                                        @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kelas') is-invalid @enderror"
                                            name="kelas" value="{{ old('kelas', $siswa->kelas) }}"
                                            placeholder="Contoh: X IPA 1, XI IPS 2">
                                        @error('kelas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-check form-check-card">
                                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                        id="laki" value="L"
                                                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                                    <label class="form-check-label card p-3 text-center" for="laki">
                                                        <i class="fas fa-male fa-2x text-primary mb-2"></i>
                                                        <div class="fw-bold">Laki-laki</div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check form-check-card">
                                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                        id="perempuan" value="P"
                                                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                                    <label class="form-check-label card p-3 text-center" for="perempuan">
                                                        <i class="fas fa-female fa-2x text-danger mb-2"></i>
                                                        <div class="fw-bold">Perempuan</div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3"
                                            placeholder="Masukkan alamat lengkap">{{ old('alamat', $siswa->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Academic & Interest Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-graduation-cap me-2"></i>Data Akademik & Minat
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai Akademik <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('nilai_akademik') is-invalid @enderror"
                                                name="nilai_akademik"
                                                value="{{ old('nilai_akademik', $siswa->nilai_akademik) }}"
                                                min="0" max="100" step="0.1" id="nilaiAkademik">
                                            <span class="input-group-text" id="nilaiStatus">Baik</span>
                                        </div>
                                        @error('nilai_akademik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Rata-rata nilai akademik siswa (0-100)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Minat & Hobi</label>
                                        <div class="row">
                                            @php
                                                $minatOptions = [
                                                    'Olahraga',
                                                    'Seni',
                                                    'Sains',
                                                    'Teknologi',
                                                    'Bahasa',
                                                    'Musik',
                                                    'Keagamaan',
                                                    'Sosial',
                                                ];
                                                $currentMinat = old('minat', $siswa->minat ?? []);
                                            @endphp
                                            @foreach ($minatOptions as $minat)
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="minat[]"
                                                            value="{{ $minat }}" id="minat{{ $loop->index }}"
                                                            {{ in_array($minat, $currentMinat) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="minat{{ $loop->index }}">
                                                            {{ $minat }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-text">Pilih minat siswa (opsional, membantu rekomendasi)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Changes Preview -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-eye me-2"></i>Preview Perubahan
                                            </h6>
                                            <div class="row">
                                                <div class="col-auto">
                                                    <img id="previewAvatar"
                                                        src="https://ui-avatars.com/api/?name={{ $siswa->user->name }}&background=667eea&color=ffffff&size=80"
                                                        class="rounded-circle" width="80" height="80">
                                                </div>
                                                <div class="col">
                                                    <table class="table table-borderless table-sm">
                                                        <tr>
                                                            <td width="150"><strong>Nama:</strong></td>
                                                            <td id="previewName">{{ $siswa->user->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>NISN:</strong></td>
                                                            <td id="previewNisn">{{ $siswa->nisn }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Kelas:</strong></td>
                                                            <td id="previewKelas">{{ $siswa->kelas }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Email:</strong></td>
                                                            <td id="previewEmail">{{ $siswa->user->email }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Detail
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Update Data Siswa
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
        document.addEventListener('DOMContentLoaded', function() {
            // Preview functionality
            function updatePreview() {
                const name = document.querySelector('input[name="name"]').value;
                const nisn = document.querySelector('input[name="nisn"]').value;
                const kelas = document.querySelector('input[name="kelas"]').value;
                const email = document.querySelector('input[name="email"]').value;

                document.getElementById('previewName').textContent = name;
                document.getElementById('previewNisn').textContent = nisn;
                document.getElementById('previewKelas').textContent = kelas;
                document.getElementById('previewEmail').textContent = email;

                // Update avatar
                document.getElementById('previewAvatar').src =
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=667eea&color=ffffff&size=80`;
            }

            // Update nilai akademik status
            function updateNilaiStatus() {
                const nilai = parseFloat(document.getElementById('nilaiAkademik').value) || 0;
                const statusElement = document.getElementById('nilaiStatus');

                if (nilai >= 90) {
                    statusElement.textContent = 'Sangat Baik';
                    statusElement.className = 'input-group-text bg-success text-white';
                } else if (nilai >= 80) {
                    statusElement.textContent = 'Baik';
                    statusElement.className = 'input-group-text bg-info text-white';
                } else if (nilai >= 70) {
                    statusElement.textContent = 'Cukup';
                    statusElement.className = 'input-group-text bg-warning text-dark';
                } else if (nilai >= 60) {
                    statusElement.textContent = 'Kurang';
                    statusElement.className = 'input-group-text bg-danger text-white';
                } else {
                    statusElement.textContent = 'Sangat Kurang';
                    statusElement.className = 'input-group-text bg-dark text-white';
                }
            }

            // Event listeners
            document.querySelector('input[name="name"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="nisn"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="kelas"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="email"]').addEventListener('input', updatePreview);
            document.getElementById('nilaiAkademik').addEventListener('input', updateNilaiStatus);

            // Initial update
            updateNilaiStatus();

            // Form submission
            document.getElementById('siswaForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Re-enable after 3 seconds if form doesn't submit
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        });
    </script>
@endpush
