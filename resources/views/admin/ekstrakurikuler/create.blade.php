@extends('layouts.admin')

@section('title', 'Tambah Ekstrakurikuler')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white p-3 rounded-3 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.ekstrakurikuler.index') }}" class="text-decoration-none">Kelola
                        Ekstrakurikuler</a>
                </li>
                <li class="breadcrumb-item active">Tambah Ekstrakurikuler</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Tambah Ekstrakurikuler Baru</h1>
                <p class="text-muted mb-0">Buat kegiatan ekstrakurikuler baru untuk siswa</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus me-2"></i>Form Ekstrakurikuler</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ekstrakurikuler.store') }}" method="POST" id="ekstrakurikulerForm">
                            @csrf

                            <!-- Basic Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Ekstrakurikuler <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_ekskul') is-invalid @enderror"
                                            name="nama_ekskul" value="{{ old('nama_ekskul') }}"
                                            placeholder="Contoh: Basket, Musik, Pramuka">
                                        @error('nama_ekskul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kategori') is-invalid @enderror" name="kategori"
                                            id="kategoriSelect">
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="Olahraga" {{ old('kategori') == 'Olahraga' ? 'selected' : '' }}>
                                                Olahraga</option>
                                            <option value="Seni & Budaya"
                                                {{ old('kategori') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya
                                            </option>
                                            <option value="Sains & Teknologi"
                                                {{ old('kategori') == 'Sains & Teknologi' ? 'selected' : '' }}>Sains &
                                                Teknologi</option>
                                            <option value="Keagamaan"
                                                {{ old('kategori') == 'Keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                                            <option value="Akademik" {{ old('kategori') == 'Akademik' ? 'selected' : '' }}>
                                                Akademik</option>
                                            <option value="Sosial" {{ old('kategori') == 'Sosial' ? 'selected' : '' }}>
                                                Sosial</option>
                                            <option value="custom">Kategori Lain...</option>
                                        </select>
                                        <input type="text" class="form-control mt-2 d-none" id="kategoriCustom"
                                            placeholder="Masukkan kategori baru">
                                        @error('kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4"
                                            placeholder="Jelaskan tentang ekstrakurikuler ini...">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 50 karakter</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-calendar me-2"></i>Jadwal Kegiatan
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Hari <span class="text-danger">*</span></label>
                                        <select class="form-select @error('hari') is-invalid @enderror" name="hari">
                                            <option value="">-- Pilih Hari --</option>
                                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                                <option value="{{ $hari }}"
                                                    {{ old('hari') == $hari ? 'selected' : '' }}>
                                                    {{ $hari }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('hari')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror"
                                            name="jam_mulai" value="{{ old('jam_mulai') }}" id="jamMulai">
                                        @error('jam_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time"
                                            class="form-control @error('jam_selesai') is-invalid @enderror"
                                            name="jam_selesai" value="{{ old('jam_selesai') }}" id="jamSelesai">
                                        @error('jam_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text" id="durasiInfo"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Tempat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                            name="tempat" value="{{ old('tempat') }}"
                                            placeholder="Contoh: Lapangan Basket, Ruang Musik, Aula">
                                        @error('tempat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Management Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-users-cog me-2"></i>Pengelolaan
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pembina <span class="text-danger">*</span></label>
                                        <select class="form-select @error('pembina_id') is-invalid @enderror"
                                            name="pembina_id">
                                            <option value="">-- Pilih Pembina --</option>
                                            @foreach ($pembina as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ old('pembina_id') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->name }} - {{ $p->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('pembina_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if ($pembina->count() == 0)
                                            <div class="form-text text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Belum ada pembina tersedia.
                                                <a href="{{ route('admin.pembina.create') }}" target="_blank">Tambah
                                                    pembina</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kapasitas Maksimal <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('kapasitas_maksimal') is-invalid @enderror"
                                                name="kapasitas_maksimal" value="{{ old('kapasitas_maksimal', 20) }}"
                                                min="1" max="100" id="kapasitasInput">
                                            <span class="input-group-text">orang</span>
                                        </div>
                                        @error('kapasitas_maksimal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Maksimal siswa yang dapat bergabung</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-eye me-2"></i>Preview Ekstrakurikuler
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless table-sm">
                                                        <tr>
                                                            <td width="120"><strong>Nama:</strong></td>
                                                            <td id="previewNama">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Kategori:</strong></td>
                                                            <td id="previewKategori">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Pembina:</strong></td>
                                                            <td id="previewPembina">-</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <strong>Deskripsi:</strong>
                                                    <p id="previewDeskripsi" class="text-muted mb-0">-</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Ekstrakurikuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle custom category
            document.getElementById('kategoriSelect').addEventListener('change', function() {
                const customInput = document.getElementById('kategoriCustom');
                if (this.value === 'custom') {
                    customInput.classList.remove('d-none');
                    customInput.focus();
                } else {
                    customInput.classList.add('d-none');
                }
            });

            // Update category when custom input changes
            document.getElementById('kategoriCustom').addEventListener('input', function() {
                if (this.value.trim()) {
                    document.getElementById('kategoriSelect').value = this.value;
                }
            });

            // Calculate duration
            function updateDuration() {
                const jamMulai = document.getElementById('jamMulai').value;
                const jamSelesai = document.getElementById('jamSelesai').value;

                if (jamMulai && jamSelesai) {
                    const start = new Date('2000-01-01 ' + jamMulai);
                    const end = new Date('2000-01-01 ' + jamSelesai);

                    if (end > start) {
                        const diff = (end - start) / (1000 * 60); // minutes
                        const hours = Math.floor(diff / 60);
                        const minutes = diff % 60;

                        let durationText = 'Durasi: ';
                        if (hours > 0) durationText += hours + ' jam ';
                        if (minutes > 0) durationText += minutes + ' menit';

                        document.getElementById('durasiInfo').textContent = durationText;
                        document.getElementById('durasiInfo').className = 'form-text text-success';
                    } else {
                        document.getElementById('durasiInfo').textContent =
                            'Jam selesai harus lebih besar dari jam mulai';
                        document.getElementById('durasiInfo').className = 'form-text text-danger';
                    }
                }
            }

            // Preview functionality
            function updatePreview() {
                const nama = document.querySelector('input[name="nama_ekskul"]').value || '-';
                const kategori = document.getElementById('kategoriCustom').classList.contains('d-none') ?
                    document.querySelector('select[name="kategori"]').value || '-' :
                    document.getElementById('kategoriCustom').value || '-';
                const pembina = document.querySelector('select[name="pembina_id"]');
                const pembinaText = pembina.selectedOptions[0]?.text || '-';
                const hari = document.querySelector('select[name="hari"]').value || '-';
                const jamMulai = document.querySelector('input[name="jam_mulai"]').value || '-';
                const jamSelesai = document.querySelector('input[name="jam_selesai"]').value || '-';
                const tempat = document.querySelector('input[name="tempat"]').value || '-';
                const kapasitas = document.querySelector('input[name="kapasitas_maksimal"]').value || '-';
                const deskripsi = document.querySelector('textarea[name="deskripsi"]').value || '-';

                document.getElementById('previewNama').textContent = nama;
                document.getElementById('previewKategori').textContent = kategori;
                document.getElementById('previewPembina').textContent = pembinaText;
                document.getElementById('previewJadwal').textContent =
                    hari !== '-' && jamMulai !== '-' && jamSelesai !== '-' ?
                    `${hari}, ${jamMulai} - ${jamSelesai}` :
                    '-';
                document.getElementById('previewTempat').textContent = tempat;
                document.getElementById('previewKapasitas').textContent = kapasitas !== '-' ? kapasitas + ' orang' :
                    '-';
                document.getElementById('previewDeskripsi').textContent = deskripsi;
            }

            // Event listeners
            document.querySelector('input[name="nama_ekskul"]').addEventListener('input', updatePreview);
            document.querySelector('select[name="kategori"]').addEventListener('change', updatePreview);
            document.getElementById('kategoriCustom').addEventListener('input', updatePreview);
            document.querySelector('select[name="pembina_id"]').addEventListener('change', updatePreview);
            document.querySelector('select[name="hari"]').addEventListener('change', updatePreview);
            document.querySelector('input[name="jam_mulai"]').addEventListener('change', function() {
                updateDuration();
                updatePreview();
            });
            document.querySelector('input[name="jam_selesai"]').addEventListener('change', function() {
                updateDuration();
                updatePreview();
            });
            document.querySelector('input[name="tempat"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="kapasitas_maksimal"]').addEventListener('input', updatePreview);
            document.querySelector('textarea[name="deskripsi"]').addEventListener('input', updatePreview);

            // Initial update
            updatePreview();

            // Form validation
            document.getElementById('ekstrakurikulerForm').addEventListener('submit', function(e) {
                // Handle custom category
                const kategoriSelect = document.getElementById('kategoriSelect');
                const kategoriCustom = document.getElementById('kategoriCustom');

                if (kategoriSelect.value === 'custom' && kategoriCustom.value.trim()) {
                    // Create hidden input for custom category
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'kategori';
                    hiddenInput.value = kategoriCustom.value.trim();
                    this.appendChild(hiddenInput);

                    // Remove name from select to avoid conflict
                    kategoriSelect.removeAttribute('name');
                }

                // Show loading
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Re-enable after 3 seconds if form doesn't submit
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });

            // Character counter for description
            const deskripsiTextarea = document.querySelector('textarea[name="deskripsi"]');
            const charCounter = document.createElement('div');
            charCounter.className = 'form-text text-end';
            deskripsiTextarea.parentNode.appendChild(charCounter);

            deskripsiTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = `${length} karakter`;

                if (length < 50) {
                    charCounter.className = 'form-text text-end text-danger';
                } else {
                    charCounter.className = 'form-text text-end text-success';
                }
            });

            // Trigger initial character count
            deskripsiTextarea.dispatchEvent(new Event('input'));
        });
    </script>
@endpush
