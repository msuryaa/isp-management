<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Rute Utama otomatis redirect ke Login
Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    
});

// Auth Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //admin area
    Route::middleware('role:administrator')->group(function () {
        Route::resource('users', UserController::class);
    });

     // staff area (bisa akses customers & invoices)
    Route::resource('customers', CustomerController::class);
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store']);
    Route::patch('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
});



