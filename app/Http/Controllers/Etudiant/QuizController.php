<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Module;
use App\Models\Question;
use App\Models\Tentative;
use App\Models\ReponseDonnee;
use App\Models\ReponseAttendue;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

public function index()
    {
        $etudiant = auth('etudiant')->user()->etudiant;
        $specialiteId = $etudiant->specialite_id;
        $now = Carbon::now()->setTimezone('Europe/Paris'); 


        // Récupérer les IDs des modules liés à la spécialité de l'étudiant
        $modulesIds = Module::whereHas('specialites', function ($query) use ($specialiteId) {
            $query->where('specialites.id', $specialiteId);
        })->pluck('id');

        // Récupérer les quizzes dont le module est dans cette liste
        $quizzes = Quiz::whereIn('module_id', $modulesIds)
            ->where('est_actif', true)
            ->where('archived', false)
            ->where('disponible_du', '<=', $now)
            ->where('disponible_jusquau', '>=', $now)
            ->with('module')
            ->get();

        // Récupérer les ids des quizzes déjà passés par l'étudiant
        $quizIdsPasses = Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->pluck('quiz_id')
            ->toArray();

        // Ajouter un attribut 'dejaPasse' à chaque quiz
        $quizzes->transform(function ($quiz) use ($quizIdsPasses) {
            $quiz->dejaPasse = in_array($quiz->id, $quizIdsPasses);
            return $quiz;
        });

        return Inertia::render('Etudiant/QuizPages/QuizzesList', [
            'quizzes' => $quizzes,
        ]);
    }

    public function passer(Quiz $quiz)
    {
        $quiz->load('questions.reponseAttendues');

        return Inertia::render('Etudiant/QuizPages/PasserQuiz', [
            'quiz' => $quiz,
        ]);
    }

    public function soumettre(Quiz $quiz, Request $request)
{
    $etudiant = Auth::guard('etudiant')->user()->etudiant;

    $tentativeExistante = Tentative::where('quiz_id', $quiz->id)
        ->where('etudiant_id', $etudiant->id)
        ->first();

    if ($tentativeExistante) {
        return response()->json([
            'message' => 'Vous avez déjà passé ce quiz.',
        ], 403);
    }

    $reponses = $request->input('reponses', []);

    // Vérifier s'il y a au moins une question rédactionnelle
    $contientQuestionTexte = $quiz->questions()->where('type', 'text')->exists();

    DB::beginTransaction();
    try {
        $tentative = Tentative::create([
            'quiz_id' => $quiz->id,
            'etudiant_id' => $etudiant->id,
            'commence_a' => now(),
            'termine_a' => now(),
            'score' => 0, // on met à 0 temporairement
            'est_corrigee' => !$contientQuestionTexte, // true si pas de texte, false sinon
        ]);

        $scoreTotal = 0;

        foreach ($reponses as $questionId => $reponseDonnee) {
            $question = $quiz->questions()->findOrFail($questionId);
            $reponsesAttendues = $question->reponseAttendues()->where('est_correct', true)->get();

            $noteQuestion = 0;

            if (in_array($question->type, ['single', 'vrai_faux'])) {
                $repAttendue = $reponsesAttendues->first();
                if ($repAttendue && $reponseDonnee == $repAttendue->texte) {
                    $noteQuestion = $question->note;
                }
            } elseif ($question->type === 'multiple') {
                $noteQuestion = 0;
                $reponseDonneeArray = is_array($reponseDonnee) ? $reponseDonnee : [];
                foreach ($reponsesAttendues as $repAttendue) {
                    if (in_array($repAttendue->texte, $reponseDonneeArray)) {
                        $noteQuestion += $repAttendue->note_partielle ?? 0;
                    }
                }
            } else {
                // Question texte => note 0 pour l'instant (correction manuelle)
                $noteQuestion = null;
            }

            // Enregistrement des réponses données
            if (is_array($reponseDonnee)) {
                foreach ($reponseDonnee as $valeur) {
                    ReponseDonnee::create([
                        'tentative_id' => $tentative->id,
                        'question_id' => $questionId,
                        'texte' => $valeur,
                        'note_obtenue' => $noteQuestion,
                    ]);
                }
            } else {
                ReponseDonnee::create([
                    'tentative_id' => $tentative->id,
                    'question_id' => $questionId,
                    'texte' => $reponseDonnee,
                    'note_obtenue' => $noteQuestion,
                ]);
            }

            $scoreTotal += $noteQuestion;
        }

        // Met à jour le score total uniquement si pas de correction manuelle à faire
        if (!$contientQuestionTexte) {
            $tentative->score = $scoreTotal;
            $tentative->est_passed = $scoreTotal >= $quiz->note_reussite;
            $tentative->save();
        }

        DB::commit();

        return redirect()->route('etudiant.quizzes.correction', [
            'quiz' => $quiz->id,
            'tentative' => $tentative->id,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Erreur lors de l\'enregistrement des réponses.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    public function afficherCorrection(Quiz $quiz, Tentative $tentative)
    {
        $etudiant = auth('etudiant')->user()->etudiant;

        // Sécurité : interdit l'accès si la tentative ne appartient pas à cet étudiant
        abort_unless($tentative->etudiant_id === $etudiant->id, 403);

        // Charge en mémoire la tentative avec :
        // - la relation 'quiz' (le quiz auquel la tentative appartient)
        // - la relation 'reponseDonnees' avec leurs questions et les réponses attendues de ces questions
        $tentative->load([
            'quiz',
            'reponseDonnees.question.reponseAttendues'  // Charge les réponses attendues des questions
        ]);

        $noteTotale = $quiz->questions->sum('note');

        return Inertia::render('Etudiant/QuizPages/CorrectionQuiz', [
            'quiz' => $quiz,
            'tentative' => $tentative,

            // Prépare un tableau 'reponsesDonnees' à partir des réponses données
            'reponsesDonnees' => $tentative->reponseDonnees->map(function ($reponse) {
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
            }),
            'score' => $tentative->score,
            'estCorrigee' => $tentative->est_corrigee,
            'noteTotale' => $noteTotale,
        ]);
    }

}