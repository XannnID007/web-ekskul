@extends('layouts.admin')

@section('title', 'Detail Siswa - ' . $siswa->user->name)

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
                <li class="breadcrumb-item active">{{ $siswa->user->name }}</li>
            </ol>
        </nav>

        <!-- Student Profile Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img src="https://ui-avatars.com/api/?name={{ $siswa->user->name }}&background=ffffff&color=667eea&size=120"
                                    class="rounded-circle border border-4 border-white" width="120" height="120">
                            </div>
                            <div class="col">
                                <h2 class="mb-2">{{ $siswa->user->name }}</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><i class="fas fa-id-card me-2"></i>NISN: {{ $siswa->nisn }}</p>
                                        <p class="mb-1"><i class="fas fa-school me-2"></i>Kelas: {{ $siswa->kelas }}</p>
                                        <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $siswa->user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><i
                                                class="fas fa-user me-2"></i>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                        <p class="mb-1"><i
                                                class="fas fa-calendar me-2"></i>{{ $siswa->tanggal_lahir->format('d F Y') }}
                                        </p>
                                        @if ($siswa->user->phone)
                                            <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $siswa->user->phone }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="text-end">
                                    <div class="mb-2">
                                        <span class="badge bg-white text-dark fs-6 px-3 py-2">
                                            Nilai: {{ $siswa->nilai_akademik }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-light btn-sm me-2">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="{{ route('admin.penilaian.siswa', $siswa) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-calculator me-1"></i>Penilaian
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user me-2"></i>Informasi Personal</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">NISN:</td>
                                <td>{{ $siswa->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelas:</td>
                                <td><span class="badge bg-primary">{{ $siswa->kelas }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin:</td>
                                <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Lahir:</td>
                                <td>{{ $siswa->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Usia:</td>
                                <td>{{ $siswa->tanggal_lahir->diffInYears(now()) }} tahun</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $siswa->user->email }}</td>
                            </tr>
                            @if ($siswa->user->phone)
                                <tr>
                                    <td class="fw-bold">Telepon:</td>
                                    <td>{{ $siswa->user->phone }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="fw-bold">Bergabung:</td>
                                <td>{{ $siswa->created_at->format('d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Academic Performance -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-graduation-cap me-2"></i>Performa Akademik</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div
                                class="display-4 fw-bold text-{{ $siswa->nilai_akademik >= 80 ? 'success' : ($siswa->nilai_akademik >= 70 ? 'warning' : 'danger') }}">
                                {{ $siswa->nilai_akademik }}
                            </div>
                            <div class="text-muted">Nilai Akademik</div>
                        </div>

                        <div class="progress mb-3" style="height: 15px;">
                            <div class="progress-bar bg-{{ $siswa->nilai_akademik >= 80 ? 'success' : ($siswa->nilai_akademik >= 70 ? 'warning' : 'danger') }}"
                                style="width: {{ $siswa->nilai_akademik }}%"></div>
                        </div>

                        <div class="text-center">
                            @if ($siswa->nilai_akademik >= 90)
                                <span class="badge bg-success fs-6">Sangat Baik</span>
                            @elseif($siswa->nilai_akademik >= 80)
                                <span class="badge bg-info fs-6">Baik</span>
                            @elseif($siswa->nilai_akademik >= 70)
                                <span class="badge bg-warning fs-6">Cukup</span>
                            @else
                                <span class="badge bg-danger fs-6">Perlu Perbaikan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Interests & Hobbies -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-heart me-2"></i>Minat & Hobi</h5>
                    </div>
                    <div class="card-body">
                        @if ($siswa->minat && count($siswa->minat) > 0)
                            @foreach ($siswa->minat as $minat)
                                <span class="badge bg-info me-1 mb-1">{{ $minat }}</span>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p class="mb-0">Belum ada data minat</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Address -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Alamat</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $siswa->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Assessment Scores -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-calculator me-2"></i>Penilaian Kriteria</h5>
                        <a href="{{ route('admin.penilaian.siswa', $siswa) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Penilaian
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($siswa->penilaianSiswa->count() > 0)
                            <div class="row">
                                @foreach ($siswa->penilaianSiswa as $penilaian)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{{ $penilaian->kriteria->nama_kriteria }}</strong>
                                            <span
                                                class="badge bg-{{ $penilaian->nilai >= 80 ? 'success' : ($penilaian->nilai >= 70 ? 'warning' : 'danger') }} fs-6">
                                                {{ $penilaian->nilai }}
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $penilaian->nilai >= 80 ? 'success' : ($penilaian->nilai >= 70 ? 'warning' : 'danger') }}"
                                                style="width: {{ $penilaian->nilai }}%"></div>
                                        </div>
                                        <small class="text-muted">
                                            Bobot: {{ $penilaian->kriteria->bobot }} |
                                            {{ $penilaian->kriteria->tipe == 'benefit' ? 'Benefit' : 'Cost' }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calculator fa-3x mb-3"></i>
                                <h6>Belum Ada Penilaian</h6>
                                <p class="mb-3">Siswa belum memiliki penilaian kriteria</p>
                                <a href="{{ route('admin.penilaian.siswa', $siswa) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah Penilaian
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ekstrakurikuler Activities -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-star me-2"></i>Aktivitas Ekstrakurikuler</h5>
                    </div>
                    <div class="card-body">
                        @if ($siswa->pendaftaran->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ekstrakurikuler</th>
                                            <th>Pembina</th>
                                            <th>Status</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswa->pendaftaran as $pendaftaran)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $pendaftaran->ekstrakurikuler->nama_ekskul }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $pendaftaran->ekstrakurikuler->kategori }}</small>
                                                </td>
                                                <td>{{ $pendaftaran->ekstrakurikuler->pembina->name }}</td>
                                                <td>
                                                    @if ($pendaftaran->status == 'pending')
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif($pendaftaran->status == 'approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>{{ $pendaftaran->created_at->format('d M Y') }}</td>
                                                <td>
                                                    @if ($pendaftaran->skor_rekomendasi)
                                                        <span
                                                            class="badge bg-primary">{{ number_format($pendaftaran->skor_rekomendasi, 1) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-star fa-3x mb-3"></i>
                                <h6>Belum Ada Aktivitas</h6>
                                <p class="mb-0">Siswa belum mendaftar ekstrakurikuler</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-history me-2"></i>Timeline Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Account Creation -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Akun Dibuat</h6>
                                    <p class="text-muted mb-1">Siswa bergabung dengan sistem</p>
                                    <small class="text-muted">{{ $siswa->created_at->format('d F Y, H:i') }}</small>
                                </div>
                            </div>

                            <!-- Registration Activities -->
                            @foreach ($siswa->pendaftaran->sortByDesc('created_at')->take(5) as $pendaftaran)
                                <div class="timeline-item">
                                    <div
                                        class="timeline-marker bg-{{ $pendaftaran->status == 'approved' ? 'success' : ($pendaftaran->status == 'pending' ? 'warning' : 'danger') }}">
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">
                                            @if ($pendaftaran->status == 'approved')
                                                Bergabung dengan {{ $pendaftaran->ekstrakurikuler->nama_ekskul }}
                                            @elseif($pendaftaran->status == 'pending')
                                                Mendaftar {{ $pendaftaran->ekstrakurikuler->nama_ekskul }}
                                            @else
                                                Ditolak dari {{ $pendaftaran->ekstrakurikuler->nama_ekskul }}
                                            @endif
                                        </h6>
                                        @if ($pendaftaran->catatan_pembina)
                                            <p class="text-muted mb-1">{{ $pendaftaran->catatan_pembina }}</p>
                                        @endif
                                        <small
                                            class="text-muted">{{ $pendaftaran->created_at->format('d F Y, H:i') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }
    </style>
@endpush
