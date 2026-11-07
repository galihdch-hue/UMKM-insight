<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderLocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UmkmAnalyticsController;
use App\Http\Controllers\UmkmDashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UmkmDashboardController::class, 'index'])->name('umkm.dashboard');
    Route::resource('products', ProductController::class);

    Route::get('/map', [OrderLocationController::class, 'index'])->name('map.index');
    Route::post('/map', [OrderLocationController::class, 'store'])->name('map.store');
    Route::delete('/map/{orderLocation}', [OrderLocationController::class, 'destroy'])->name('map.destroy');

    Route::get('/analytics', [UmkmAnalyticsController::class, 'index'])->name('umkm.analytics');
});
