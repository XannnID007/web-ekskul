@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Pengaturan Sistem</h1>
                <p class="text-muted mb-0">Konfigurasi dan pengaturan aplikasi ekstrakurikuler</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="backupSystem()">
                    <i class="fas fa-download me-1"></i>
                    Backup Data
                </button>
            </div>
        </div>

        <!-- Settings Cards -->
        <div class="row">
            <!-- General Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs me-2"></i>Pengaturan Umum</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.update') }}" method="POST" id="generalForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Aplikasi</label>
                                <input type="text" class="form-control" name="app_name"
                                    value="{{ $settings['app_name'] }}" readonly>
                                <div class="form-text">Nama sistem aplikasi</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tahun Akademik</label>
                                <input type="text" class="form-control" name="academic_year"
                                    value="{{ $settings['academic_year'] }}" placeholder="2024/2025">
                                <div class="form-text">Tahun akademik saat ini</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Maksimal Ekstrakurikuler per Siswa</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="max_ekstrakurikuler_per_siswa"
                                        value="{{ $settings['max_ekstrakurikuler_per_siswa'] }}" min="1"
                                        max="5">
                                    <span class="input-group-text">ekstrakurikuler</span>
                                </div>
                                <div class="form-text">Batas maksimal ekstrakurikuler yang dapat diikuti satu siswa</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Threshold Auto Approve</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="auto_approve_threshold"
                                        value="{{ $settings['auto_approve_threshold'] }}" min="0" max="100"
                                        step="0.1">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Skor minimum untuk otomatis menyetujui pendaftaran</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Versi Laravel:</td>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Versi PHP:</td>
                                <td>{{ phpversion() }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Environment:</td>
                                <td>
                                    <span class="badge bg-{{ config('app.env') === 'production' ? 'success' : 'warning' }}">
                                        {{ strtoupper(config('app.env')) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Debug Mode:</td>
                                <td>
                                    <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                                        {{ config('app.debug') ? 'ON' : 'OFF' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Timezone:</td>
                                <td>{{ config('app.timezone') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Database:</td>
                                <td>{{ config('database.default') }}</td>
                            </tr>
                        </table>

                        <hr>

                        <h6 class="mb-3">Storage Information</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="h5 text-primary">
                                        {{ number_format(disk_free_space('/') / 1024 / 1024 / 1024, 2) }}GB</div>
                                    <small class="text-muted">Free Space</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h5 text-info">
                                    {{ number_format(disk_total_space('/') / 1024 / 1024 / 1024, 2) }}GB</div>
                                <small class="text-muted">Total Space</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope me-2"></i>Pengaturan Email</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.email') }}" method="POST" id="emailForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" class="form-control" name="mail_host"
                                    value="{{ config('mail.mailers.smtp.host') }}" placeholder="smtp.gmail.com">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="number" class="form-control" name="mail_port"
                                            value="{{ config('mail.mailers.smtp.port') }}" placeholder="587">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Encryption</label>
                                        <select class="form-select" name="mail_encryption">
                                            <option value="tls"
                                                {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'selected' : '' }}>
                                                TLS</option>
                                            <option value="ssl"
                                                {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'selected' : '' }}>
                                                SSL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Username</label>
                                <input type="email" class="form-control" name="mail_username"
                                    value="{{ config('mail.mailers.smtp.username') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Password</label>
                                <input type="password" class="form-control" name="mail_password"
                                    placeholder="Masukkan password email">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">From Email</label>
                                <input type="email" class="form-control" name="mail_from_address"
                                    value="{{ config('mail.from.address') }}">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="testEmail()">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Test Email
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Pengaturan Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5><i class="fas fa-tools me-2"></i>Maintenance & Tools</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-warning" onclick="clearCache()">
                                <i class="fas fa-broom me-1"></i>
                                Clear Cache
                            </button>

                            <button type="button" class="btn btn-info" onclick="optimizeSystem()">
                                <i class="fas fa-magic me-1"></i>
                                Optimize System
                            </button>

                            <button type="button" class="btn btn-success" onclick="runMigrations()">
                                <i class="fas fa-database me-1"></i>
                                Run Migrations
                            </button>

                            <button type="button" class="btn btn-secondary" onclick="viewLogs()">
                                <i class="fas fa-file-alt me-1"></i>
                                View System Logs
                            </button>

                            <hr>

                            <button type="button" class="btn btn-danger" onclick="toggleMaintenance()"
                                id="maintenanceBtn">
                                <i class="fas fa-wrench me-1"></i>
                                <span id="maintenanceText">Enable Maintenance Mode</span>
                            </button>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Gunakan tools ini dengan hati-hati. Beberapa operasi dapat mempengaruhi performa sistem.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Algorithm Settings -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-calculator me-2"></i>Pengaturan Algoritma Rekomendasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Weighted Scoring Configuration</h6>
                                <form action="{{ route('admin.settings.algorithm') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Minimum Score untuk Rekomendasi</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="min_recommendation_score"
                                                value="50" min="0" max="100" step="0.1">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Weight Adjustment Factor</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="weight_adjustment"
                                                value="1.0" min="0.1" max="2.0" step="0.1">
                                            <span class="input-group-text">x</span>
                                        </div>
                                        <div class="form-text">Faktor penyesuaian bobot untuk fine-tuning</div>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="enable_context_aware"
                                            checked>
                                        <label class="form-check-label">
                                            Enable Context-Aware Recommendations
                                        </label>
                                        <div class="form-text">Mempertimbangkan faktor kontekstual seperti minat dan
                                            kapasitas</div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Algorithm Settings
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h6>Algorithm Performance</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="h4 text-success">94.2%</div>
                                                <small class="text-muted">Accuracy Rate</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="h4 text-info">127ms</div>
                                                <small class="text-muted">Avg Response Time</small>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="h5 text-primary">1,847</div>
                                                <small class="text-muted">Total Recommendations</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="h5 text-warning">76%</div>
                                                <small class="text-muted">Acceptance Rate</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <div id="loadingText">Processing...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showLoading(text = 'Processing...') {
            document.getElementById('loadingText').textContent = text;
            const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
            modal.show();
        }

        function hideLoading() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
            if (modal) modal.hide();
        }

        function clearCache() {
            showLoading('Clearing cache...');

            fetch('/admin/settings/clear-cache', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('Cache cleared successfully!');
                    } else {
                        alert('Failed to clear cache: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function optimizeSystem() {
            showLoading('Optimizing system...');

            fetch('/admin/settings/optimize', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('System optimized successfully!');
                    } else {
                        alert('Failed to optimize: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function runMigrations() {
            if (!confirm('Are you sure you want to run database migrations? This action cannot be undone.')) {
                return;
            }

            showLoading('Running migrations...');

            fetch('/admin/settings/migrate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('Migrations completed successfully!');
                    } else {
                        alert('Migration failed: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function toggleMaintenance() {
            const btn = document.getElementById('maintenanceBtn');
            const text = document.getElementById('maintenanceText');
            const isEnabled = text.textContent.includes('Enable');

            if (isEnabled) {
                if (!confirm('Enable maintenance mode? This will make the site unavailable to users.')) {
                    return;
                }
            }

            showLoading(isEnabled ? 'Enabling maintenance mode...' : 'Disabling maintenance mode...');

            fetch('/admin/settings/maintenance', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        enable: isEnabled
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        if (isEnabled) {
                            text.textContent = 'Disable Maintenance Mode';
                            btn.className = 'btn btn-success';
                        } else {
                            text.textContent = 'Enable Maintenance Mode';
                            btn.className = 'btn btn-danger';
                        }
                        alert(data.message);
                    } else {
                        alert('Failed: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function testEmail() {
            showLoading('Sending test email...');

            fetch('/admin/settings/test-email', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('Test email sent successfully!');
                    } else {
                        alert('Failed to send test email: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        }

        function backupSystem() {
            showLoading('Creating system backup...');

            fetch('/admin/settings/backup', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    hideLoading();
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Backup failed');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `backup-${new Date().toISOString().split('T')[0]}.sql`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                })
                .catch(error => {
                    hideLoading();
                    alert('Backup failed: ' + error.message);
                });
        }

        function viewLogs() {
            window.open('/admin/settings/logs', '_blank');
        }

        // Form submission handling
        document.getElementById('generalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading('Updating settings...');

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('Settings updated successfully!');
                    } else {
                        alert('Failed to update settings: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        });

        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading('Updating email settings...');

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert('Email settings updated successfully!');
                    } else {
                        alert('Failed to update email settings: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('Error: ' + error.message);
                });
        });
    </script>
@endpush
