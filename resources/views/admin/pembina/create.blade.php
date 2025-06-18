@extends('layouts.admin')

@section('title', 'Tambah Pembina')

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
                    <a href="{{ route('admin.pembina.index') }}" class="text-decoration-none">Kelola Pembina</a>
                </li>
                <li class="breadcrumb-item active">Tambah Pembina</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Tambah Pembina Baru</h1>
                <p class="text-muted mb-0">Tambahkan pembina ekstrakurikuler ke dalam sistem</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus me-2"></i>Form Data Pembina</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pembina.store') }}" method="POST" id="pembinaForm">
                            @csrf

                            <!-- Personal Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user me-2"></i>Informasi Personal
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}"
                                            placeholder="Masukkan nama lengkap pembina">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" placeholder="pembina@sekolah.sch.id">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Email akan digunakan untuk login ke sistem</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+62</span>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone') }}" placeholder="8xxxxxxxxxx">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NIP/NIK</label>
                                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                            name="nip" value="{{ old('nip') }}"
                                            placeholder="Nomor Induk Pegawai/NIK">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-key me-2"></i>Informasi Akun
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                id="password" placeholder="Masukkan password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="passwordIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 6 karakter</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Konfirmasi Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" id="passwordConfirmation"
                                                placeholder="Ulangi password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('passwordConfirmation')">
                                                <i class="fas fa-eye" id="passwordConfirmationIcon"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-briefcase me-2"></i>Informasi Profesi
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jabatan</label>
                                        <select class="form-select @error('jabatan') is-invalid @enderror" name="jabatan">
                                            <option value="">-- Pilih Jabatan --</option>
                                            <option value="Guru" {{ old('jabatan') == 'Guru' ? 'selected' : '' }}>Guru
                                            </option>
                                            <option value="Wali Kelas"
                                                {{ old('jabatan') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                            <option value="Guru BK" {{ old('jabatan') == 'Guru BK' ? 'selected' : '' }}>
                                                Guru BK</option>
                                            <option value="Staff TU" {{ old('jabatan') == 'Staff TU' ? 'selected' : '' }}>
                                                Staff TU</option>
                                            <option value="Pelatih Eksternal"
                                                {{ old('jabatan') == 'Pelatih Eksternal' ? 'selected' : '' }}>Pelatih
                                                Eksternal</option>
                                        </select>
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bidang Keahlian</label>
                                        <input type="text" class="form-control @error('keahlian') is-invalid @enderror"
                                            name="keahlian" value="{{ old('keahlian') }}"
                                            placeholder="Contoh: Olahraga, Seni, Sains">
                                        @error('keahlian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <select class="form-select @error('pendidikan') is-invalid @enderror"
                                            name="pendidikan">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="SMA/SMK"
                                                {{ old('pendidikan') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                            <option value="D3" {{ old('pendidikan') == 'D3' ? 'selected' : '' }}>D3
                                            </option>
                                            <option value="S1" {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>S1
                                            </option>
                                            <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2
                                            </option>
                                            <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3
                                            </option>
                                        </select>
                                        @error('pendidikan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pengalaman Mengajar</label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('pengalaman') is-invalid @enderror"
                                                name="pengalaman" value="{{ old('pengalaman') }}" min="0"
                                                max="50">
                                            <span class="input-group-text">tahun</span>
                                        </div>
                                        @error('pengalaman')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3"
                                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                    </h6>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Catatan Khusus</label>
                                        <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" rows="3"
                                            placeholder="Catatan khusus tentang pembina (opsional)">{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            Aktifkan Akun
                                        </label>
                                        <div class="form-text">Pembina dapat langsung login setelah akun dibuat</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail"
                                            {{ old('send_email', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sendEmail">
                                            Kirim Email Notifikasi
                                        </label>
                                        <div class="form-text">Kirim email berisi informasi akun ke pembina</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-eye me-2"></i>Preview Data Pembina
                                            </h6>
                                            <div class="row">
                                                <div class="col-auto">
                                                    <img id="previewAvatar"
                                                        src="https://ui-avatars.com/api/?name=Pembina&background=059669&color=ffffff&size=80"
                                                        class="rounded-circle" width="80" height="80">
                                                </div>
                                                <div class="col">
                                                    <table class="table table-borderless table-sm">
                                                        <tr>
                                                            <td width="150"><strong>Nama:</strong></td>
                                                            <td id="previewName">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Email:</strong></td>
                                                            <td id="previewEmail">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Telepon:</strong></td>
                                                            <td id="previewPhone">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Jabatan:</strong></td>
                                                            <td id="previewJabatan">-</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.pembina.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Pembina
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
            // Preview functionality
            function updatePreview() {
                const name = document.querySelector('input[name="name"]').value || 'Pembina Baru';
                const email = document.querySelector('input[name="email"]').value || '-';
                const phone = document.querySelector('input[name="phone"]').value || '-';
                const jabatan = document.querySelector('select[name="jabatan"]').value || '-';

                document.getElementById('previewName').textContent = name;
                document.getElementById('previewEmail').textContent = email;
                document.getElementById('previewPhone').textContent = phone ? '+62' + phone : '-';
                document.getElementById('previewJabatan').textContent = jabatan;

                // Update avatar
                document.getElementById('previewAvatar').src =
                    `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=059669&color=ffffff&size=80`;
            }

            // Event listeners for preview
            document.querySelector('input[name="name"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="email"]').addEventListener('input', updatePreview);
            document.querySelector('input[name="phone"]').addEventListener('input', updatePreview);
            document.querySelector('select[name="jabatan"]').addEventListener('change', updatePreview);

            // Initial preview update
            updatePreview();

            // Form validation
            document.getElementById('pembinaForm').addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]').value;
                const passwordConfirmation = document.querySelector('input[name="password_confirmation"]')
                    .value;

                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak sama!');
                    return false;
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
        });

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + 'Icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Password strength indicator
        document.querySelector('input[name="password"]').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            const strengthText = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const strengthColors = ['danger', 'warning', 'info', 'success', 'primary'];

            if (password.length > 0) {
                let indicator = this.parentNode.parentNode.querySelector('.password-strength');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.className = 'password-strength form-text';
                    this.parentNode.parentNode.appendChild(indicator);
                }
                indicator.innerHTML =
                    `<span class="text-${strengthColors[strength - 1]}">Kekuatan: ${strengthText[strength - 1] || 'Sangat Lemah'}</span>`;
            }
        });
    </script>
@endpush
