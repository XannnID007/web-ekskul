@extends('layouts.admin')

@section('title', 'Manajemen Penilaian Siswa')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Penilaian Siswa</h1>
                <p class="text-muted mb-0">Kelola nilai siswa untuk setiap kriteria penilaian</p>
            </div>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#batchModal">
                    <i class="fas fa-edit me-1"></i>
                    Batch Update
                </button>
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sliders-h me-1"></i>
                    Kelola Kriteria
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.penilaian.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Cari Siswa</label>
                                    <input type="text" class="form-control" name="search"
                                        value="{{ request('search') }}" placeholder="Nama, NISN, atau Kelas">
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
                                        <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary w-100">
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

        <!-- Kriteria Info -->
        @if ($kriteria->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Kriteria Penilaian Aktif
                            </h6>
                            <div class="row">
                                @foreach ($kriteria as $k)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">{{ $k->bobot }}</span>
                                            <span class="fw-bold">{{ $k->nama_kriteria }}</span>
                                            <span class="badge bg-{{ $k->tipe == 'benefit' ? 'success' : 'warning' }} ms-2">
                                                {{ $k->tipe }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Students Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users me-2"></i>Daftar Siswa dan Penilaian</h5>
                    </div>
                    <div class="card-body">
                        @if ($siswa->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>Siswa</th>
                                            <th>Kelas</th>
                                            @foreach ($kriteria as $k)
                                                <th class="text-center">
                                                    <div class="fw-bold">{{ $k->nama_kriteria }}</div>
                                                    <small class="text-muted">({{ $k->bobot }})</small>
                                                </th>
                                            @endforeach
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswa as $s)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="form-check-input siswa-checkbox"
                                                        value="{{ $s->id }}">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $s->user->name }}&background=667eea&color=ffffff&size=40"
                                                            class="rounded-circle me-3" width="40" height="40">
                                                        <div>
                                                            <div class="fw-bold">{{ $s->user->name }}</div>
                                                            <small class="text-muted">{{ $s->nisn }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $s->kelas }}</td>
                                                @foreach ($kriteria as $k)
                                                    @php
                                                        $penilaian = $s->penilaianSiswa
                                                            ->where('kriteria_id', $k->id)
                                                            ->first();
                                                        $nilai = $penilaian ? $penilaian->nilai : 0;
                                                    @endphp
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-{{ $nilai >= 80 ? 'success' : ($nilai >= 70 ? 'warning' : ($nilai >= 60 ? 'info' : 'danger')) }} fs-6">
                                                            {{ $nilai }}
                                                        </span>
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    <a href="{{ route('admin.penilaian.siswa', $s) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </a>
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
                                <p class="text-muted">Tambahkan data siswa terlebih dahulu</p>
                                <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Siswa
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Update Modal -->
    <div class="modal fade" id="batchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batch Update Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="batchForm">
                        <div class="mb-3">
                            <label class="form-label">Pilih Kriteria</label>
                            <select name="kriteria_id" class="form-select" required>
                                <option value="">-- Pilih Kriteria --</option>
                                @foreach ($kriteria as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kriteria }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai</label>
                            <input type="number" name="nilai" class="form-control" min="0" max="100"
                                step="0.1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Siswa yang Dipilih</label>
                            <div id="selectedStudents" class="border rounded p-2"
                                style="max-height: 150px; overflow-y: auto;">
                                <small class="text-muted">Pilih siswa dari tabel terlebih dahulu</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="submitBatchUpdate()">
                        Update Nilai
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.siswa-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedStudents();
        });

        // Individual checkbox change
        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedStudents);
        });

        function updateSelectedStudents() {
            const selected = Array.from(document.querySelectorAll('.siswa-checkbox:checked'));
            const container = document.getElementById('selectedStudents');

            if (selected.length === 0) {
                container.innerHTML = '<small class="text-muted">Pilih siswa dari tabel terlebih dahulu</small>';
            } else {
                const names = selected.map(cb => {
                    const row = cb.closest('tr');
                    const name = row.querySelector('.fw-bold').textContent;
                    return `<span class="badge bg-primary me-1">${name}</span>`;
                });
                container.innerHTML = names.join('');
            }
        }

        function submitBatchUpdate() {
            const selectedIds = Array.from(document.querySelectorAll('.siswa-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Pilih minimal satu siswa');
                return;
            }

            const formData = new FormData(document.getElementById('batchForm'));

            fetch('{{ route('admin.penilaian.batch') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        siswa_ids: selectedIds,
                        kriteria_id: formData.get('kriteria_id'),
                        nilai: formData.get('nilai')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal update penilaian');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    </script>
@endpush
