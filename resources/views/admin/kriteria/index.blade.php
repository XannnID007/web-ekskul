@extends('layouts.admin')

@section('title', 'Manajemen Kriteria Penilaian')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Kriteria Penilaian</h1>
                <p class="text-muted mb-0">Kelola kriteria untuk algoritma Weighted Scoring</p>
            </div>
            <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Tambah Kriteria
            </a>
        </div>

        <!-- Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Tentang Kriteria Penilaian</h6>
                        <p class="mb-0">
                            Kriteria digunakan dalam algoritma Weighted Scoring untuk menghitung rekomendasi
                            ekstrakurikuler.
                            <strong>Total bobot semua kriteria aktif harus = 1.00</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Weight Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-balance-scale me-2"></i>Ringkasan Bobot Kriteria</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalBobot = $kriteria->where('is_active', true)->sum('bobot');
                            $sisaBobot = 1 - $totalBobot;
                        @endphp
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border-end">
                                    <div class="text-primary fw-bold fs-4">
                                        {{ $kriteria->where('is_active', true)->count() }}</div>
                                    <div class="text-muted small">Kriteria Aktif</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <div
                                        class="text-{{ $totalBobot == 1 ? 'success' : ($totalBobot < 1 ? 'warning' : 'danger') }} fw-bold fs-4">
                                        {{ number_format($totalBobot, 2) }}
                                    </div>
                                    <div class="text-muted small">Total Bobot</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <div class="text-{{ $sisaBobot == 0 ? 'success' : 'info' }} fw-bold fs-4">
                                        {{ number_format($sisaBobot, 2) }}
                                    </div>
                                    <div class="text-muted small">Sisa Bobot</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-{{ $totalBobot == 1 ? 'success' : 'warning' }} fw-bold fs-6">
                                    @if ($totalBobot == 1)
                                        <i class="fas fa-check-circle"></i> Siap Digunakan
                                    @elseif($totalBobot < 1)
                                        <i class="fas fa-exclamation-triangle"></i> Belum Lengkap
                                    @else
                                        <i class="fas fa-times-circle"></i> Melebihi Limit
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $totalBobot == 1 ? 'success' : ($totalBobot < 1 ? 'warning' : 'danger') }}"
                                    style="width: {{ min(100, $totalBobot * 100) }}%"></div>
                            </div>
                            <small class="text-muted">Target: 100% (1.00)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kriteria Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list me-2"></i>Daftar Kriteria</h5>
                    </div>
                    <div class="card-body">
                        @if ($kriteria->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kriteria</th>
                                            <th>Bobot</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kriteria as $index => $item)
                                            <tr>
                                                <td>{{ $kriteria->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->nama_kriteria }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">{{ $item->bobot }}</span>
                                                    <small
                                                        class="text-muted d-block">{{ number_format($item->bobot * 100, 1) }}%</small>
                                                </td>
                                                <td>
                                                    @if ($item->tipe == 'benefit')
                                                        <span class="badge bg-success">Benefit</span>
                                                        <small class="text-muted d-block">Semakin tinggi semakin
                                                            baik</small>
                                                    @else
                                                        <span class="badge bg-warning">Cost</span>
                                                        <small class="text-muted d-block">Semakin rendah semakin
                                                            baik</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="status{{ $item->id }}"
                                                            {{ $item->is_active ? 'checked' : '' }}
                                                            onchange="toggleStatus({{ $item->id }})">
                                                        <label class="form-check-label" for="status{{ $item->id }}">
                                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;"
                                                        title="{{ $item->deskripsi }}">
                                                        {{ $item->deskripsi ?: '-' }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.kriteria.edit', $item) }}"
                                                            class="btn btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger"
                                                            onclick="deleteKriteria({{ $item->id }}, '{{ $item->nama_kriteria }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $kriteria->links() }}
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-sliders-h fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Kriteria</h5>
                                <p class="text-muted">Tambahkan kriteria penilaian untuk mulai menggunakan sistem
                                    rekomendasi</p>
                                <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Kriteria Pertama
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
                    <p>Apakah Anda yakin ingin menghapus kriteria <strong id="kriteriaName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data penilaian yang terkait dengan kriteria ini akan ikut terhapus!
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
            fetch(`/admin/kriteria/${id}/toggle-status`, {
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
                        alert('Gagal mengubah status kriteria');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        function deleteKriteria(id, name) {
            document.getElementById('kriteriaName').textContent = name;
            document.getElementById('deleteForm').action = `/admin/kriteria/${id}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endpush
