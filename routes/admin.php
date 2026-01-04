<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

# Connexion admin (Filament) avec Blade

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:admin'])->group(function () {
    // Admin dashboard via Filament
    Route::get('/admin/dashboard', function () {
        return redirect('/admin');
    })->name('admin.dashboard');
});

Route::get('/login', function () {
    return redirect()->route('welcome');
})->name('login');
