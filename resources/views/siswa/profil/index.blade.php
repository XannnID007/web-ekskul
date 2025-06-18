@extends('layouts.siswa')

@section('title', 'Profil Saya')

@section('content')
    <!-- Page Header -->
    <div class="mb-4">
        <h4 class="mb-1">Profil Saya</h4>
        <p class="text-muted mb-0">Kelola informasi personal Anda</p>
    </div>

    <div class="row">
        <!-- Profile Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ $siswa->user->name }}&background=4f46e5&color=ffffff&size=120"
                        class="rounded-circle mb-3" width="120" height="120">
                    <h5>{{ $siswa->user->name }}</h5>
                    <p class="text-muted mb-1">{{ $siswa->kelas }}</p>
                    <p class="text-muted">NISN: {{ $siswa->nisn }}</p>

                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <div class="h5 text-primary">{{ $siswa->pendaftaran->where('status', 'approved')->count() }}
                            </div>
                            <small class="text-muted">Ekstrakurikuler</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-success">{{ number_format($siswa->nilai_akademik, 0) }}</div>
                            <small class="text-muted">Nilai</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-info">{{ is_array($siswa->minat) ? count($siswa->minat) : 0 }}</div>
                            <small class="text-muted">Minat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Minat -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="fas fa-heart me-2"></i>Minat Saya</h6>
                </div>
                <div class="card-body">
                    @if ($siswa->minat && count($siswa->minat) > 0)
                        @foreach ($siswa->minat as $minat)
                            <span class="badge bg-primary me-1 mb-1">{{ $minat }}</span>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">Belum ada minat yang dipilih</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-edit me-2"></i>Edit Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('siswa.profil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $siswa->user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $siswa->user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN</label>
                                <input type="text" class="form-control" value="{{ $siswa->nisn }}" readonly>
                                <div class="form-text">NISN tidak dapat diubah</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" class="form-control" value="{{ $siswa->kelas }}" readonly>
                                <div class="form-text">Kelas tidak dapat diubah</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone', $siswa->user->phone) }}"
                                    placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <input type="text" class="form-control"
                                    value="{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly>
                                <div class="form-text">Jenis kelamin tidak dapat diubah</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="text" class="form-control"
                                    value="{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d M Y') : '-' }}"
                                    readonly>
                                <div class="form-text">Tanggal lahir tidak dapat diubah</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nilai Akademik</label>
                                <input type="text" class="form-control"
                                    value="{{ number_format($siswa->nilai_akademik, 1) }}" readonly>
                                <div class="form-text">Nilai akademik dikelola oleh admin</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat *</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Minat Ekstrakurikuler</label>
                            <div class="row">
                                @php
                                    $kategoriOptions = [
                                        'Olahraga',
                                        'Seni',
                                        'Akademik',
                                        'Teknologi',
                                        'Sosial',
                                        'Keagamaan',
                                    ];
                                    $currentMinat = $siswa->minat ?? [];
                                @endphp
                                @foreach ($kategoriOptions as $kategori)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="minat[]"
                                                value="{{ $kategori }}" id="minat_{{ $loop->index }}"
                                                {{ in_array($kategori, $currentMinat) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="minat_{{ $loop->index }}">
                                                {{ $kategori }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">Pilih minat Anda untuk mendapatkan rekomendasi yang lebih akurat</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aktivitas Ekstrakurikuler -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-star me-2"></i>Ekstrakurikuler Saya</h5>
                </div>
                <div class="card-body">
                    @php
                        $ekskulAktif = $siswa->pendaftaran->where('status', 'approved');
                    @endphp

                    @if ($ekskulAktif->count() > 0)
                        @foreach ($ekskulAktif as $pendaftaran)
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
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i>Jelajahi Ekstrakurikuler
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
