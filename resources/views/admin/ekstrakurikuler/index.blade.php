@extends('layouts.admin')

@section('title', 'Kelola Ekstrakurikuler')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Kelola Ekstrakurikuler</h1>
                <p class="text-muted mb-0">Manajemen kegiatan ekstrakurikuler sekolah</p>
            </div>
            <a href="{{ route('admin.ekstrakurikuler.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Ekstrakurikuler
            </a>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.ekstrakurikuler.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Cari Ekstrakurikuler</label>
                                    <input type="text" class="form-control" name="search"
                                        value="{{ request('search') }}" placeholder="Nama atau kategori">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Filter Kategori</label>
                                    <select name="kategori" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori }}"
                                                {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                                {{ $kategori }}
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
                                    @if (request()->hasAny(['search', 'kategori']))
                                        <a href="{{ route('admin.ekstrakurikuler.index') }}"
                                            class="btn btn-secondary w-100">
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
                            <div class="number">{{ $ekstrakurikuler->total() }}</div>
                            <div class="label">Total Ekstrakurikuler</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $ekstrakurikuler->where('is_active', true)->count() }}</div>
                            <div class="label">Aktif</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $kategoriList->count() }}</div>
                            <div class="label">Total Kategori</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">{{ $ekstrakurikuler->sum('pendaftaran_count') }}</div>
                            <div class="label">Total Anggota</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ekstrakurikuler Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-star me-2"></i>Daftar Ekstrakurikuler</h5>
                    </div>
                    <div class="card-body">
                        @if ($ekstrakurikuler->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Kategori</th>
                                            <th>Pembina</th>
                                            <th>Jadwal</th>
                                            <th>Kapasitas</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ekstrakurikuler as $index => $item)
                                            <tr>
                                                <td>{{ $ekstrakurikuler->firstItem() + $index }}</td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold">{{ $item->nama_ekskul }}</div>
                                                        <small
                                                            class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $item->kategori }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $item->pembina->name }}&background=059669&color=ffffff&size=32"
                                                            class="rounded-circle me-2" width="32" height="32">
                                                        <div>
                                                            <div class="fw-bold">{{ $item->pembina->name }}</div>
                                                            <small class="text-muted">{{ $item->pembina->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold">{{ $item->hari }}</div>
                                                        <small class="text-muted">
                                                            {{ date('H:i', strtotime($item->jam_mulai)) }} -
                                                            {{ date('H:i', strtotime($item->jam_selesai)) }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="fw-bold">
                                                            {{ $item->pendaftaran_count }}/{{ $item->kapasitas_maksimal }}
                                                        </div>
                                                        <div class="progress mt-1" style="height: 6px;">
                                                            @php
                                                                $percentage =
                                                                    $item->kapasitas_maksimal > 0
                                                                        ? ($item->pendaftaran_count /
                                                                                $item->kapasitas_maksimal) *
                                                                            100
                                                                        : 0;
                                                            @endphp
                                                            <div class="progress-bar bg-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}"
                                                                style="width: {{ min(100, $percentage) }}%"></div>
                                                        </div>
                                                        @if ($percentage >= 100)
                                                            <small class="text-danger">Penuh</small>
                                                        @elseif($percentage >= 90)
                                                            <small class="text-warning">Hampir Penuh</small>
                                                        @else
                                                            <small class="text-success">Tersedia</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="status{{ $item->id }}"
                                                            {{ $item->is_active ? 'checked' : '' }}
                                                            onchange="toggleStatus({{ $item->id }})">
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.ekstrakurikuler.show', $item) }}"
                                                            class="btn btn-outline-info" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.ekstrakurikuler.edit', $item) }}"
                                                            class="btn btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="deleteEkstrakurikuler({{ $item->id }}, '{{ $item->nama_ekskul }}')"
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

                            {{ $ekstrakurikuler->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Ekstrakurikuler</h5>
                                <p class="text-muted">Tambahkan ekstrakurikuler untuk mulai mengelola kegiatan siswa</p>
                                <a href="{{ route('admin.ekstrakurikuler.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Ekstrakurikuler Pertama
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
                    <p>Apakah Anda yakin ingin menghapus ekstrakurikuler <strong id="ekstrakurikulerName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data pendaftaran dan anggota yang terkait akan ikut terhapus!
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

@push('scripts')
    <script>
        function toggleStatus(id) {
            fetch(`/admin/ekstrakurikuler/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Revert checkbox if failed
                        document.getElementById(`status${id}`).checked = !document.getElementById(`status${id}`)
                            .checked;
                        alert('Gagal mengubah status ekstrakurikuler');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        function deleteEkstrakurikuler(id, name) {
            document.getElementById('ekstrakurikulerName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/ekstrakurikuler/${id}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endpush
