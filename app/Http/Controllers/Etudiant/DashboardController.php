<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Quiz;
use App\Models\Tentative;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $etudiant = auth()->user();

    $totalQuizzes = Quiz::whereHas('module.specialites.etudiants', function ($query) use ($etudiant) {
        $query->where('etudiants.user_id', $etudiant->user_id ?? $etudiant->id);
    })->count();

    $totalAttempts = Tentative::where('etudiant_id', $etudiant->etudiant->id)->count();

    $passedCount = Tentative::join('quizzes', 'tentatives.quiz_id', '=', 'quizzes.id')
        ->where('tentatives.etudiant_id', $etudiant->etudiant->id)
        ->whereColumn('tentatives.score', '>=', 'quizzes.note_reussite')
        ->count();

    $latestQuizzes = Quiz::whereHas('module.specialites.etudiants', function ($query) use ($etudiant) {
        $query->where('etudiants.user_id', $etudiant->id);
    })
    ->latest()
    ->take(3)
    ->get(['id', 'title', 'description', 'duration']);

    return Inertia::render('Etudiant/Dashboard', [
        'totalQuizzes' => $totalQuizzes,
        'totalAttempts' => $totalAttempts,
        'passedCount' => $passedCount,
        'latestQuizzes' => $latestQuizzes,
    ]);
    }

}
