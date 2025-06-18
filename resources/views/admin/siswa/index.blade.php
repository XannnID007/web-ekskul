@extends('layouts.admin')

@section('title', 'Kelola Siswa')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Kelola Siswa</h1>
                <p class="text-muted mb-0">Manajemen data siswa untuk sistem ekstrakurikuler</p>
            </div>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Siswa
            </a>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.siswa.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Cari Siswa</label>
                                    <input type="text" class="form-control" name="search"
                                        value="{{ request('search') }}" placeholder="Nama, email, NISN">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter Kelas</label>
                                    <select name="kelas" class="form-select">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas }}"
                                                {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                                {{ $kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i>
                                        Filter
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    @if (request()->hasAny(['search', 'kelas']))
                                        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-times me-1"></i>
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card primary">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $siswa->total() }}</div>
                            <div class="label">Total Siswa</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $siswa->where('jenis_kelamin', 'L')->count() }}</div>
                            <div class="label">Laki-laki</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-male icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $siswa->where('jenis_kelamin', 'P')->count() }}</div>
                            <div class="label">Perempuan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-female icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $kelasList->count() }}</div>
                            <div class="label">Total Kelas</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Siswa Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users me-2"></i>Daftar Siswa</h5>
                    </div>
                    <div class="card-body">
                        @if ($siswa->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa</th>
                                            <th>NISN</th>
                                            <th>Kelas</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Nilai Akademik</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswa as $index => $item)
                                            <tr>
                                                <td>{{ $siswa->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $item->user->name }}&background=667eea&color=ffffff&size=40"
                                                            class="rounded-circle me-3" width="40" height="40">
                                                        <div>
                                                            <div class="fw-bold">{{ $item->user->name }}</div>
                                                            <small class="text-muted">{{ $item->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->nisn }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $item->kelas }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $item->jenis_kelamin == 'L' ? 'info' : 'pink' }}">
                                                        {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $item->nilai_akademik >= 80 ? 'success' : ($item->nilai_akademik >= 70 ? 'warning' : 'danger') }} fs-6">
                                                        {{ $item->nilai_akademik }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $ekstrakurikulerAktif = $item->pendaftaran
                                                            ->where('status', 'approved')
                                                            ->count();
                                                    @endphp
                                                    @if ($ekstrakurikulerAktif > 0)
                                                        <span class="badge bg-success">
                                                            Aktif ({{ $ekstrakurikulerAktif }})
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Ada</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.siswa.show', $item) }}"
                                                            class="btn btn-outline-info" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.siswa.edit', $item) }}"
                                                            class="btn btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.penilaian.siswa', $item) }}"
                                                            class="btn btn-outline-success" title="Penilaian">
                                                            <i class="fas fa-calculator"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="deleteSiswa({{ $item->id }}, '{{ $item->user->name }}')"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $siswa->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Data Siswa</h5>
                                <p class="text-muted">Tambahkan data siswa untuk mulai mengelola sistem ekstrakurikuler</p>
                                <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Siswa Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus siswa <strong id="siswaName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data pendaftaran dan penilaian siswa akan ikut terhapus!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-pink {
            background-color: #ec4899 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteSiswa(id, name) {
            document.getElementById('siswaName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/siswa/${id}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endpush
