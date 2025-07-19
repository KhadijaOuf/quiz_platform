<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Tentative;
use Inertia\Inertia;

class StatistiquesController extends Controller
{
    public function index()
{
    $formateur = auth()->user()->formateur;
    $modules = $formateur->modules;

    $moduleIds = $modules->pluck('id');

    $quizCount = Quiz::whereIn('module_id', $moduleIds)->count();
    $tentativeCount = Tentative::whereHas('quiz', fn($q) => $q->whereIn('module_id', $moduleIds))->count();

    $averageScore = Tentative::whereHas('quiz', fn($q) => $q->whereIn('module_id', $moduleIds))
        ->avg('score') ?? 0;

    $modulesStats = $modules->map(function ($module) {
        $quizCount = $module->quizzes()->count();
        $tentativeCount = Tentative::whereHas('quiz', fn($q) => $q->where('module_id', $module->id))->count();
        $avgScore = Tentative::whereHas('quiz', fn($q) => $q->where('module_id', $module->id))->avg('score') ?? 0;

        return [
            'id' => $module->id,
            'nom' => $module->nom,
            'quiz_count' => $quizCount,
            'tentative_count' => $tentativeCount,
            'avg_score' => $avgScore,
        ];
    });

    return Inertia::render('Formateur/StatistiquesPage', [
        'modulesCount' => $modules->count(),
        'quizCount' => $quizCount,
        'tentativeCount' => $tentativeCount,
        'averageScore' => $averageScore,
        'modulesStats' => $modulesStats,
    ]);
}
}