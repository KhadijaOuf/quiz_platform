<?php
# Routes Inertia/React : accueil, dashboard, formateur, étudiant

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Formateur\DashboardController as FormateurDashboardController;
use App\Http\Controllers\Formateur\QuizController;
use App\Http\Controllers\Formateur\SpecialiteController;
use App\Http\Controllers\Formateur\ModuleController;
use App\Http\Controllers\Formateur\CorrectionController;
use App\Http\Controllers\Formateur\StatistiquesController;
use App\Http\Controllers\Formateur\QuestionController;
use App\Http\Controllers\Formateur\TentativeController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;



use App\Models\Etudiant;
use Inertia\Inertia;

Route::get('/', function () {

    if (Auth::check()) {
        // L'utilisateur est connecté -> redirection vers son dashboard
        return redirect()->route('dashboard');
    }

    // Sinon, on affiche la page publique d'accueil
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



////////////////////////////////////////////////////////////// mes routes :

// Routes login personnalisées
Route::get('/login/formateur', fn() => Inertia::render('Auth/LoginFormateur'))->name('login.formateur');
Route::get('/login/etudiant', fn() => Inertia::render('Auth/LoginEtudiant'))->name('login.etudiant');


// Route de bouton "Dashboard" après login Breeze
Route::get('/dashboard', function () {
    if (Auth::guard('formateur')->check()) {
        return redirect()->route('formateur.dashboard');
    }

    if (Auth::guard('etudiant')->check()) {
        return redirect()->route('etudiant.dashboard');
    }

    if (Auth::guard('admin')->check()) {
        return redirect('/admin');
    }

    return redirect()->route('welcome');
})->name('dashboard');
// Route::get('/dashboard', function () {

//     // return match (true) {
//     //     $user->hasRole('formateur') => to_route('formateur.dashboard'),
//     //     $user->hasRole('etudiant') => to_route('etudiant.dashboard'),
//     //     default => redirect()->route('admin.login')->withErrors([
//     //         'email' => 'Veuillez utiliser la page admin.',
//     //     ]),
//     // };
// })->name('dashboard');


Route::middleware(['auth:formateur'])->prefix('formateur')->group(function () {
    Route::get('/dashboard', [FormateurDashboardController::class, 'index'])->name('formateur.dashboard');

    // Spécialités et modules
    Route::get('/specialites', [SpecialiteController::class, 'index'])->name('specialites.index');
    Route::get('/specialites/{specialite}/modules', [ModuleController::class, 'index'])->name('modules.index');
    // Quiz
    Route::get('/modules/{module}/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    // Questions (par quiz)
    Route::get('/quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    // Tentatives / Résultats des étudiants
    Route::get('/quizzes/{quiz}/tentatives', [TentativeController::class, 'index'])->name('tentatives.index');
    // Correction manuelle
    Route::get('/corrections', [CorrectionController::class, 'index'])->name('corrections.index');
    Route::get('/corrections/{tentative}', [CorrectionController::class, 'show'])->name('corrections.show');
    Route::post('/corrections/{tentative}', [CorrectionController::class, 'store'])->name('corrections.store');
    // Statistiques (par quiz)
    Route::get('/quizzes/{quiz}/statistiques', [StatistiquesController::class, 'quiz'])->name('statistiques.quiz');
    // Statistiques globales
    Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques.index');
});

Route::middleware(['auth:etudiant', 'etudiant'])->prefix('etudiant')->group(function () {
    Route::get('/dashboard', [EtudiantDashboardController::class, 'index'])->name('etudiant.dashboard');
});


require __DIR__ . '/auth.php';

