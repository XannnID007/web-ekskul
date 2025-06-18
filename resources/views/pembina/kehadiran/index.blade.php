@extends('layouts.pembina')

@section('title', 'Input Kehadiran')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Input Kehadiran Anggota</h4>
            <p class="text-muted mb-0">Catat kehadiran anggota ekstrakurikuler</p>
        </div>
        <a href="{{ route('pembina.kehadiran.laporan') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-line me-1"></i>Laporan Kehadiran
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pembina.kehadiran.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Ekstrakurikuler *</label>
                        <select name="ekstrakurikuler_id" class="form-select" onchange="this.form.submit()" required>
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
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Pertemuan *</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $tanggalDipilih }}"
                                onchange="this.form.submit()" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-calendar-check me-1"></i>Tampilkan
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success w-100" onclick="setToday()">
                                <i class="fas fa-calendar-day me-1"></i>Hari Ini
                            </button>
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
                    <div class="col-md-8">
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
                    <div class="col-md-4 text-center">
                        <div class="h4 text-success">{{ $anggota->count() }}</div>
                        <div class="text-muted">Total Anggota</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Form -->
        @if ($anggota->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Daftar Kehadiran -
                        {{ \Carbon\Carbon::parse($tanggalDipilih)->format('d M Y') }}
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success" onclick="markAll('hadir')">
                            <i class="fas fa-check-circle me-1"></i>Hadir Semua
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="markAll('tidak_hadir')">
                            <i class="fas fa-times-circle me-1"></i>Alfa Semua
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pembina.kehadiran.store') }}" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="ekstrakurikuler_id" value="{{ $selectedEkskul->id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th width="150">Status Kehadiran</th>
                                        <th width="200">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anggota as $index => $member)
                                        @php
                                            $existingKehadiran = $member->kehadiran->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ $member->siswa->user->name }}&background=059669&color=ffffff&size=40"
                                                        class="rounded-circle me-3" width="40" height="40">
                                                    <div>
                                                        <h6 class="mb-0">{{ $member->siswa->user->name }}</h6>
                                                        <small class="text-muted">{{ $member->siswa->nisn }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $member->siswa->kelas }}</td>
                                            <td>
                                                <select class="form-select form-select-sm attendance-select"
                                                    name="kehadiran[{{ $member->id }}]" required>
                                                    <option value="hadir"
                                                        {{ $existingKehadiran && $existingKehadiran->status == 'hadir' ? 'selected' : '' }}>
                                                        Hadir
                                                    </option>
                                                    <option value="tidak_hadir"
                                                        {{ $existingKehadiran && $existingKehadiran->status == 'tidak_hadir' ? 'selected' : '' }}>
                                                        Tidak Hadir (Alfa)
                                                    </option>
                                                    <option value="izin"
                                                        {{ $existingKehadiran && $existingKehadiran->status == 'izin' ? 'selected' : '' }}>
                                                        Izin
                                                    </option>
                                                    <option value="sakit"
                                                        {{ $existingKehadiran && $existingKehadiran->status == 'sakit' ? 'selected' : '' }}>
                                                        Sakit
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="keterangan[{{ $member->id }}]"
                                                    value="{{ $existingKehadiran ? $existingKehadiran->keterangan : '' }}"
                                                    placeholder="Keterangan (opsional)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Simpan Kehadiran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5>Belum Ada Anggota</h5>
                    <p class="text-muted">Ekstrakurikuler ini belum memiliki anggota aktif</p>
                    <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Kelola Pendaftaran
                    </a>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-check fa-4x text-muted mb-3"></i>
                <h5>Pilih Ekstrakurikuler</h5>
                <p class="text-muted">Pilih ekstrakurikuler untuk mulai mencatat kehadiran</p>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function setToday() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="tanggal"]').value = today;
            document.querySelector('form').submit();
        }

        function markAll(status) {
            const selects = document.querySelectorAll('.attendance-select');
            selects.forEach(select => {
                select.value = status;
            });
        }

        // Auto-save functionality (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('attendanceForm');
            if (form) {
                // Add auto-save every 30 seconds
                setInterval(function() {
                    if (hasUnsavedChanges()) {
                        saveProgress();
                    }
                }, 30000);
            }
        });

        function hasUnsavedChanges() {
            // Check if there are any changes that need to be saved
            return true; // Simplified for demo
        }

        function saveProgress() {
            // Save form data to localStorage for recovery
            const formData = new FormData(document.getElementById('attendanceForm'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem('attendance_draft', JSON.stringify(data));
        }

        // Confirmation before leaving page with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
@endpush
