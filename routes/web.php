<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuController;

/*
|--------------------------------------------------------------------------
| USER ROUTES (PELANGGAN)
|--------------------------------------------------------------------------
*/

// 1. Proses Awal & Pilih Meja
Route::get('/', [UserController::class, 'askTable'])->name('ask.table');
Route::get('/new-session', [UserController::class, 'startNewSession'])->name('customer.new-session');
Route::get('/select-table', [UserController::class, 'selectTable'])->name('select.table');
Route::post('/confirm-table', [UserController::class, 'chooseTable'])->name('confirm.table');

// 2. Halaman Menu & Keranjang
Route::get('/menu', [UserController::class, 'menu'])->name('user.menu');
Route::post('/cart/add', [UserController::class, 'addToCart'])->name('cart.add');
Route::post('/cart-remove', [UserController::class, 'removeFromCart'])->name('cart.remove');

// 3. Checkout & Proses Pembayaran (DIUPDATE BIAR SAT-SET)
Route::get('/order-detail', [UserController::class, 'orderPage'])->name('order.detail');
Route::post('/order/checkout', [UserController::class, 'storeAjax'])->name('order.checkout');

// 4. Halaman Sukses & Fitur Selesai Meja
Route::get('/payment-success/{id}', [UserController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/finish-table/{id}', [UserController::class, 'finishTable'])->name('customer.finishTable');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (DASHBOARD)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // 🔐 AUTH
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'doLogin']);

    // 📊 DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth')->name('dashboard');

    // 🍔 MANAJEMEN MENU
    Route::resource('menus', MenuController::class);

    // 🪑 MANAJEMEN MEJA
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::delete('/tables/{id}', [TableController::class, 'destroy'])->name('tables.destroy');

    // 🔥 PERBAIKAN: Route untuk tombol "Selesai" di Dashboard Admin
    // Route ini menggantikan 'admin.resetTable' agar tidak error lagi
    Route::post('/tables/update-status/{id}', [TableController::class, 'updateStatus'])->name('tables.updateStatus');

    // 📋 TRANSAKSI & HISTORY
    Route::post('/reservations/{id}/status', [AdminController::class, 'updateStatus'])->name('reservation.status');
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    Route::delete('/history/{id}', [AdminController::class, 'destroy'])->name('history.destroy');

    // LOGOUT ADMIN
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
