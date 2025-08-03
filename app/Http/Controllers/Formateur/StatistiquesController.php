<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Tentative;
use Inertia\Inertia;

class StatistiquesController extends Controller
{
    public function show(Quiz $quiz)
    {
        // Récupérer les tentatives du quiz avec la note et l'étudiant
        $tentatives = $quiz->tentatives()->with('etudiant')->get();

        // Statistiques globales calculées
        $notes = $tentatives->pluck('score')->all();

        $totalNotes = $quiz->questions()->sum('note'); // adapter 'note' au champ réel

        $stats = [
            'nb_tentatives' => count($notes),
            'moyenne' => count($notes) ? round(array_sum($notes) / count($notes), 2) : 0,
            'score_max' => count($notes) ? max($notes) : 0,
            'score_min' => count($notes) ? min($notes) : 0,
            'nb_reussis' => $tentatives->where('est_passed', true)->count(),
            'nb_echoues' => $tentatives->where('est_passed', false)->count(),
        ];

        // Préparer données pour les graphiques Chart.js

        // Bar chart: noms des étudiants et leurs notes
        $barLabels = $tentatives->map(fn($t) => $t->etudiant->nom_complet ?? 'Inconnu')->all();
        $barData = [
            'labels' => $barLabels,
            'datasets' => [[
                'label' => 'Note obtenue',
                'data' => $notes,
                'backgroundColor' => 'rgba(59, 130, 246, 0.7)', // Bleu
            ]]
        ];

        // Pie chart : répartition réussites/échecs
        $pieData = [
            'labels' => ['Réussites', 'Échecs'],
            'datasets' => [[
                'data' => [$stats['nb_reussis'], $stats['nb_echoues']],
                'backgroundColor' => ['#10B981', '#EF4444'], // vert et rouge
            ]]
        ];

        // Line chart : notes triées (exemple simple)
        $sortedNotes = $notes;
        sort($sortedNotes);
        $lineData = [
            'labels' => range(1, count($sortedNotes)),
            'datasets' => [[
                'label' => 'Note triée',
                'data' => $sortedNotes,
                'fill' => false,
                'borderColor' => '#F59E0B', // orange
                'tension' => 0.3,
            ]]
        ];

        return Inertia::render('Formateur/StatistiquesQuiz', [
            'quiz' => $quiz,
            'stats' => $stats,
            'tentatives' => $tentatives,
            'totalNotes' => $totalNotes,
            'chartsData' => [
                'barData' => $barData,
                'pieData' => $pieData,
                'lineData' => $lineData,
            ],
        ]);
    }

}