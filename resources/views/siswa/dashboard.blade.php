@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
    <!-- Welcome Section -->
    <div class="card card-gradient mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">Selamat Datang, {{ $siswa->user->name }}! 👋</h3>
                    <p class="mb-0 opacity-90">Kelola kegiatan ekstrakurikuler Anda dengan mudah</p>
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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ $pendaftaranAktif->count() }}</div>
                        <div>Ekstrakurikuler Aktif</div>
                    </div>
                    <i class="fas fa-star icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ $pendaftaranPending->count() }}</div>
                        <div>Menunggu Persetujuan</div>
                    </div>
                    <i class="fas fa-clock icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ number_format($siswa->nilai_akademik, 0) }}</div>
                        <div>Nilai Akademik</div>
                    </div>
                    <i class="fas fa-graduation-cap icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-1">{{ count($topRekomendasi) }}</div>
                        <div>Rekomendasi Tersedia</div>
                    </div>
                    <i class="fas fa-magic icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ekstrakurikuler Aktif -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-star me-2"></i>Ekstrakurikuler Saya</h5>
                    <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-sm btn-outline-primary">
                        Jelajahi Lebih
                    </a>
                </div>
                <div class="card-body">
                    @if ($pendaftaranAktif->count() > 0)
                        @foreach ($pendaftaranAktif as $pendaftaran)
                            <div class="d-flex align-items-center p-3 border rounded mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $pendaftaran->ekstrakurikuler->nama_ekskul }}</h6>
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar me-1"></i>{{ $pendaftaran->ekstrakurikuler->hari }}
                                        <i class="fas fa-clock ms-2 me-1"></i>
                                        {{ date('H:i', strtotime($pendaftaran->ekstrakurikuler->jam_mulai)) }} -
                                        {{ date('H:i', strtotime($pendaftaran->ekstrakurikuler->jam_selesai)) }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-user me-1"></i>{{ $pendaftaran->ekstrakurikuler->pembina->name }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success">Aktif</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h6>Belum Mengikuti Ekstrakurikuler</h6>
                            <p class="text-muted">Jelajahi dan daftar ekstrakurikuler yang menarik!</p>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Jelajahi Sekarang
                            </a>
                        </div>
                    @endif

                    @if ($pendaftaranPending->count() > 0)
                        <hr>
                        <h6 class="text-muted mb-3">Menunggu Persetujuan</h6>
                        @foreach ($pendaftaranPending as $pendaftaran)
                            <div class="d-flex align-items-center p-3 border rounded mb-2 bg-light">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $pendaftaran->ekstrakurikuler->nama_ekskul }}</h6>
                                    <small class="text-muted">Didaftar
                                        {{ $pendaftaran->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge bg-warning">Pending</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Rekomendasi -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-magic me-2"></i>Rekomendasi Untuk Anda</h5>
                    <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if (count($topRekomendasi) > 0)
                        @foreach ($topRekomendasi as $index => $item)
                            <div
                                class="d-flex align-items-center p-3 border rounded mb-3 {{ $index == 0 ? 'border-warning' : '' }}">
                                <div class="flex-shrink-0">
                                    @if ($index == 0)
                                        <div class="badge bg-warning position-absolute" style="top: -5px; left: -5px;">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                    @endif
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px; background: linear-gradient(135deg, 
                                        {{ $item['skor_akhir'] >= 80
                                            ? '#28a745, #20c997'
                                            : ($item['skor_akhir'] >= 70
                                                ? '#ffc107, #fd7e14'
                                                : '#17a2b8, #6f42c1') }});">
                                        {{ number_format($item['skor_akhir'], 0) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $item['ekstrakurikuler']->nama_ekskul }}</h6>
                                    <small class="text-muted">{{ $item['rekomendasi'] }}</small>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-primary btn-sm w-100">
                                Lihat Rekomendasi Lengkap
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-magic fa-3x text-muted mb-3"></i>
                            <h6>Rekomendasi Belum Tersedia</h6>
                            <p class="text-muted small">Lengkapi profil Anda untuk mendapatkan rekomendasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-bullhorn me-2"></i>Pengumuman Terbaru</h5>
                </div>
                <div class="card-body">
                    @if ($pengumuman->count() > 0)
                        @foreach ($pengumuman as $item)
                            <div class="d-flex align-items-start p-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $item->judul }}</h6>
                                    <p class="text-muted mb-2">{{ Str::limit($item->konten, 100) }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $item->author->name }}
                                        <i
                                            class="fas fa-calendar ms-2 me-1"></i>{{ $item->published_at->format('d M Y') }}
                                        <span
                                            class="badge bg-{{ $item->kategori == 'umum' ? 'primary' : 'success' }} ms-2">
                                            {{ ucfirst($item->kategori) }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                            <h6>Belum Ada Pengumuman</h6>
                            <p class="text-muted">Pengumuman akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
