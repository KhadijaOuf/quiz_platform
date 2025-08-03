<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Tentative;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CorrectionController extends Controller
{
    public function index()
    {
        $formateurId = Auth::user()->formateur->id;

        // Récupérer tentatives avec au moins une question 'text' non corrigée
        $tentatives = Tentative::whereHas('quiz', function($q) use ($formateurId) {
            $q->where('formateur_id', $formateurId);
        })
        ->whereHas('reponseDonnees.question', function($q) {
            $q->where('type', 'text');
        })
        ->with(['etudiant', 'quiz', 'reponseDonnees' => function($q) {
            $q->whereHas('question', fn($query) => $query->where('type', 'text'));
        }])
        ->get()
        ->filter(function($tentative) {
            // Garder celles où au moins une réponse rédactionnelle n'est pas corrigée
            return $tentative->reponseDonnees->contains(fn($rep) => $rep->note === null);
        })
        ->values();

        return Inertia::render('Formateur/CorrectionPages/CorrectionList', [
            'tentatives' => $tentatives,
        ]);
    }

    public function show($tentativeId)
    {
        $tentative = Tentative::with([
            'etudiant.user',
            'quiz.questions.reponseAttendues',
            'reponseDonnees.question.reponseAttendues'
        ])->findOrFail($tentativeId);

        // Vérifie que le formateur a accès au quiz de cette tentative (sécurité)
        $formateurModules = auth()->user()->formateur->modules->pluck('id');
        if (!$formateurModules->contains($tentative->quiz->module_id)) {
            abort(403);
        }

        $reponses = $tentative->reponseDonnees->map(function ($reponse) {
            $question = $reponse->question;
            $estCorrecte = $reponse->note_obtenue === null ? null : $reponse->note_obtenue >= $question->note;

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

        return Inertia::render('Formateur/CorrectionPages/CorrectionQuiz', [
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


    public function update(Request $request, $tentativeId)
    {
        $tentative = Tentative::with('quiz.questions')->findOrFail($tentativeId);

        // Vérifier que le formateur a accès au quiz de cette tentative (sécurité)
        $formateurModules = auth()->user()->formateur->modules->pluck('id');
        if (!$formateurModules->contains($tentative->quiz->module_id)) {
            abort(403);
        }

        $data = $request->validate([
            'notes' => 'required|array',
            'notes.*' => 'nullable|numeric|min:0',
        ]);

        // Mettre à jour les notes des réponses rédactionnelles (type 'text')
        foreach ($data['notes'] as $questionId => $note) {
            // S'assurer que la question est de type 'text'
            $question = $tentative->quiz->questions->where('id', $questionId)->first();
            if (!$question || $question->type !== 'text') {
                continue; // ignorer si question non trouvée ou pas de type text
            }

            // Trouver la réponse donnée correspondante
            $reponse = $tentative->reponseDonnees()->where('question_id', $questionId)->first();

            if ($reponse) {
                $reponse->note_obtenue = $note;
                $reponse->save();
            }
        }

        // Recalculer le score total sur toutes les réponses données (incluant QCM + texte)
        $totalScore = $tentative->reponseDonnees()->sum('note_obtenue');

        // Mettre à jour la tentative
        $tentative->score = $totalScore;
        $tentative->est_corrigee = true;

        // Décider si l'étudiant a réussi selon note_reussite du quiz
        $tentative->est_passed = $totalScore >= $tentative->quiz->note_reussite;

        $tentative->save();

        return redirect()->route('correction.show', $tentative->id)
                        ->with('success', 'Correction enregistrée avec succès');
    }


}

