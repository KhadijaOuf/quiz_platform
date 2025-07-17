<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

# Connexion admin (Filament) avec Blade

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Dans routes/web.php
Route::get('/login', function () {
    return redirect()->route('welcome');
})->name('login');
