<?php
# Routes Inertia/React : accueil, dashboard, formateur, étudiant

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Formateur\DashboardController as FormateurDashboardController;
use App\Http\Controllers\Formateur\ModuleController;
use App\Http\Controllers\Formateur\QuizController as FormateurQuizController;
use App\Http\Controllers\Formateur\QuestionController as FormateurQuestionController;
use App\Http\Controllers\Formateur\SpecialiteController;
use App\Http\Controllers\Formateur\CorrectionController;
use App\Http\Controllers\Formateur\StatistiquesController;
use App\Http\Controllers\Formateur\TentativeController;

use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Etudiant\QuizController as EtudiantQuizController;
use App\Http\Controllers\Etudiant\QuestionController as EtudiantQuestionController;



use App\Models\Etudiant;
use Inertia\Inertia;

Route::get('/', function () {

    if (Auth::check()) {
        // L'utilisateur est connecté -> redirection vers son dashboard
        return redirect()->route('dashboard');
    }
    // add later
    // if (Auth::guard('formateur')->check()) {
    //     return redirect()->route('formateur.dashboard');
    // }
    // if (Auth::guard('etudiant')->check()) {
    //     return redirect()->route('etudiant.dashboard');
    // }
    // if (Auth::guard('admin')->check()) {
    //     return redirect('/admin');
    // }
    if (Auth::guard('formateur')->check()) {
        return redirect()->route('formateur.dashboard');
    }
    if (Auth::guard('etudiant')->check()) {
        return redirect()->route('etudiant.dashboard');
    }
    if (Auth::guard('admin')->check()) {
        return redirect('/admin');
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

// Modules
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');

// Quizzes
    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::get('/', [FormateurQuizController::class, 'index'])->name('index');
        Route::get('/create', [FormateurQuizController::class, 'create'])->name('create');
        Route::post('/', [FormateurQuizController::class, 'store'])->name('store');

        // Archive des quizzes
        Route::get('/archives', [FormateurQuizController::class, 'archives'])->name('archives');

        // Actions sur quiz
        Route::get('/{quiz}', [FormateurQuizController::class, 'show'])->name('show');
        Route::put('/{quiz}', [FormateurQuizController::class, 'update'])->name('update');
        Route::put('/{quiz}/activer', [FormateurQuizController::class, 'activer'])->name('activer');
        Route::put('/{quiz}/archive', [FormateurQuizController::class, 'archive'])->name('archive');
        Route::delete('/{quiz}', [FormateurQuizController::class, 'destroy'])->name('destroy');

        // Questions d’un quiz
        Route::get('/{quiz}/questions', [FormateurQuestionController::class, 'index'])->name('questions.index');
        Route::post('/{quiz}/questions', [FormateurQuestionController::class, 'store'])->name('questions.store');
        Route::put('/{quiz}/questions/order', [FormateurQuestionController::class, 'updateOrder'])->name('questions.updateOrder');
        Route::delete('{quiz}/questions/{question}', [FormateurQuestionController::class, 'destroy'])->name('questions.destroy');
        

        // Tentatives
        Route::get('/{quiz}/tentatives', [TentativeController::class, 'index'])->name('tentatives.index');

        // Statistiques d’un quiz
        Route::get('/{quiz}/statistiques', [StatistiquesController::class, 'quiz'])->name('statistiques');
    });

    // Corrections
    Route::prefix('corrections')->name('corrections.')->group(function () {
        Route::get('/', [CorrectionController::class, 'index'])->name('index');
        Route::get('/{tentative}', [CorrectionController::class, 'show'])->name('show');
        Route::post('/{tentative}', [CorrectionController::class, 'store'])->name('store');
    });

    // Statistiques globales
    Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques.index');
  
});


Route::middleware(['auth:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/dashboard', [EtudiantDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::get('/', [EtudiantQuizController::class, 'index'])->name('index');
        Route::get('/{quiz}/passer', [EtudiantQuizController::class, 'passer'])->name('passer');
        Route::post('/{quiz}/soumettre', [EtudiantQuizController::class, 'soumettre'])->name('quiz.soumettre');
        Route::get('/{quiz}/resultats', [EtudiantQuizController::class, 'resultats'])->name('resultats');
        Route::get('/{quiz}/tentatives', [EtudiantQuizController::class, 'tentatives'])->name('tentatives');
        Route::get('/{quiz}/tentatives/{tentative}', [EtudiantQuizController::class, 'tentative'])->name('tentative');
        Route::get('/{quiz}/tentatives/{tentative}/correction', [EtudiantQuizController::class, 'correction'])->name('tentative.correction');
        Route::get('/{quiz}/tentatives/{tentative}/reponses', [EtudiantQuizController::class, 'reponses'])->name('tentative.reponses');
    });


});


require __DIR__ . '/auth.php';

