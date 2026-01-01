<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

// 1. Proses Pilih Meja
Route::get('/', [UserController::class, 'askTable']);
Route::get('/select-table', [UserController::class, 'selectTable'])->name('select.table');
Route::post('/confirm-table', [UserController::class, 'chooseTable'])->name('confirm.table');

// 2. Halaman Menu
Route::get('/menu', [UserController::class, 'menu'])->name('user.menu');
Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');
Route::post('/cart-remove', [UserController::class, 'removeFromCart'])->name('cart.remove');

// 3. Proses Checkout
Route::get('/order-detail', [UserController::class, 'orderPage'])->name('order.detail');

/* =============================================================
 * 4. FINAL ORDER & PAYMENT FLOW (ALUR OTOMATIS)
 * ============================================================= */

// Simpan data awal (Status: pending_payment)
Route::post('/payment/store', [UserController::class, 'store'])->name('payment.store');

// Halaman Pembayaran (Halaman yang ada tombol "Bayar Sekarang")
Route::get('/payment/{id}', [UserController::class, 'payment'])->name('user.payment');

// Redirect dari Midtrans ke sini kalau SUKSES
Route::get('/payment-success/{id}', [UserController::class, 'paymentSuccess'])->name('payment.success');

Route::get('/', [UserController::class, 'askTable'])->name('ask.table');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    // 🔐 AUTH
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'doLogin']);

    // 📊 DASHBOARD (Tambahkan ->name agar tombol 'Kembali' di detail tidak error)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // 🪑 TABLES & 🍔 MENUS (Tetap sama)
    Route::get('/tables', [TableController::class, 'index']);
    Route::post('/tables', [TableController::class, 'store']);
    Route::get('/menus', [MenuController::class, 'index']);
    Route::post('/menus', [MenuController::class, 'store']);

    // 📋 RESERVATIONS
    // Gunakan AdminController sesuai dengan kodingan Controller yang lo buat sebelumnya
    Route::get('/reservations', [AdminController::class, 'dashboard'])->name('admin.reservations.index');
    
    // Aksi ACC/Tolak (Wajib POST)
    Route::post('/reservations/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.reservation.status');
    
    // Lihat Detail Pesanan (Nasi Goreng dkk)
    Route::get('/reservations/{id}', [AdminController::class, 'show'])->name('admin.reservation.show');
});