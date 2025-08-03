<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tentative;
use Inertia\Inertia;

class ResultatController extends Controller
{
    public function index()
    {
        $etudiant = Auth::guard('etudiant')->user()->etudiant;

        $tentatives = Tentative::with('quiz.module', 'quiz.questions') // charger questions aussi
            ->where('etudiant_id', $etudiant->id)
            ->orderByDesc('termine_a')
            ->get()
            ->map(function ($tentative) {
                $quiz = $tentative->quiz;

                // Note totale = somme des notes des questions
                $noteTotale = $quiz->questions->sum('note');

                // Vérifier s'il y a une question texte
                $contientQuestionTexte = $quiz->questions->contains('type', 'text');

                return [
                    'id' => $tentative->id,
                    'score' => $tentative->score,
                    'est_passed' => $tentative->est_passed,
                    'termine_a' => $tentative->termine_a,

                    'quiz' => [
                        'id' => $quiz->id,
                        'title' => $quiz->title,
                        'module' => $quiz->module,
                        'contientQuestionTexte' => $contientQuestionTexte,
                        'noteTotale' => $noteTotale,
                    ],
                ];
            });
        return Inertia::render('Etudiant/ResultatPages/Resultats', [
            'tentatives' => $tentatives,
        ]);
    }

}
