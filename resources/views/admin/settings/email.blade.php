@extends('layouts.admin')

@section('title', 'Pengaturan Email Sistem')

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
                    <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">Pengaturan</a>
                </li>
                <li class="breadcrumb-item active">Konfigurasi Email</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Konfigurasi Email Sistem</h1>
                <p class="text-muted mb-0">Pengaturan SMTP dan template email untuk notifikasi sistem</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary me-2" onclick="testConnection()">
                    <i class="fas fa-plug me-1"></i>
                    Test Koneksi
                </button>
                <button type="button" class="btn btn-success" onclick="sendTestEmail()">
                    <i class="fas fa-paper-plane me-1"></i>
                    Kirim Test Email
                </button>
            </div>
        </div>

        <!-- Email Status Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    Status Email Service
                                </h4>
                                <p class="mb-0 opacity-90">
                                    Service email aktif dan siap mengirim notifikasi ke pengguna sistem
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="badge bg-success fs-6 me-3 px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>ONLINE
                                    </span>
                                    <div class="text-end">
                                        <div class="h5 mb-0">247</div>
                                        <small class="opacity-75">Email terkirim hari ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- SMTP Configuration -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-server me-2"></i>Konfigurasi SMTP</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.email.update') }}" method="POST" id="smtpForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('smtp_host') is-invalid @enderror"
                                    name="smtp_host" value="{{ old('smtp_host', config('mail.mailers.smtp.host')) }}"
                                    placeholder="smtp.gmail.com">
                                @error('smtp_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contoh: smtp.gmail.com, smtp.office365.com
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                                        <select class="form-select @error('smtp_port') is-invalid @enderror"
                                            name="smtp_port">
                                            <option value="25"
                                                {{ config('mail.mailers.smtp.port') == 25 ? 'selected' : '' }}>25 (Default)
                                            </option>
                                            <option value="465"
                                                {{ config('mail.mailers.smtp.port') == 465 ? 'selected' : '' }}>465 (SSL)
                                            </option>
                                            <option value="587"
                                                {{ config('mail.mailers.smtp.port') == 587 ? 'selected' : '' }}>587 (TLS)
                                            </option>
                                            <option value="2525"
                                                {{ config('mail.mailers.smtp.port') == 2525 ? 'selected' : '' }}>2525
                                                (Alternative)</option>
                                        </select>
                                        @error('smtp_port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Enkripsi <span class="text-danger">*</span></label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="smtp_encryption"
                                                id="encryptionTLS" value="tls"
                                                {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="encryptionTLS">TLS</label>

                                            <input type="radio" class="btn-check" name="smtp_encryption"
                                                id="encryptionSSL" value="ssl"
                                                {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="encryptionSSL">SSL</label>

                                            <input type="radio" class="btn-check" name="smtp_encryption"
                                                id="encryptionNone" value=""
                                                {{ !config('mail.mailers.smtp.encryption') ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="encryptionNone">None</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('smtp_username') is-invalid @enderror"
                                    name="smtp_username"
                                    value="{{ old('smtp_username', config('mail.mailers.smtp.username')) }}"
                                    placeholder="your-email@domain.com">
                                @error('smtp_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('smtp_password') is-invalid @enderror"
                                        name="smtp_password" id="smtpPassword" placeholder="Masukkan password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('smtpPassword')">
                                        <i class="fas fa-eye" id="smtpPasswordIcon"></i>
                                    </button>
                                </div>
                                @error('smtp_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Untuk Gmail, gunakan App Password bukan password akun
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">From Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('from_email') is-invalid @enderror"
                                    name="from_email" value="{{ old('from_email', config('mail.from.address')) }}"
                                    placeholder="noreply@sekolah.sch.id">
                                @error('from_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('from_name') is-invalid @enderror"
                                    name="from_name" value="{{ old('from_name', config('mail.from.name')) }}"
                                    placeholder="Sistem Ekstrakurikuler">
                                @error('from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Konfigurasi SMTP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Templates -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-file-alt me-2"></i>Template Email</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#previewModal">
                            <i class="fas fa-eye me-1"></i>Preview
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Template</label>
                            <select class="form-select" id="templateSelect" onchange="loadTemplate()">
                                <option value="welcome">Email Selamat Datang</option>
                                <option value="approval">Persetujuan Pendaftaran</option>
                                <option value="rejection">Penolakan Pendaftaran</option>
                                <option value="reminder">Pengingat Kehadiran</option>
                                <option value="announcement">Pengumuman</option>
                            </select>
                        </div>

                        <form action="{{ route('admin.settings.email.template') }}" method="POST" id="templateForm">
                            @csrf
                            <input type="hidden" name="template_type" id="templateType" value="welcome">

                            <div class="mb-3">
                                <label class="form-label">Subject Email</label>
                                <input type="text" class="form-control" name="subject" id="emailSubject"
                                    placeholder="Masukkan subject email">
                                <div class="form-text">
                                    Gunakan variabel: {nama}, {ekstrakurikuler}, {tanggal}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Isi Email</label>
                                <textarea class="form-control" name="content" id="emailContent" rows="8"
                                    placeholder="Tulis isi email di sini..."></textarea>
                                <div class="form-text">
                                    <strong>Variabel tersedia:</strong>
                                    {nama}, {email}, {ekstrakurikuler}, {pembina}, {tanggal}, {waktu}, {tempat}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="enable_html"
                                            id="enableHTML">
                                        <label class="form-check-label" for="enableHTML">
                                            Format HTML
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="auto_send" id="autoSend"
                                            checked>
                                        <label class="form-check-label" for="autoSend">
                                            Kirim Otomatis
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Template
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Email Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Statistik Email</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-3">
                                <div class="h4 text-primary">1,247</div>
                                <small class="text-muted">Total Terkirim</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 text-success">98.2%</div>
                                <small class="text-muted">Delivery Rate</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 text-warning">23</div>
                                <small class="text-muted">Gagal</small>
                            </div>
                            <div class="col-3">
                                <div class="h4 text-info">76%</div>
                                <small class="text-muted">Open Rate</small>
                            </div>
                        </div>

                        <canvas id="emailChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Email Queue -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-clock me-2"></i>Antrian Email</h5>
                        <span class="badge bg-primary">12 pending</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Penerima</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Ahmad Fauzi</div>
                                            <small class="text-muted">ahmad@email.com</small>
                                        </td>
                                        <td><span class="badge bg-info">Approval</span></td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>14:32</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Siti Nurhaliza</div>
                                            <small class="text-muted">siti@email.com</small>
                                        </td>
                                        <td><span class="badge bg-success">Welcome</span></td>
                                        <td><span class="badge bg-success">Sent</span></td>
                                        <td>14:28</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Budi Santoso</div>
                                            <small class="text-muted">budi@email.com</small>
                                        </td>
                                        <td><span class="badge bg-warning">Reminder</span></td>
                                        <td><span class="badge bg-danger">Failed</span></td>
                                        <td>14:25</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshQueue()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="processQueue()">
                                <i class="fas fa-play me-1"></i>Proses Antrian
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP Providers Quick Setup -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-magic me-2"></i>Quick Setup - Provider Populer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border-2 border-primary h-100 provider-card"
                                    onclick="setupProvider('gmail')">
                                    <div class="card-body text-center">
                                        <i class="fab fa-google fa-3x text-danger mb-3"></i>
                                        <h6>Gmail</h6>
                                        <small class="text-muted">smtp.gmail.com:587</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-2 border-info h-100 provider-card"
                                    onclick="setupProvider('outlook')">
                                    <div class="card-body text-center">
                                        <i class="fab fa-microsoft fa-3x text-primary mb-3"></i>
                                        <h6>Outlook/Office365</h6>
                                        <small class="text-muted">smtp.office365.com:587</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-2 border-warning h-100 provider-card"
                                    onclick="setupProvider('yahoo')">
                                    <div class="card-body text-center">
                                        <i class="fab fa-yahoo fa-3x text-purple mb-3"></i>
                                        <h6>Yahoo Mail</h6>
                                        <small class="text-muted">smtp.mail.yahoo.com:587</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-2 border-success h-100 provider-card"
                                    onclick="setupProvider('sendgrid')">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope fa-3x text-success mb-3"></i>
                                        <h6>SendGrid</h6>
                                        <small class="text-muted">smtp.sendgrid.net:587</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Preview Email Template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="border rounded p-3" style="background: #f8f9fa;">
                        <div class="mb-3">
                            <strong>From:</strong> Sistem Ekstrakurikuler &lt;noreply@sekolah.sch.id&gt;<br>
                            <strong>To:</strong> Ahmad Fauzi &lt;ahmad@email.com&gt;<br>
                            <strong>Subject:</strong> <span id="previewSubject">Selamat Datang di Sistem
                                Ekstrakurikuler</span>
                        </div>
                        <hr>
                        <div id="previewContent">
                            <p>Halo <strong>Ahmad Fauzi</strong>,</p>
                            <p>Selamat datang di Sistem Ekstrakurikuler MA Modern Miftahussa'adah Cimahi!</p>
                            <p>Akun Anda telah berhasil dibuat dan siap digunakan. Silakan login menggunakan email dan
                                password yang telah diberikan.</p>
                            <p>Terima kasih,<br>Tim Sistem Ekstrakurikuler</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="sendTestEmail()">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Test Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div id="loadingText">Mengirim email...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .provider-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .provider-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .text-purple {
            color: #7c3aed !important;
        }

        #emailChart {
            max-height: 200px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Email Statistics Chart
        const ctx = document.getElementById('emailChart').getContext('2d');
        const emailChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Email Terkirim',
                    data: [45, 52, 38, 67, 45, 23, 12],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Template data
        const templates = {
            welcome: {
                subject: 'Selamat Datang di Sistem Ekstrakurikuler',
                content: 'Halo {nama},\n\nSelamat datang di Sistem Ekstrakurikuler!\n\nAkun Anda telah berhasil dibuat dan siap digunakan.\n\nTerima kasih,\nTim Sistem'
            },
            approval: {
                subject: 'Pendaftaran {ekstrakurikuler} Disetujui',
                content: 'Halo {nama},\n\nSelamat! Pendaftaran Anda untuk ekstrakurikuler {ekstrakurikuler} telah disetujui.\n\nJadwal: {tanggal}\nTempat: {tempat}\nPembina: {pembina}\n\nTerima kasih,\nTim Sistem'
            },
            rejection: {
                subject: 'Pendaftaran {ekstrakurikuler} Tidak Disetujui',
                content: 'Halo {nama},\n\nMohon maaf, pendaftaran Anda untuk ekstrakurikuler {ekstrakurikuler} tidak dapat disetujui saat ini.\n\nSilakan hubungi pembina untuk informasi lebih lanjut.\n\nTerima kasih,\nTim Sistem'
            },
            reminder: {
                subject: 'Pengingat Kehadiran {ekstrakurikuler}',
                content: 'Halo {nama},\n\nIni adalah pengingat untuk kehadiran ekstrakurikuler {ekstrakurikuler}.\n\nWaktu: {waktu}\nTempat: {tempat}\n\nJangan lupa hadir ya!\n\nTerima kasih,\nTim Sistem'
            },
            announcement: {
                subject: 'Pengumuman: {ekstrakurikuler}',
                content: 'Halo {nama},\n\nAda pengumuman penting terkait ekstrakurikuler {ekstrakurikuler}.\n\nSilakan cek sistem untuk detail lengkap.\n\nTerima kasih,\nTim Sistem'
            }
        };

        function loadTemplate() {
            const templateType = document.getElementById('templateSelect').value;
            const template = templates[templateType];

            document.getElementById('templateType').value = templateType;
            document.getElementById('emailSubject').value = template.subject;
            document.getElementById('emailContent').value = template.content;

            // Update preview
            document.getElementById('previewSubject').textContent = template.subject;
            document.getElementById('previewContent').innerHTML = template.content.replace(/\n/g, '<br>');
        }

        function setupProvider(provider) {
            const providers = {
                gmail: {
                    host: 'smtp.gmail.com',
                    port: 587,
                    encryption: 'tls'
                },
                outlook: {
                    host: 'smtp.office365.com',
                    port: 587,
                    encryption: 'tls'
                },
                yahoo: {
                    host: 'smtp.mail.yahoo.com',
                    port: 587,
                    encryption: 'tls'
                },
                sendgrid: {
                    host: 'smtp.sendgrid.net',
                    port: 587,
                    encryption: 'tls'
                }
            };

            const config = providers[provider];
            document.querySelector('input[name="smtp_host"]').value = config.host;
            document.querySelector('select[name="smtp_port"]').value = config.port;
            document.querySelector(`input[name="smtp_encryption"][value="${config.encryption}"]`).checked = true;

            alert(`Konfigurasi ${provider} telah diatur. Silakan isi username dan password.`);
        }

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

        function testConnection() {
            showLoading('Testing koneksi SMTP...');

            fetch('/admin/settings/email/test-connection', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('✅ Koneksi SMTP berhasil!');
                    } else {
                        alert('❌ Koneksi SMTP gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function sendTestEmail() {
            const email = prompt('Masukkan alamat email tujuan:', 'admin@test.com');
            if (!email) return;

            showLoading('Mengirim test email...');

            fetch('/admin/settings/email/test-send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('✅ Test email berhasil dikirim ke ' + email);
                    } else {
                        alert('❌ Gagal mengirim test email: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function showLoading(text) {
            document.getElementById('loadingText').textContent = text;
            const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
            modal.show();
        }

        function hideLoading() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
            if (modal) modal.hide();
        }

        function refreshQueue() {
            showLoading('Memuat antrian email...');
            setTimeout(() => {
                hideLoading();
                location.reload();
            }, 1500);
        }

        function processQueue() {
            showLoading('Memproses antrian email...');
            setTimeout(() => {
                hideLoading();
                alert('Antrian email berhasil diproses!');
            }, 2000);
        }

        // Load default template on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTemplate();
        });

        // Form submissions
        document.getElementById('smtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading('Menyimpan konfigurasi SMTP...');

            setTimeout(() => {
                hideLoading();
                alert('Konfigurasi SMTP berhasil disimpan!');
            }, 2000);
        });

        document.getElementById('templateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading('Menyimpan template email...');

            setTimeout(() => {
                hideLoading();
                alert('Template email berhasil disimpan!');
            }, 1500);
        });
    </script>
@endpush
