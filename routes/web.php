<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ================= CONTROLLER =================

// Admin
use App\Http\Controllers\Admin\KantorController as AdminKantorController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\BagianController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// User
use App\Http\Controllers\User\KantorController as UserKantorController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\PresensiController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\LaporanController;
use App\Http\Controllers\User\PengajuanController as UserPengajuanController;

// Pembimbing
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboardController;
use App\Http\Controllers\Pembimbing\PesertaController;
use App\Http\Controllers\Pembimbing\PengajuanController as PembimbingPengajuanController;
use App\Http\Controllers\Pembimbing\ProfileController as PembimbingProfileController;

// HOME
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin'       => redirect()->route('admin.dashboard'),
        'pembimbing' => redirect()->route('pembimbing.dashboard'),
        'user'        => redirect()->route('user.dashboard'),
        default       => abort(403),
    };
});

Route::middleware(['auth', 'role:pembimbing'])
    ->prefix('pembimbing')
    ->name('pembimbing.')
    ->group(function () {

        Route::get('/dashboard', [PembimbingDashboardController::class, 'index'])->name('dashboard');

        Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta');
        
        Route::get('/peserta/{user}', [PesertaController::class, 'show'])->name('peserta.show');

        Route::get('/pengajuan', [PembimbingPengajuanController::class, 'index'])->name('pengajuan');

        Route::get('/pengajuan/{pengajuan}', [PembimbingPengajuanController::class, 'show'])->name('pengajuan.show');

        Route::get('/profile', [PembimbingProfileController::class, 'index'])->name('profile.index');

        Route::get('/profile/edit', [PembimbingProfileController::class, 'edit'])->name('profile.edit');

        Route::put('/profile', [PembimbingProfileController::class, 'update'])->name('profile.update');

        Route::put('/profile/password', [PembimbingProfileController::class, 'updatePassword'])->name('profile.password.update');

    });

// ROLE: ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('kantors', AdminKantorController::class);
        Route::resource('pengguna', AkunController::class);
        Route::resource('bagian', BagianController::class);

        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');

        Route::patch('/pengajuan/{id}/update-status', [AdminPengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

        Route::get('/presensi', [AdminPresensiController::class, 'index'])->name('presensi.index');
        Route::get('/presensi/{user}', [AdminPresensiController::class, 'show'])->name('presensi.show');
        Route::patch('/presensi/{presensi}', [AdminPresensiController::class, 'update'])->name('presensi.update');

        Route::get('/profile', [AdminProfileController::class, 'show']) ->name('profile.show');

        Route::get('/profile/edit', [AdminProfileController::class, 'edit']) ->name('profile.edit');

        Route::put('/profile', [AdminProfileController::class, 'update']) ->name('profile.update');

    });

// ROLE: USER
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/kantors', [UserKantorController::class, 'index'])->name('kantors.index');

        // PRESENSI
        Route::get('/presensi', [PresensiController::class, 'create'])->name('presensi.create');
        Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
        Route::post('/presensi/tidak-hadir', [PresensiController::class, 'tidakHadir'])->name('presensi.tidak_hadir');

        // LAPORAN
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'exportPdf'])->name('laporan.export');

        // PROFIL
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

        // PENGAJUAN
        Route::get('/pengajuan', [UserPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [UserPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [UserPengajuanController::class, 'store'])->name('pengajuan.store');
    });


// DEFAULT AUTH
require __DIR__.'/auth.php';
