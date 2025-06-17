<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\EkstrakurikulerController;
use App\Http\Controllers\Admin\RekomendasiController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\PembinaController;

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
// 👨‍💼 ADMIN ROUTES - SYSTEM MANAGER
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Sistem
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // KELOLA DATA MASTER
    Route::resource('siswa', App\Http\Controllers\Admin\SiswaController::class);
    Route::resource('pembina', App\Http\Controllers\Admin\PembinaController::class);
    Route::resource('ekstrakurikuler', App\Http\Controllers\Admin\EkstrakurikulerController::class);
    Route::resource('kriteria', App\Http\Controllers\Admin\KriteriaController::class);

    // KELOLA PENILAIAN
    Route::get('penilaian', [App\Http\Controllers\Admin\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/siswa/{siswa}', [App\Http\Controllers\Admin\PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::put('penilaian/siswa/{siswa}', [App\Http\Controllers\Admin\PenilaianController::class, 'update'])->name('penilaian.update');

    // MONITOR SISTEM
    Route::get('monitor/pendaftaran', [App\Http\Controllers\Admin\MonitorController::class, 'pendaftaran'])->name('monitor.pendaftaran');
    Route::get('monitor/kehadiran', [App\Http\Controllers\Admin\MonitorController::class, 'kehadiran'])->name('monitor.kehadiran');

    // LAPORAN & EXPORT
    Route::get('laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export/{type}', [App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');

    // SETTINGS
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
});

// ============================================
// 👨‍🏫 PEMBINA ROUTES - ACTIVITY MANAGER
// ============================================
Route::middleware(['auth', 'role:pembina'])->prefix('pembina')->name('pembina.')->group(function () {
    // Dashboard Kegiatan
    Route::get('/', [App\Http\Controllers\Pembina\DashboardController::class, 'index'])->name('dashboard');

    // KELOLA ANGGOTA
    Route::get('anggota', [App\Http\Controllers\Pembina\AnggotaController::class, 'index'])->name('anggota.index');

    // KELOLA PENDAFTARAN
    Route::get('pendaftaran', [App\Http\Controllers\Pembina\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('pendaftaran/{pendaftaran}/approve', [App\Http\Controllers\Pembina\PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
    Route::post('pendaftaran/{pendaftaran}/reject', [App\Http\Controllers\Pembina\PendaftaranController::class, 'reject'])->name('pendaftaran.reject');

    // INPUT KEHADIRAN
    Route::get('kehadiran', [App\Http\Controllers\Pembina\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('kehadiran', [App\Http\Controllers\Pembina\KehadiranController::class, 'store'])->name('kehadiran.store');

    // PENGUMUMAN
    Route::resource('pengumuman', App\Http\Controllers\Pembina\PengumumanController::class)->except(['show']);
});

// ============================================
// 👨‍🎓 SISWA ROUTES - END USER
// ============================================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard Personal
    Route::get('/', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

    // BROWSE EKSTRAKURIKULER
    Route::get('ekstrakurikuler', [App\Http\Controllers\Siswa\EkstrakurikulerController::class, 'index'])->name('ekstrakurikuler.index');
    Route::get('ekstrakurikuler/{ekstrakurikuler}', [App\Http\Controllers\Siswa\EkstrakurikulerController::class, 'detail'])->name('ekstrakurikuler.detail');

    // DAFTAR EKSTRAKURIKULER
    Route::post('ekstrakurikuler/{ekstrakurikuler}/daftar', [App\Http\Controllers\Siswa\PendaftaranController::class, 'daftar'])->name('pendaftaran.daftar');

    // REKOMENDASI PERSONAL
    Route::get('rekomendasi', [App\Http\Controllers\Siswa\RekomendasiController::class, 'index'])->name('rekomendasi.index');

    // STATUS & MONITORING
    Route::get('status', [App\Http\Controllers\Siswa\StatusController::class, 'index'])->name('status.index');

    // PROFIL
    Route::get('profil', [App\Http\Controllers\Siswa\ProfilController::class, 'index'])->name('profil.index');
    Route::put('profil', [App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('profil.update');
});
