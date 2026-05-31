<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rute Utama otomatis redirect ke Login
Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});
