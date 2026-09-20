<?php

use App\Http\Controllers\Admin\AbsensiAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\NilaiAdminController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Guru\AbsensiGuruController;
use App\Http\Controllers\Guru\DashboardGuruController;
use App\Http\Controllers\Guru\NilaiGuruController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\AbsensiSiswaController;
use App\Http\Controllers\Siswa\DashboardSiswaController;
use App\Http\Controllers\Siswa\NilaiSiswaController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Auth Routes
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Halaman depan (Landing Page)
Route::get('/', fn () => view('welcome'))->name('home');

// ============================================================
// Profil (semua role)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ============================================================
// Admin Routes
// ============================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/users', [UserController::class, 'store'])->name('user.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::patch('/users/{user}/toggle-aktif', [UserController::class, 'toggleAktif'])->name('user.toggle-aktif');

        // Tahun Ajaran
        Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
        Route::get('/tahun-ajaran/create', [TahunAjaranController::class, 'create'])->name('tahun-ajaran.create');
        Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
        Route::get('/tahun-ajaran/{tahunAjaran}/edit', [TahunAjaranController::class, 'edit'])->name('tahun-ajaran.edit');
        Route::put('/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
        Route::delete('/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
        Route::patch('/tahun-ajaran/{tahunAjaran}/set-aktif', [TahunAjaranController::class, 'setAktif'])->name('tahun-ajaran.set-aktif');

        // Jurusan
        Route::get('/jurusan', [KelasController::class, 'jurusanIndex'])->name('jurusan.index');
        Route::post('/jurusan', [KelasController::class, 'jurusanStore'])->name('jurusan.store');
        Route::put('/jurusan/{jurusan}', [KelasController::class, 'jurusanUpdate'])->name('jurusan.update');
        Route::delete('/jurusan/{jurusan}', [KelasController::class, 'jurusanDestroy'])->name('jurusan.destroy');

        // Kelas (menggunakan binding 'kela' karena Laravel singularize 'kelas' -> 'kela')
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kela}', [KelasController::class, 'show'])->name('kelas.show');
        Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Guru
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
        Route::get('/guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');

        // Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
        Route::patch('/siswa/{siswa}/pindah-kelas', [SiswaController::class, 'pindahKelas'])->name('siswa.pindah-kelas');

        // Mata Pelajaran
        Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran.index');
        Route::get('/mata-pelajaran/create', [MataPelajaranController::class, 'create'])->name('mata-pelajaran.create');
        Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
        Route::get('/mata-pelajaran/{mataPelajaran}/edit', [MataPelajaranController::class, 'edit'])->name('mata-pelajaran.edit');
        Route::put('/mata-pelajaran/{mataPelajaran}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
        Route::delete('/mata-pelajaran/{mataPelajaran}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');
        Route::get('/mata-pelajaran/{mataPelajaran}/bobot', [MataPelajaranController::class, 'bobotIndex'])->name('mata-pelajaran.bobot');
        Route::post('/mata-pelajaran/{mataPelajaran}/bobot', [MataPelajaranController::class, 'bobotStore'])->name('mata-pelajaran.bobot.store');

        // Jadwal Pelajaran
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/grid', [JadwalController::class, 'grid'])->name('jadwal.grid');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

        // Absensi (Admin)
        Route::get('/absensi/rekap', [AbsensiAdminController::class, 'rekap'])->name('absensi.rekap');
        Route::get('/absensi/{absensi}/detail', [AbsensiAdminController::class, 'detail'])->name('absensi.detail');
        Route::put('/absensi/{absensi}/koreksi', [AbsensiAdminController::class, 'koreksi'])->name('absensi.koreksi');

        // Nilai & Rapor (Admin)
        Route::get('/nilai/laporan', [NilaiAdminController::class, 'laporan'])->name('nilai.laporan');
        Route::post('/nilai/finalisasi', [NilaiAdminController::class, 'finalisasi'])->name('nilai.finalisasi');
        Route::get('/nilai/peringkat', [NilaiAdminController::class, 'peringkat'])->name('nilai.peringkat');
        Route::get('/nilai/rapor/siswa/{siswa}', [NilaiAdminController::class, 'exportRaporSiswa'])->name('nilai.rapor.siswa');
        Route::get('/nilai/rapor/kelas', [NilaiAdminController::class, 'exportRaporKelas'])->name('nilai.rapor.kelas');

        // ── RBAC: Manajemen Role & Permission (Dinamis) ──────────────
        Route::get('/roles', [RolePermissionController::class, 'roleIndex'])->name('roles.index');
        Route::get('/roles/create', [RolePermissionController::class, 'roleCreate'])->name('roles.create');
        Route::post('/roles', [RolePermissionController::class, 'roleStore'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RolePermissionController::class, 'roleEdit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolePermissionController::class, 'roleUpdate'])->name('roles.update');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'roleDestroy'])->name('roles.destroy');

        Route::get('/permissions', [RolePermissionController::class, 'permissionIndex'])->name('permissions.index');
        Route::get('/permissions/create', [RolePermissionController::class, 'permissionCreate'])->name('permissions.create');
        Route::post('/permissions', [RolePermissionController::class, 'permissionStore'])->name('permissions.store');
        Route::delete('/permissions/{permission}', [RolePermissionController::class, 'permissionDestroy'])->name('permissions.destroy');

        Route::get('/users/{user}/roles', [RolePermissionController::class, 'userRoles'])->name('user.roles');
        Route::put('/users/{user}/roles', [RolePermissionController::class, 'userRolesUpdate'])->name('user.roles.update');
    });

// ============================================================
// Guru Routes
// ============================================================
Route::middleware(['auth', 'role:guru,admin'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::get('/dashboard', [DashboardGuruController::class, 'index'])->name('dashboard');

        // Jadwal
        Route::get('/jadwal', [DashboardGuruController::class, 'jadwal'])->name('jadwal');

        // Absensi
        Route::get('/absensi', [AbsensiGuruController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/rekap', [AbsensiGuruController::class, 'rekap'])->name('absensi.rekap');
        Route::get('/absensi/create/{jadwal}', [AbsensiGuruController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiGuruController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/{absensi}', [AbsensiGuruController::class, 'show'])->name('absensi.show');

        // Nilai
        Route::get('/nilai', [NilaiGuruController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/kelas/{kelas}', [NilaiGuruController::class, 'kelas'])->name('nilai.kelas');
        Route::get('/nilai/edit/{siswa}', [NilaiGuruController::class, 'editNilai'])->name('nilai.edit');
        Route::post('/nilai', [NilaiGuruController::class, 'storeNilai'])->name('nilai.store');
        Route::post('/nilai/detail', [NilaiGuruController::class, 'storeDetailNilai'])->name('nilai.detail.store');
        Route::delete('/nilai/detail/{detailNilai}', [NilaiGuruController::class, 'destroyDetailNilai'])->name('nilai.detail.destroy');
        Route::get('/nilai/rapor/kelas/{kelas}', [NilaiGuruController::class, 'exportRapor'])->name('nilai.rapor.kelas');
    });

// ============================================================
// Siswa Routes
// ============================================================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/dashboard', [DashboardSiswaController::class, 'index'])->name('dashboard');

        // Jadwal
        Route::get('/jadwal', [NilaiSiswaController::class, 'jadwal'])->name('jadwal');

        // Absensi
        Route::get('/absensi', [AbsensiSiswaController::class, 'index'])->name('absensi');

        // Nilai & Rapor
        Route::get('/nilai', [NilaiSiswaController::class, 'index'])->name('nilai');
        Route::get('/nilai/rapor/download', [NilaiSiswaController::class, 'downloadRapor'])->name('rapor.download');
    });
