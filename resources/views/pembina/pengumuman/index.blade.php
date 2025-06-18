@extends('layouts.pembina')

@section('title', 'Kelola Pengumuman')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Kelola Pengumuman</h4>
            <p class="text-muted mb-0">Buat dan kelola pengumuman untuk anggota ekstrakurikuler</p>
        </div>
        <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Buat Pengumuman
        </a>
    </div>

    <!-- Pengumuman List -->
    <div class="row">
        @forelse($pengumuman as $item)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $item->judul }}</h5>
                                <span class="badge bg-info">{{ ucfirst($item->kategori) }}</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pembina.pengumuman.edit', $item) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="togglePublish({{ $item->id }}, {{ $item->is_published ? 'false' : 'true' }})">
                                            <i class="fas fa-{{ $item->is_published ? 'eye-slash' : 'eye' }} me-2"></i>
                                            {{ $item->is_published ? 'Sembunyikan' : 'Publikasikan' }}
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            onclick="deletePengumuman({{ $item->id }}, '{{ $item->judul }}')">
                                            <i class="fas fa-trash me-2"></i>Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-muted mb-3">{{ Str::limit($item->konten, 150) }}</p>

                        <div class="row text-center mb-3">
                            <div class="col-6 border-end">
                                <div class="small text-muted">Status</div>
                                <div class="fw-bold">
                                    @if ($item->is_published)
                                        <span class="text-success"><i class="fas fa-eye me-1"></i>Publikasi</span>
                                    @else
                                        <span class="text-warning"><i class="fas fa-eye-slash me-1"></i>Draft</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted">Dibuat</div>
                                <div class="fw-bold">{{ $item->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                        @if ($item->published_at)
                            <div class="text-center mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Dipublikasi: {{ $item->published_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('pembina.pengumuman.edit', $item) }}"
                                class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>

                            @if ($item->is_published)
                                <button class="btn btn-outline-warning btn-sm flex-fill"
                                    onclick="togglePublish({{ $item->id }}, false)">
                                    <i class="fas fa-eye-slash me-1"></i>Sembunyikan
                                </button>
                            @else
                                <button class="btn btn-outline-success btn-sm flex-fill"
                                    onclick="togglePublish({{ $item->id }}, true)">
                                    <i class="fas fa-eye me-1"></i>Publikasikan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
                        <h5>Belum Ada Pengumuman</h5>
                        <p class="text-muted">Buat pengumuman pertama Anda untuk berkomunikasi dengan anggota</p>
                        <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Buat Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($pengumuman->hasPages())
        <div class="d-flex justify-content-center">
            {{ $pengumuman->links() }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengumuman <strong id="pengumumanTitle"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pengumuman yang dihapus tidak dapat dikembalikan.
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
        function togglePublish(id, publish) {
            const action = publish ? 'publikasikan' : 'sembunyikan';

            if (confirm(`Apakah Anda yakin ingin ${action} pengumuman ini?`)) {
                // You would implement this route in your controller
                fetch(`/pembina/pengumuman/${id}/toggle-publish`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            is_published: publish
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        function deletePengumuman(id, title) {
            document.getElementById('pengumumanTitle').textContent = title;
            document.getElementById('deleteForm').action = `/pembina/pengumuman/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endpush
