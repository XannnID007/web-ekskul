@extends('layouts.pembina')

@section('title', 'Kelola Anggota')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Kelola Anggota Ekstrakurikuler</h4>
            <p class="text-muted mb-0">Pantau dan kelola anggota aktif ekstrakurikuler Anda</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pembina.anggota.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Ekstrakurikuler</label>
                        <select name="ekstrakurikuler_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Pilih Ekstrakurikuler --</option>
                            @foreach ($ekstrakurikulerList as $ekskul)
                                <option value="{{ $ekskul->id }}"
                                    {{ request('ekstrakurikuler_id') == $ekskul->id ? 'selected' : '' }}>
                                    {{ $ekskul->nama_ekskul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($selectedEkskul)
                        <div class="col-md-4">
                            <label class="form-label">Cari Anggota</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Nama atau NISN">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Cari
                            </button>
                        </div>
                        <div class="col-md-2">
                            @if (request('search'))
                                <a href="{{ route('pembina.anggota.index', ['ekstrakurikuler_id' => request('ekstrakurikuler_id')]) }}"
                                    class="btn btn-secondary w-100">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($selectedEkskul)
        <!-- Ekstrakurikuler Info -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>{{ $selectedEkskul->nama_ekskul }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-bold">Kategori:</td>
                                <td>{{ $selectedEkskul->kategori }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jadwal:</td>
                                <td>{{ $selectedEkskul->hari }}, {{ date('H:i', strtotime($selectedEkskul->jam_mulai)) }} -
                                    {{ date('H:i', strtotime($selectedEkskul->jam_selesai)) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat:</td>
                                <td>{{ $selectedEkskul->tempat }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h4 text-success">{{ $anggota->total() }}</div>
                                <div class="text-muted small">Total Anggota</div>
                            </div>
                            <div class="col-4">
                                <div class="h4 text-primary">{{ $selectedEkskul->kapasitas_maksimal }}</div>
                                <div class="text-muted small">Kapasitas</div>
                            </div>
                            <div class="col-4">
                                <div class="h4 text-info">{{ $selectedEkskul->sisaKuota() }}</div>
                                <div class="text-muted small">Sisa Kuota</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anggota List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Daftar Anggota Aktif
                </h5>
                <span class="badge bg-success fs-6">{{ $anggota->total() }} Anggota</span>
            </div>
            <div class="card-body">
                @if ($anggota->count() > 0)
                    @foreach ($anggota as $member)
                        <div class="d-flex align-items-center p-3 border rounded mb-3">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ $member->siswa->user->name }}&background=059669&color=ffffff&size=50"
                                    class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $member->siswa->user->name }}</h6>
                                <div class="text-muted small">
                                    <i class="fas fa-id-card me-1"></i>{{ $member->siswa->nisn }}
                                    <i class="fas fa-school ms-2 me-1"></i>{{ $member->siswa->kelas }}
                                    <i class="fas fa-calendar ms-2 me-1"></i>Bergabung
                                    {{ $member->tanggal_persetujuan->format('d M Y') }}
                                </div>
                                @if ($member->kehadiran->count() > 0)
                                    <div class="mt-2">
                                        <div class="text-muted small">Kehadiran Terakhir:</div>
                                        <div class="d-flex gap-1">
                                            @foreach ($member->kehadiran->take(5) as $kehadiran)
                                                <span
                                                    class="badge bg-{{ $kehadiran->status == 'hadir' ? 'success' : ($kehadiran->status == 'izin' || $kehadiran->status == 'sakit' ? 'warning' : 'danger') }}"
                                                    title="{{ $kehadiran->tanggal->format('d M') }} - {{ ucfirst($kehadiran->status) }}">
                                                    {{ $kehadiran->tanggal->format('d/m') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pembina.anggota.show', $member) }}" class="btn btn-outline-info"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="removeAnggota({{ $member->id }}, '{{ $member->siswa->user->name }}')"
                                        title="Keluarkan">
                                        <i class="fas fa-user-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{ $anggota->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>Belum Ada Anggota</h5>
                        <p class="text-muted">Ekstrakurikuler ini belum memiliki anggota aktif</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5>Pilih Ekstrakurikuler</h5>
                <p class="text-muted">Pilih ekstrakurikuler yang ingin Anda kelola anggotanya</p>
            </div>
        </div>
    @endif

    <!-- Remove Member Modal -->
    <div class="modal fade" id="removeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Keluarkan Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengeluarkan <strong id="memberName"></strong> dari ekstrakurikuler?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anggota yang dikeluarkan tidak dapat mengikuti kegiatan lagi dan harus mendaftar ulang jika ingin
                        bergabung kembali.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRemove()">
                        Keluarkan Anggota
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let memberToRemove = null;

        function removeAnggota(id, name) {
            memberToRemove = id;
            document.getElementById('memberName').textContent = name;

            const removeModal = new bootstrap.Modal(document.getElementById('removeModal'));
            removeModal.show();
        }

        function confirmRemove() {
            if (!memberToRemove) return;

            fetch(`/pembina/anggota/${memberToRemove}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    </script>
@endpush
