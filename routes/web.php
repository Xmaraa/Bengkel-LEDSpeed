<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Middleware\EnsurePhone;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'products'])->name('produk');

// Tamu
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.proses');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.proses');
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// Pelanggan (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::get('/lengkapi-data', [AuthController::class, 'showPhone'])->name('phone.show');
    Route::post('/lengkapi-data', [AuthController::class, 'savePhone'])->name('phone.simpan');

    Route::middleware(EnsurePhone::class)->group(function () {
        Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.create');
        Route::get('/reservasi/slots', [ReservationController::class, 'slots'])->name('reservasi.slots');
        Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');

        Route::get('/riwayat', [ReservationController::class, 'index'])->name('riwayat');
        Route::patch('/riwayat/{reservation}/batal', [ReservationController::class, 'cancel'])->name('riwayat.batal');
    });
});

// Admin
Route::middleware(['auth', EnsureRole::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservasi', [AdminReservationController::class, 'index'])->name('reservasi');
    Route::patch('/reservasi/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservasi.status');
    Route::delete('/reservasi/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservasi.hapus');

    Route::get('/layanan', [AdminServiceController::class, 'index'])->name('layanan');
    Route::post('/layanan', [AdminServiceController::class, 'store'])->name('layanan.simpan');
    Route::get('/layanan/{service}/edit', [AdminServiceController::class, 'edit'])->name('layanan.edit');
    Route::put('/layanan/{service}', [AdminServiceController::class, 'update'])->name('layanan.update');
    Route::patch('/layanan/{service}', [AdminServiceController::class, 'toggle'])->name('layanan.toggle');
    Route::delete('/layanan/{service}', [AdminServiceController::class, 'destroy'])->name('layanan.hapus');

    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk');
    Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.simpan');
    Route::get('/produk/{product}/edit', [AdminProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{product}', [AdminProductController::class, 'updateFull'])->name('produk.update.full');
    Route::patch('/produk/{product}', [AdminProductController::class, 'update'])->name('produk.update');
    Route::patch('/produk/{product}/status', [AdminProductController::class, 'toggle'])->name('produk.toggle');
    Route::delete('/produk/{product}', [AdminProductController::class, 'destroy'])->name('produk.hapus');
});
