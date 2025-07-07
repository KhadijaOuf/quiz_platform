<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// mes routes :

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('/gestionFormateurs', function () {
        return Inertia::render('Admin/GestionFormateurs');
    })->name('admin.gestionFormateurs');

    Route::get('/gestionEtudiants', function () {
        return Inertia::render('Admin/GestionEtudiants');
    })->name('admin.gestionEtudiants');
});

Route::middleware(['auth', 'role:formateur'])->prefix('formateur')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Formateur/Dashboard');
    })->name('formateur.dashboard');

});

Route::middleware(['auth', 'role:etudiant'])->prefix('etudiant')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Formateur/Dashboard');
    })->name('fomateur.dashboard');

});

require __DIR__ . '/auth.php';
