<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Tentative;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $formateur = auth()->user()->formateur; // Récupère le formateur connecté
        $modules = $formateur->modules; // liste des modules associés à ce formateur

        $moduleIds = $modules->pluck('id'); 
        // Transforme la collection de modules en une liste d'IDs uniquement ( prends uniquement la colonne id de chaque module)

        $quizzes = Quiz::whereIn('module_id', $moduleIds)->latest()->take(5)->with('module')->get();
        // Sélectionne les quiz dont le module_id est dans la liste $moduleIds, Limite à 5 résultats, Trie du plus récent au plus ancien
        // with('module') : charge la relation 'module' pour récupérer les informations du module associé à chaque quiz

        $quizCount = Quiz::whereIn('module_id', $moduleIds)->count();
        // compte le nombre total de quiz liés à ses modules.

        $recentTentatives = Tentative::whereHas('quiz', function ($q) use ($moduleIds) {
            $q->whereIn('module_id', $moduleIds);
        })->latest()->take(5)->with(['quiz', 'etudiant'])->get();
        // Récupère les tentatives dont le quiz est lié à l’un des module_id donnés

        return Inertia::render('Formateur/Dashboard', [
            'modulesCount' => $modules->count(),
            'quizCount' => $quizCount,
            'recentTentativesCount' => $recentTentatives->count(),
            'recentQuizzes' => $quizzes,
            'recentTentatives' => $recentTentatives,
        ]);
    }
}
