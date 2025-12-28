<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// 🔥 IMPORT ADMIN CONTROLLER (WAJIB)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [UserController::class,'askTable']);
Route::get('/select-table', [UserController::class,'selectTable']);
Route::post('/select-table', [UserController::class,'chooseTable']);

Route::get('/menu', [UserController::class,'menu']);
Route::post('/order', [UserController::class,'order']);
Route::get('/order', [UserController::class,'orderPage']);

Route::get('/payment', [UserController::class,'payment']);
Route::post('/payment', [UserController::class,'store']);


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // 🔐 AUTH
    Route::get('/login', [AdminController::class, 'login']);
    Route::post('/login', [AdminController::class, 'doLogin']);

    // 📊 DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // 🪑 TABLES
    Route::get('/tables', [TableController::class, 'index']);
    Route::post('/tables', [TableController::class, 'store']);

    // 🍔 MENUS
    Route::get('/menus', [MenuController::class, 'index']);
    Route::post('/menus', [MenuController::class, 'store']);

    // 📋 RESERVATIONS
    Route::get('/reservations', [ReservationController::class, 'index']);
});