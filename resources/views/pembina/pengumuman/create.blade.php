@extends('layouts.pembina')

@section('title', 'Buat Pengumuman')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Buat Pengumuman Baru</h4>
            <p class="text-muted mb-0">Sampaikan informasi penting kepada anggota ekstrakurikuler</p>
        </div>
        <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-bullhorn me-2"></i>Form Pengumuman</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pembina.pengumuman.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Judul Pengumuman *</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
                                value="{{ old('judul') }}" placeholder="Masukkan judul pengumuman..." required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Gunakan judul yang jelas dan menarik perhatian</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="ekstrakurikuler"
                                    {{ old('kategori') == 'ekstrakurikuler' ? 'selected' : '' }}>
                                    Ekstrakurikuler
                                </option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pembina hanya dapat membuat pengumuman kategori ekstrakurikuler</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konten Pengumuman *</label>
                            <textarea class="form-control @error('konten') is-invalid @enderror" name="konten" rows="8"
                                placeholder="Tulis konten pengumuman di sini..." required>{{ old('konten') }}</textarea>
                            @error('konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Jelaskan informasi dengan lengkap dan jelas. Anda dapat menggunakan format paragraf untuk
                                konten yang panjang.
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4">
                            <h6>Preview Pengumuman:</h6>
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=059669&color=ffffff&size=40"
                                        class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <h6 class="mb-0" id="preview-judul">Judul akan muncul di sini</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                                            <i class="fas fa-calendar ms-2 me-1"></i>{{ now()->format('d M Y') }}
                                            <span class="badge bg-info ms-2" id="preview-kategori">Kategori</span>
                                        </small>
                                    </div>
                                </div>
                                <div id="preview-konten" class="text-muted">
                                    Konten pengumuman akan muncul di sini...
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="publish" id="publish" value="1"
                                    {{ old('publish') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="publish">
                                    <i class="fas fa-eye me-1"></i>Publikasikan Sekarang
                                </label>
                            </div>
                            <div class="form-text">
                                Jika tidak dicentang, pengumuman akan disimpan sebagai draft dan dapat dipublikasikan nanti
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Pengumuman
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips Membuat Pengumuman Efektif</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Judul yang Menarik:</strong> Gunakan judul yang singkat, jelas, dan menarik perhatian
                        </li>
                        <li class="mb-2">
                            <strong>Konten Terstruktur:</strong> Susun informasi dari yang paling penting ke detail
                            pendukung
                        </li>
                        <li class="mb-2">
                            <strong>Call to Action:</strong> Jika ada tindakan yang perlu dilakukan, sampaikan dengan jelas
                        </li>
                        <li class="mb-2">
                            <strong>Bahasa yang Ramah:</strong> Gunakan bahasa yang mudah dipahami dan bersahabat
                        </li>
                        <li>
                            <strong>Informasi Lengkap:</strong> Pastikan semua informasi penting tercakup (apa, kapan,
                            dimana, bagaimana)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Real-time preview
            const judulInput = document.querySelector('input[name="judul"]');
            const kategoriSelect = document.querySelector('select[name="kategori"]');
            const kontenTextarea = document.querySelector('textarea[name="konten"]');

            const previewJudul = document.getElementById('preview-judul');
            const previewKategori = document.getElementById('preview-kategori');
            const previewKonten = document.getElementById('preview-konten');

            judulInput.addEventListener('input', function() {
                previewJudul.textContent = this.value || 'Judul akan muncul di sini';
            });

            kategoriSelect.addEventListener('change', function() {
                previewKategori.textContent = this.value ? this.options[this.selectedIndex].text :
                    'Kategori';
            });

            kontenTextarea.addEventListener('input', function() {
                const konten = this.value || 'Konten pengumuman akan muncul di sini...';
                // Simple line break conversion for preview
                previewKonten.innerHTML = konten.replace(/\n/g, '<br>');
            });

            // Auto-save to localStorage
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                // Load saved data
                const savedValue = localStorage.getItem(`pengumuman_${input.name}`);
                if (savedValue && !input.value) {
                    input.value = savedValue;
                    input.dispatchEvent(new Event('input'));
                    input.dispatchEvent(new Event('change'));
                }

                // Save on change
                input.addEventListener('input', function() {
                    localStorage.setItem(`pengumuman_${this.name}`, this.value);
                });
            });

            // Clear localStorage on successful submit
            form.addEventListener('submit', function() {
                inputs.forEach(input => {
                    localStorage.removeItem(`pengumuman_${input.name}`);
                });
            });
        });

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua data akan hilang.')) {
                document.querySelector('form').reset();

                // Clear localStorage
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    localStorage.removeItem(`pengumuman_${input.name}`);
                });

                // Reset preview
                document.getElementById('preview-judul').textContent = 'Judul akan muncul di sini';
                document.getElementById('preview-kategori').textContent = 'Kategori';
                document.getElementById('preview-konten').textContent = 'Konten pengumuman akan muncul di sini...';
            }
        }

        // Character counter for content
        document.querySelector('textarea[name="konten"]').addEventListener('input', function() {
            const maxLength = 1000; // Set your desired max length
            const currentLength = this.value.length;

            // Create or update character counter
            let counter = document.getElementById('char-counter');
            if (!counter) {
                counter = document.createElement('div');
                counter.id = 'char-counter';
                counter.className = 'form-text text-end';
                this.parentNode.appendChild(counter);
            }

            counter.textContent = `${currentLength} karakter`;
            counter.className = `form-text text-end ${currentLength > maxLength ? 'text-danger' : ''}`;
        });
    </script>
@endpush
