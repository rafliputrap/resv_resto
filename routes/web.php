<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class,'indexDefault']);
Route::get('/u/{token}', [UserController::class,'index']);
Route::post('/reservasi', [UserController::class,'store']);


