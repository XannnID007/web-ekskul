@extends('layouts.pembina')

@section('title', 'Kelola Pendaftaran')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Kelola Pendaftaran Ekstrakurikuler</h4>
            <p class="text-muted mb-0">Setujui atau tolak pendaftaran anggota baru</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="showBatchApprovalModal()"
                {{ $counts['pending'] == 0 ? 'disabled' : '' }}>
                <i class="fas fa-check-double me-1"></i>Setujui Massal
            </button>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-warning">{{ $counts['pending'] }}</div>
                    <div class="text-muted">Menunggu Persetujuan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-success">{{ $counts['approved'] }}</div>
                    <div class="text-muted">Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-danger">{{ $counts['rejected'] }}</div>
                    <div class="text-muted">Ditolak</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h3 text-primary">{{ array_sum($counts) }}</div>
                    <div class="text-muted">Total Pendaftaran</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pembina.pendaftaran.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Menunggu Persetujuan
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Ekstrakurikuler</label>
                        <select name="ekstrakurikuler_id" class="form-select">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach ($ekstrakurikulerList as $ekskul)
                                <option value="{{ $ekskul->id }}"
                                    {{ request('ekstrakurikuler_id') == $ekskul->id ? 'selected' : '' }}>
                                    {{ $ekskul->nama_ekskul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cari Siswa</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Nama atau NISN">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pendaftaran List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Daftar Pendaftaran
            </h5>
            @if (request('status') == 'pending' && $pendaftaran->count() > 0)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    <label class="form-check-label" for="selectAll">Pilih Semua</label>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if ($pendaftaran->count() > 0)
                @foreach ($pendaftaran as $item)
                    <div class="d-flex align-items-center p-3 border rounded mb-3 pendaftaran-item">
                        @if ($item->status == 'pending')
                            <div class="flex-shrink-0 me-3">
                                <input class="form-check-input pendaftaran-checkbox" type="checkbox"
                                    value="{{ $item->id }}" onchange="updateBatchButton()">
                            </div>
                        @endif

                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ $item->siswa->user->name }}&background=059669&color=ffffff&size=60"
                                class="rounded-circle" width="60" height="60">
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $item->siswa->user->name }}</h6>
                                    <div class="text-muted small">
                                        <i class="fas fa-id-card me-1"></i>{{ $item->siswa->nisn }}
                                        <i class="fas fa-school ms-2 me-1"></i>{{ $item->siswa->kelas }}
                                    </div>
                                </div>
                                <span
                                    class="badge bg-{{ $item->status == 'approved' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }} fs-6">
                                    @switch($item->status)
                                        @case('pending')
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        @break

                                        @case('approved')
                                            <i class="fas fa-check me-1"></i>Disetujui
                                        @break

                                        @case('rejected')
                                            <i class="fas fa-times me-1"></i>Ditolak
                                        @break
                                    @endswitch
                                </span>
                            </div>

                            <div class="mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Ekstrakurikuler:</div>
                                        <div class="fw-bold">{{ $item->ekstrakurikuler->nama_ekskul }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">Skor Rekomendasi:</div>
                                        <div class="fw-bold">{{ number_format($item->skor_rekomendasi, 1) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">Tanggal Daftar:</div>
                                        <div class="fw-bold">{{ $item->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($item->alasan_daftar)
                                <div class="mb-2">
                                    <div class="small text-muted">Alasan Mendaftar:</div>
                                    <div class="small bg-light p-2 rounded">{{ Str::limit($item->alasan_daftar, 150) }}
                                    </div>
                                </div>
                            @endif

                            @if ($item->catatan_pembina)
                                <div class="mb-2">
                                    <div class="small text-muted">Catatan Pembina:</div>
                                    <div class="small bg-light p-2 rounded">{{ $item->catatan_pembina }}</div>
                                </div>
                            @endif

                            @if ($item->tanggal_persetujuan)
                                <div class="small text-muted">
                                    {{ $item->status == 'approved' ? 'Disetujui' : 'Ditolak' }} pada:
                                    {{ $item->tanggal_persetujuan->format('d M Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-shrink-0">
                            <div class="btn-group-vertical btn-group-sm">
                                <a href="{{ route('pembina.pendaftaran.show', $item) }}" class="btn btn-outline-info"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if ($item->status == 'pending')
                                    <button type="button" class="btn btn-outline-success"
                                        onclick="approvePendaftaran({{ $item->id }}, '{{ $item->siswa->user->name }}')"
                                        title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="rejectPendaftaran({{ $item->id }}, '{{ $item->siswa->user->name }}')"
                                        title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $pendaftaran->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h5>Tidak Ada Pendaftaran</h5>
                    <p class="text-muted">Belum ada pendaftaran yang sesuai dengan filter</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Modals -->
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Setujui pendaftaran <strong id="approveStudentName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="approveCatatan" rows="3" placeholder="Selamat bergabung..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="confirmApprove()">Setujui</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tolak pendaftaran <strong id="rejectStudentName"></strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan *</label>
                        <textarea class="form-control" id="rejectCatatan" rows="3" placeholder="Berikan alasan yang jelas..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmReject()">Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Approval Modal -->
    <div class="modal fade" id="batchApprovalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Pendaftaran Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Setujui <span id="selectedCount">0</span> pendaftaran yang dipilih?</p>
                    <div class="mb-3">
                        <label class="form-label">Catatan untuk semua (opsional)</label>
                        <textarea class="form-control" id="batchCatatan" rows="3" placeholder="Selamat bergabung..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="confirmBatchApproval()">Setujui
                        Semua</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPendaftaranId = null;

        function approvePendaftaran(id, name) {
            currentPendaftaranId = id;
            document.getElementById('approveStudentName').textContent = name;

            const modal = new bootstrap.Modal(document.getElementById('approveModal'));
            modal.show();
        }

        function rejectPendaftaran(id, name) {
            currentPendaftaranId = id;
            document.getElementById('rejectStudentName').textContent = name;

            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
        }

        function confirmApprove() {
            if (!currentPendaftaranId) return;

            const catatan = document.getElementById('approveCatatan').value;

            fetch(`/pembina/pendaftaran/${currentPendaftaranId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        catatan: catatan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
                        modal.hide();

                        // Tampilkan notifikasi sukses
                        showNotification('Pendaftaran berhasil disetujui!', 'success');

                        // Refresh halaman setelah delay singkat
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('Gagal: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memproses permintaan', 'error');
                });
        }

        function confirmReject() {
            if (!currentPendaftaranId) return;

            const catatan = document.getElementById('rejectCatatan').value;
            if (!catatan.trim()) {
                showNotification('Alasan penolakan harus diisi', 'error');
                return;
            }

            fetch(`/pembina/pendaftaran/${currentPendaftaranId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        catatan: catatan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                        modal.hide();

                        // Tampilkan notifikasi sukses
                        showNotification('Pendaftaran berhasil ditolak', 'success');

                        // Refresh halaman setelah delay singkat
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('Gagal: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memproses permintaan', 'error');
                });
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.pendaftaran-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateBatchButton();
        }

        function updateBatchButton() {
            const selectedCheckboxes = document.querySelectorAll('.pendaftaran-checkbox:checked');
            const batchButton = document.querySelector('button[onclick="showBatchApprovalModal()"]');

            if (batchButton) {
                batchButton.disabled = selectedCheckboxes.length === 0;
            }

            // Update select all checkbox state
            const selectAllCheckbox = document.getElementById('selectAll');
            const allCheckboxes = document.querySelectorAll('.pendaftaran-checkbox');

            if (selectAllCheckbox && allCheckboxes.length > 0) {
                selectAllCheckbox.checked = selectedCheckboxes.length === allCheckboxes.length;
                selectAllCheckbox.indeterminate = selectedCheckboxes.length > 0 && selectedCheckboxes.length < allCheckboxes
                    .length;
            }
        }

        function showBatchApprovalModal() {
            const selectedCheckboxes = document.querySelectorAll('.pendaftaran-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                showNotification('Pilih minimal satu pendaftaran', 'error');
                return;
            }

            document.getElementById('selectedCount').textContent = selectedCheckboxes.length;

            const modal = new bootstrap.Modal(document.getElementById('batchApprovalModal'));
            modal.show();
        }

        function confirmBatchApproval() {
            const selectedCheckboxes = document.querySelectorAll('.pendaftaran-checkbox:checked');
            const pendaftaranIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            const catatan = document.getElementById('batchCatatan').value;

            if (pendaftaranIds.length === 0) {
                showNotification('Tidak ada pendaftaran yang dipilih', 'error');
                return;
            }

            // Disable button to prevent double submission
            const confirmButton = document.querySelector('#batchApprovalModal .btn-success');
            confirmButton.disabled = true;
            confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';

            fetch('/pembina/pendaftaran/batch-approve', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pendaftaran_ids: pendaftaranIds,
                        catatan: catatan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('batchApprovalModal'));
                        modal.hide();

                        // Tampilkan notifikasi sukses
                        showNotification(`${pendaftaranIds.length} pendaftaran berhasil disetujui!`, 'success');

                        // Refresh halaman setelah delay singkat
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('Gagal: ' + data.message, 'error');

                        // Re-enable button
                        confirmButton.disabled = false;
                        confirmButton.innerHTML = 'Setujui Semua';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memproses permintaan', 'error');

                    // Re-enable button
                    confirmButton.disabled = false;
                    confirmButton.innerHTML = 'Setujui Semua';
                });
        }

        // Utility function untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className =
                `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

            notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            document.body.appendChild(notification);

            // Auto remove setelah 5 detik
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Update batch button state on page load
            updateBatchButton();

            // Reset modal forms when modals are hidden
            document.getElementById('approveModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('approveCatatan').value = '';
                currentPendaftaranId = null;
            });

            document.getElementById('rejectModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('rejectCatatan').value = '';
                currentPendaftaranId = null;
            });

            document.getElementById('batchApprovalModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('batchCatatan').value = '';

                // Reset button state
                const confirmButton = document.querySelector('#batchApprovalModal .btn-success');
                confirmButton.disabled = false;
                confirmButton.innerHTML = 'Setujui Semua';
            });
        });
    </script>
@endpush
