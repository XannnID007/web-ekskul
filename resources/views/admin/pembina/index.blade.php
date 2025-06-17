@extends('layouts.admin')

@section('title', 'Kelola Pembina')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Kelola Pembina</h1>
                <p class="text-muted mb-0">Manajemen data pembina ekstrakurikuler</p>
            </div>
            <a href="{{ route('admin.pembina.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Pembina
            </a>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.pembina.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Cari Pembina</label>
                                    <input type="text" class="form-control" name="search"
                                        value="{{ request('search') }}" placeholder="Nama, email, atau nomor telepon">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i>
                                        Cari
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    @if (request('search'))
                                        <a href="{{ route('admin.pembina.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-times me-1"></i>
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembina Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chalkboard-teacher me-2"></i>Daftar Pembina</h5>
                    </div>
                    <div class="card-body">
                        @if ($pembina->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pembina</th>
                                            <th>Kontak</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Bergabung</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembina as $index => $item)
                                            <tr>
                                                <td>{{ $pembina->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ $item->name }}&background=059669&color=ffffff&size=40"
                                                            class="rounded-circle me-3" width="40" height="40">
                                                        <div>
                                                            <div class="fw-bold">{{ $item->name }}</div>
                                                            <small class="text-muted">{{ $item->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <i class="fas fa-envelope me-1"></i>{{ $item->email }}
                                                    </div>
                                                    @if ($item->phone)
                                                        <div>
                                                            <i class="fas fa-phone me-1"></i>{{ $item->phone }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">
                                                        {{ $item->ekstrakurikuler_pembina_count }} Ekstrakurikuler
                                                    </span>
                                                </td>
                                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.pembina.show', $item) }}"
                                                            class="btn btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.pembina.edit', $item) }}"
                                                            class="btn btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="deletePembina({{ $item->id }}, '{{ $item->name }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $pembina->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Pembina</h5>
                                <p class="text-muted">Tambahkan pembina untuk mulai mengelola ekstrakurikuler</p>
                                <a href="{{ route('admin.pembina.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Pembina Pertama
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
                    <p>Apakah Anda yakin ingin menghapus pembina <strong id="pembinaName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pembina yang masih aktif membina ekstrakurikuler tidak dapat dihapus!
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
        function deletePembina(id, name) {
            document.getElementById('pembinaName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/pembina/${id}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endpush
