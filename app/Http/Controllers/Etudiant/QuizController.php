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

class QuizController extends Controller
{

public function index()
    {
        $etudiant = auth()->user()->etudiant;
        $specialiteId = $etudiant->specialite_id;
        $now = Carbon::now();

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

    public function soumettre(Request $request, Quiz $quiz)
{
    $etudiant = Auth::user()->etudiant;
    $reponses = $request->input('reponses', []);

    // Création d'une tentative
    $tentative = Tentative::create([
        'quiz_id'     => $quiz->id,
        'etudiant_id' => $etudiant->id,
        'commence_a'  => Carbon::now(),  // tu peux les remplir plus précisément si tu gères le timing côté client
        'termine_a'   => Carbon::now(),
        'score'       => 0,
        'passed'      => false,
    ]);

    $scoreTotal = 0;
    $noteTotale = 0;
    $correctionManuelle = false;

    foreach ($reponses as $reponse) {
        $question = Question::with('reponseAttendues')->find($reponse['question_id']);
        if (!$question) continue;

        $note = 0;
        $noteTotale += $question->note;

        if (in_array($question->type, ['qcm_simple', 'vrai_faux'])) {
            $note = $this->evaluerSimple($question, $reponse['reponse']);
        } elseif ($question->type === 'qcm_multiple') {
            $note = $this->evaluerMultiple($question, $reponse['reponse']);
        } else {
            $correctionManuelle = true;
            $note = null;
        }

        if (is_numeric($note)) {
            $scoreTotal += $note;
        }

        // Stocker la réponse
        ReponseDonnee::create([
            'tentative_id'  => $tentative->id,
            'question_id'   => $question->id,
            'texte'         => is_array($reponse['reponse']) ? json_encode($reponse['reponse']) : $reponse['reponse'],
            'note_obtenue'  => $note,
        ]);
    }

    $tentative->score = $scoreTotal;
    $tentative->passed = $scoreTotal >= $quiz->note_reussite;
    $tentative->save();

    return response()->json([
        'message' => 'Réponses enregistrées.',
        'score_partiel' => $scoreTotal,
        'correction_complete' => !$correctionManuelle,
    ]);
}

    private function evaluerSimple($question, $reponseTexte)
    {
        $bonne = $question->reponseAttendues->firstWhere('est_correct', true);
        return ($bonne && $bonne->texte === $reponseTexte) ? $question->note : 0;
    }

    private function evaluerMultiple($question, $reponsesArray)
    {
        $bonnes = $question->reponseAttendues->where('est_correct', true)->pluck('texte')->sort()->values();
        $donnees = collect($reponsesArray)->sort()->values();
        return $bonnes->equals($donnees) ? $question->note : 0;
    }

}