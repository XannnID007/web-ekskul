<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(auth()->user()->role . '.dashboard')
        : redirect()->route('login');
});

// AUTHENTICATION
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// 👨‍💼 ADMIN ROUTES - SYSTEM MANAGER ONLY
// ============================================
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Sistem
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // KELOLA DATA MASTER (Admin Core Responsibility)
    Route::resource('siswa', App\Http\Controllers\Admin\SiswaController::class);
    Route::resource('pembina', App\Http\Controllers\Admin\AdminPembinaController::class);
    Route::resource('ekstrakurikuler', App\Http\Controllers\Admin\EkstrakurikulerController::class);
    Route::resource('kriteria', App\Http\Controllers\Admin\KriteriaController::class);

    // KELOLA PENILAIAN SISWA (Admin Core Responsibility)
    Route::get('penilaian', [App\Http\Controllers\Admin\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/siswa/{siswa}', [App\Http\Controllers\Admin\PenilaianController::class, 'editSiswa'])->name('penilaian.siswa');
    Route::put('penilaian/siswa/{siswa}', [App\Http\Controllers\Admin\PenilaianController::class, 'updateSiswa'])->name('penilaian.siswa.update');
    Route::post('penilaian/batch', [App\Http\Controllers\Admin\PenilaianController::class, 'batchUpdate'])->name('penilaian.batch');

    // SISTEM REKOMENDASI (Admin Monitoring)
    Route::get('rekomendasi', [App\Http\Controllers\Admin\RekomendasiController::class, 'index'])->name('rekomendasi.index');

    // MONITORING SISTEM (Admin Core Responsibility)
    Route::get('monitor/aktivitas', [App\Http\Controllers\Admin\MonitorController::class, 'aktivitas'])->name('monitor.aktivitas');
    Route::get('monitor/pendaftaran', [App\Http\Controllers\Admin\MonitorController::class, 'pendaftaran'])->name('monitor.pendaftaran');
    Route::get('monitor/kehadiran', [App\Http\Controllers\Admin\MonitorController::class, 'kehadiran'])->name('monitor.kehadiran');

    // LAPORAN SISTEM (Admin Core Responsibility)
    Route::get('laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/siswa', [App\Http\Controllers\Admin\LaporanController::class, 'siswa'])->name('laporan.siswa');
    Route::get('laporan/ekstrakurikuler', [App\Http\Controllers\Admin\LaporanController::class, 'ekstrakurikuler'])->name('laporan.ekstrakurikuler');
    Route::get('laporan/rekomendasi', [App\Http\Controllers\Admin\LaporanController::class, 'rekomendasi'])->name('laporan.rekomendasi');
    Route::post('laporan/export', [App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');

    // PENGATURAN SISTEM (Admin Core Responsibility)
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

// ============================================
// 👨‍🏫 PEMBINA ROUTES - ACTIVITY MANAGER ONLY
// ============================================
Route::middleware(['auth', 'check.role:pembina'])->prefix('pembina')->name('pembina.')->group(function () {
    // Dashboard Kegiatan (Pembina Focus)
    Route::get('/', [App\Http\Controllers\Pembina\DashboardController::class, 'index'])->name('dashboard');

    // KELOLA ANGGOTA (Pembina Core Responsibility)
    Route::get('anggota', [App\Http\Controllers\Pembina\AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('anggota/{pendaftaran}', [App\Http\Controllers\Pembina\AnggotaController::class, 'show'])->name('anggota.show');
    Route::delete('anggota/{pendaftaran}', [App\Http\Controllers\Pembina\AnggotaController::class, 'removeAnggota'])->name('anggota.remove');

    // KELOLA PENDAFTARAN (Pembina Core Responsibility)
    Route::get('pendaftaran', [App\Http\Controllers\Pembina\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('pendaftaran/{pendaftaran}', [App\Http\Controllers\Pembina\PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('pendaftaran/{pendaftaran}/approve', [App\Http\Controllers\Pembina\PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
    Route::post('pendaftaran/{pendaftaran}/reject', [App\Http\Controllers\Pembina\PendaftaranController::class, 'reject'])->name('pendaftaran.reject');
    Route::post('pendaftaran/batch-approve', [App\Http\Controllers\Pembina\PendaftaranController::class, 'batchApprove'])->name('pendaftaran.batch-approve');

    // INPUT KEHADIRAN (Pembina Core Responsibility)
    Route::get('kehadiran', [App\Http\Controllers\Pembina\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('kehadiran', [App\Http\Controllers\Pembina\KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::get('kehadiran/laporan', [App\Http\Controllers\Pembina\KehadiranController::class, 'laporan'])->name('kehadiran.laporan');
    Route::post('kehadiran/export', [App\Http\Controllers\Pembina\KehadiranController::class, 'exportLaporan'])->name('kehadiran.export');

    // PENGUMUMAN KEGIATAN (Pembina Additional Feature)
    Route::resource('pengumuman', App\Http\Controllers\Pembina\PengumumanController::class)->except(['show']);
});

// ============================================
// 👨‍🎓 SISWA ROUTES - END USER ONLY
// ============================================
Route::middleware(['auth', 'check.role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard Personal (Siswa Focus)
    Route::get('/', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

    // BROWSE EKSTRAKURIKULER (Siswa Core Feature)
    Route::get('ekstrakurikuler', [App\Http\Controllers\Siswa\EkstrakurikulerController::class, 'index'])->name('ekstrakurikuler.index');
    Route::get('ekstrakurikuler/{ekstrakurikuler}', [App\Http\Controllers\Siswa\EkstrakurikulerController::class, 'detail'])->name('ekstrakurikuler.detail');

    // REKOMENDASI PERSONAL (Siswa Core Feature)
    Route::get('rekomendasi', [App\Http\Controllers\Siswa\RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::get('rekomendasi/{ekstrakurikuler}', [App\Http\Controllers\Siswa\RekomendasiController::class, 'detail'])->name('rekomendasi.detail');
    Route::post('rekomendasi/export', [App\Http\Controllers\Siswa\RekomendasiController::class, 'export'])->name('rekomendasi.export');

    // PENDAFTARAN & STATUS (Siswa Core Feature)
    Route::get('pendaftaran', [App\Http\Controllers\Siswa\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('pendaftaran/{pendaftaran}', [App\Http\Controllers\Siswa\PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('ekstrakurikuler/{ekstrakurikuler}/daftar', [App\Http\Controllers\Siswa\PendaftaranController::class, 'daftar'])->name('pendaftaran.daftar');
    Route::delete('pendaftaran/{pendaftaran}/cancel', [App\Http\Controllers\Siswa\PendaftaranController::class, 'cancel'])->name('pendaftaran.cancel');

    // MONITOR KEHADIRAN (Siswa Additional Feature)
    Route::get('kehadiran', [App\Http\Controllers\Siswa\StatusController::class, 'kehadiran'])->name('kehadiran.index');

    // PROFIL PERSONAL (Siswa Self Management)
    Route::get('profil', [App\Http\Controllers\Siswa\ProfilController::class, 'index'])->name('profil.index');
    Route::put('profil', [App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('profil.update');
});
