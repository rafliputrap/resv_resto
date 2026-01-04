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

Route::get('/new-session', [UserController::class, 'startNewSession'])->name('customer.new-session');

Route::get('/', [UserController::class, 'askTable'])->name('ask.table');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // 🔐 AUTH
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'doLogin']);

    // 📊 DASHBOARD (Panggil di Blade: route('admin.dashboard'))
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 🪑 TABLES & 🍔 MENUS
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');

    // 📋 ACTIONS
    Route::post('/reservations/{id}/status', [AdminController::class, 'updateStatus'])->name('reservation.status');
    
    // Ini yang tadi bikin error 'not defined'
    Route::post('/table/reset/{table_id}', [AdminController::class, 'resetTable'])->name('resetTable');

    // 📜 HISTORY & REPORT
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    Route::delete('/history/{id}', [AdminController::class, 'destroy'])->name('history.delete');
});