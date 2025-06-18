@extends('layouts.admin')

@section('title', 'Generate Laporan Sistem')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Generate Laporan Sistem</h1>
                <p class="text-muted mb-0">Buat dan unduh berbagai laporan sistem ekstrakurikuler</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#scheduledReportsModal">
                    <i class="fas fa-clock me-1"></i>
                    Laporan Terjadwal
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card primary h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">47</div>
                            <div class="label">Laporan Dibuat</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">156</div>
                            <div class="label">Total Download</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-download icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card warning h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">12</div>
                            <div class="label">Bulan Ini</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card info h-100">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="number">5</div>
                            <div class="label">Format Tersedia</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-export icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="row">
            <!-- Laporan Data Master -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-database me-2"></i>Laporan Data Master
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Data Siswa</h6>
                                    <p class="text-muted mb-0 small">Data lengkap siswa, kelas, dan profil akademik</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="generateReport('siswa')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Data Pembina</h6>
                                    <p class="text-muted mb-0 small">Data pembina dan ekstrakurikuler yang dibina</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="generateReport('pembina')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Ekstrakurikuler</h6>
                                    <p class="text-muted mb-0 small">Data ekstrakurikuler, kapasitas, dan statistik anggota
                                    </p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="generateReport('ekstrakurikuler')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Aktivitas -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>Laporan Aktivitas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Pendaftaran</h6>
                                    <p class="text-muted mb-0 small">Data pendaftaran siswa per periode</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="generateReport('pendaftaran')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Kehadiran</h6>
                                    <p class="text-muted mb-0 small">Statistik kehadiran per ekstrakurikuler</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="generateReport('kehadiran')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Aktivitas Sistem</h6>
                                    <p class="text-muted mb-0 small">Log aktivitas pengguna dan sistem</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="generateReport('aktivitas')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Analitik -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Laporan Analitik
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Rekomendasi</h6>
                                    <p class="text-muted mb-0 small">Efektivitas sistem rekomendasi dan akurasi prediksi
                                    </p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="generateReport('rekomendasi')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Penilaian Kriteria</h6>
                                    <p class="text-muted mb-0 small">Distribusi skor dan analisis penilaian siswa</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="generateReport('penilaian')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">Laporan Tren Partisipasi</h6>
                                    <p class="text-muted mb-0 small">Analisis tren partisipasi siswa per kategori</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="generateReport('tren')">
                                        <i class="fas fa-download me-1"></i>Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Custom -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Laporan Custom
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Periode</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="date" class="form-control" id="startDate" name="start_date">
                                    <small class="text-muted">Tanggal Mulai</small>
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control" id="endDate" name="end_date">
                                    <small class="text-muted">Tanggal Selesai</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Filter Data</label>
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select" id="kelasFilter">
                                        <option value="">Semua Kelas</option>
                                        <option value="X">Kelas X</option>
                                        <option value="XI">Kelas XI</option>
                                        <option value="XII">Kelas XII</option>
                                    </select>
                                    <small class="text-muted">Filter Kelas</small>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="kategoriFilter">
                                        <option value="">Semua Kategori</option>
                                        <option value="Olahraga">Olahraga</option>
                                        <option value="Seni & Budaya">Seni & Budaya</option>
                                        <option value="Sains & Teknologi">Sains & Teknologi</option>
                                        <option value="Keagamaan">Keagamaan</option>
                                    </select>
                                    <small class="text-muted">Kategori Ekstrakurikuler</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Format Output</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="format" id="formatPDF" value="pdf"
                                    checked>
                                <label class="btn btn-outline-secondary" for="formatPDF">
                                    <i class="fas fa-file-pdf me-1"></i>PDF
                                </label>

                                <input type="radio" class="btn-check" name="format" id="formatExcel" value="excel">
                                <label class="btn btn-outline-secondary" for="formatExcel">
                                    <i class="fas fa-file-excel me-1"></i>Excel
                                </label>

                                <input type="radio" class="btn-check" name="format" id="formatCSV" value="csv">
                                <label class="btn btn-outline-secondary" for="formatCSV">
                                    <i class="fas fa-file-csv me-1"></i>CSV
                                </label>
                            </div>
                        </div>

                        <button type="button" class="btn btn-info w-100" onclick="generateCustomReport()">
                            <i class="fas fa-magic me-1"></i>Generate Laporan Custom
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Laporan Terbaru
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearReportHistory()">
                            <i class="fas fa-trash me-1"></i>Hapus Riwayat
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Laporan</th>
                                        <th>Jenis</th>
                                        <th>Format</th>
                                        <th>Dibuat</th>
                                        <th>Ukuran</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="reportsTable">
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Laporan Data Siswa - Juni 2025</div>
                                            <small class="text-muted">Periode: 01 Jun - 30 Jun 2025</small>
                                        </td>
                                        <td><span class="badge bg-primary">Data Master</span></td>
                                        <td><i class="fas fa-file-pdf text-danger me-1"></i>PDF</td>
                                        <td>18 Jun 2025, 14:30</td>
                                        <td>2.3 MB</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Laporan Aktivitas Sistem - Minggu Ini</div>
                                            <small class="text-muted">Periode: 12 Jun - 18 Jun 2025</small>
                                        </td>
                                        <td><span class="badge bg-success">Aktivitas</span></td>
                                        <td><i class="fas fa-file-excel text-success me-1"></i>Excel</td>
                                        <td>18 Jun 2025, 10:15</td>
                                        <td>1.8 MB</td>
                                        <td><span class="badge bg-success">Selesai</span></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Laporan Rekomendasi - Evaluasi Bulanan</div>
                                            <small class="text-muted">Analisis performa algoritma</small>
                                        </td>
                                        <td><span class="badge bg-warning text-dark">Analitik</span></td>
                                        <td><i class="fas fa-file-pdf text-danger me-1"></i>PDF</td>
                                        <td>17 Jun 2025, 16:45</td>
                                        <td>956 KB</td>
                                        <td><span class="badge bg-warning">Sedang Diproses</span></td>
                                        <td class="text-center">
                                            <div class="spinner-border spinner-border-sm text-warning" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="loadMoreReports()">
                                <i class="fas fa-sync-alt me-1"></i>Muat Lebih Banyak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduled Reports Modal -->
    <div class="modal fade" id="scheduledReportsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-clock me-2"></i>Laporan Terjadwal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#newScheduleModal">
                            <i class="fas fa-plus me-1"></i>Buat Jadwal Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Laporan</th>
                                    <th>Frekuensi</th>
                                    <th>Jadwal Selanjutnya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Laporan Bulanan Siswa</td>
                                    <td><span class="badge bg-info">Bulanan</span></td>
                                    <td>01 Jul 2025, 08:00</td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Laporan Mingguan Aktivitas</td>
                                    <td><span class="badge bg-secondary">Mingguan</span></td>
                                    <td>25 Jun 2025, 07:00</td>
                                    <td><span class="badge bg-success">Aktif</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                    <div id="loadingText">Membuat laporan...</div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Set default dates
        document.getElementById('startDate').value = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
            .toISOString().split('T')[0];
        document.getElementById('endDate').value = new Date().toISOString().split('T')[0];

        function showLoading(text = 'Membuat laporan...') {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('progressBar').style.width = '0%';
            const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
            modal.show();

            // Simulate progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 20;
                if (progress > 100) progress = 100;
                document.getElementById('progressBar').style.width = progress + '%';

                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        modal.hide();
                    }, 500);
                }
            }, 500);
        }

        function generateReport(type) {
            showLoading(`Membuat laporan ${type}...`);

            // Simulate API call
            setTimeout(() => {
                // Add to recent reports table
                addToRecentReports(type);
                alert(`Laporan ${type} berhasil dibuat dan tersedia untuk diunduh!`);
            }, 3000);
        }

        function generateCustomReport() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const kelas = document.getElementById('kelasFilter').value;
            const kategori = document.getElementById('kategoriFilter').value;
            const format = document.querySelector('input[name="format"]:checked').value;

            if (!startDate || !endDate) {
                alert('Silakan pilih periode laporan terlebih dahulu');
                return;
            }

            showLoading('Membuat laporan custom...');

            setTimeout(() => {
                addToRecentReports('custom', {
                    startDate,
                    endDate,
                    kelas,
                    kategori,
                    format
                });
                alert('Laporan custom berhasil dibuat!');
            }, 4000);
        }

        function addToRecentReports(type, options = {}) {
            const table = document.getElementById('reportsTable');
            const now = new Date();
            const reportName =
                `Laporan ${type.charAt(0).toUpperCase() + type.slice(1)} - ${now.toLocaleDateString('id-ID')}`;

            const row = table.insertRow(0);
            row.innerHTML = `
                <td>
                    <div class="fw-bold">${reportName}</div>
                    <small class="text-muted">Dibuat otomatis</small>
                </td>
                <td><span class="badge bg-primary">${type.charAt(0).toUpperCase() + type.slice(1)}</span></td>
                <td><i class="fas fa-file-pdf text-danger me-1"></i>PDF</td>
                <td>${now.toLocaleDateString('id-ID')}, ${now.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}</td>
                <td>${(Math.random() * 3 + 0.5).toFixed(1)} MB</td>
                <td><span class="badge bg-success">Selesai</span></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-outline-info" title="Preview">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
        }

        function clearReportHistory() {
            if (confirm('Apakah Anda yakin ingin menghapus semua riwayat laporan?')) {
                document.getElementById('reportsTable').innerHTML = '';
                alert('Riwayat laporan berhasil dihapus');
            }
        }

        function loadMoreReports() {
            // Simulate loading more reports
            showLoading('Memuat laporan lainnya...');
        }

        // Auto refresh recent reports every 30 seconds
        setInterval(() => {
            // Check for new reports
            console.log('Checking for new reports...');
        }, 30000);
    </script>
@endpush
