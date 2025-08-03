<?php

namespace App\Http\Controllers\Formateur;


use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Tentative;
use Inertia\Inertia;

class TentativeController extends Controller
{
 
    public function index(Quiz $quiz)
    {
        $tentatives = Tentative::with('etudiant')
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();
        
        $noteTotale = $quiz->questions->sum('note');

        return Inertia::render('Formateur/QuizPages/QuizTentatives', [
            'quiz' => $quiz,
            'tentatives' => $tentatives,
            'noteTotale' => $noteTotale,
        ]);
    }


    public function show($quizId, $tentativeId)
    {
        $tentative = Tentative::with([
            'etudiant.user',
            'quiz.questions.reponseAttendues',
            'reponseDonnees.question.reponseAttendues'
        ])->findOrFail($tentativeId);

        if ($tentative->quiz_id != $quizId) {
            abort(404);
        }

        // Vérifie que le formateur a accès au module de ce quiz
        $formateurModules = auth()->user()->formateur->modules->pluck('id');
        if (!$formateurModules->contains($tentative->quiz->module_id)) {
            abort(403);
        }

        $reponses = $tentative->reponseDonnees->map(function ($reponse) {
            $question = $reponse->question;
            $estCorrecte = $reponse->note_obtenue >= $question->note;

            return [
                'question_id' => $reponse->question_id,
                'texte' => $reponse->texte,
                'note_obtenue' => $reponse->note_obtenue,
                'est_correcte' => $estCorrecte,
                'question' => [
                    'id' => $question->id,
                    'type' => $question->type,
                    'enonce' => $question->enonce,
                    'note' => $question->note,
                    'reponse_attendues' => $question->reponseAttendues->map(function ($rep) {
                        return [
                            'texte' => $rep->texte,
                            'est_correct' => $rep->est_correct,
                            'note_partielle' => $rep->note_partielle,
                        ];
                    }),
                ],
            ];
        });

        $noteTotale = $tentative->quiz->questions->sum('note');

        return Inertia::render('Formateur/QuizPages/CorrectionQuiz', [
            'quiz' => $tentative->quiz,
            'tentative' => [
                'id' => $tentative->id,
                'score' => $tentative->score,
                'est_corrigee' => $tentative->est_corrigee,
                'termine_a' => $tentative->termine_a,
                'commence_a' => $tentative->commence_a,
                'etudiant' => [
                    'id' => $tentative->etudiant->id,
                    'nom_complet' => $tentative->etudiant->user->nom . ' ' . $tentative->etudiant->user->prenom,
                ],
            ],
            'reponsesDonnees' => $reponses,
            'score' => $tentative->score,
            'estCorrigee' => $tentative->est_corrigee,
            'noteTotale' => $noteTotale,
        ]);
    }

}
