<?php

namespace App\Http\Controllers\Formateur;


use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {

        // Récupération du formateur connecté
        $formateurId = auth()->user()->formateur->id;

        // Protection : on vérifie que ce quiz appartient au formateur
        if ($quiz->getAttribute('formateur_id') !== $formateurId) {
            abort(403, 'Vous n’êtes pas autorisé à accéder à ce quiz.');
        }

        // Charger les questions avec tri par ordre
        $quiz->load([
            'questions' => function ($query) {
                $query->orderBy('ordre')->with('reponseAttendues'); // Récupérer les questions du quiz, triées par ordre et avec ses réponses attendues.
            }
        ]);

        return Inertia::render('Formateur/QuestionPages/AjoutQuestions', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Update the order of questions for a quiz.
     */
    public function updateOrder(Request $request, Quiz $quiz)
    {
        // Validation des données reçues
        $orders = $request->validate([
            'ordres' => 'required|array',             // On attend un tableau nommé 'ordres'
            'ordres.*' => 'integer|exists:questions,id', // Chaque élément du tableau doit être un entier correspondant à un ID de question existant
        ]);

        // Mise à jour de l'ordre de chaque question
        foreach ($orders['ordres'] as $position => $questionId) {
            // Pour chaque ID de question dans le tableau, on met à jour sa colonne 'order' dans la base
            // 'order' prend la valeur de la position + 1 (car $position commence à 0)
            // On s'assure que la question appartient bien au quiz via where('quiz_id', $quiz->id)
            Question::where('id', $questionId)
                ->where('quiz_id', $quiz->getKey())
                ->update(['ordre' => $position + 1]);
        }

        return response()->json(['message' => 'Ordre des questions mis à jour.']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    public function store(Request $request, $quizId)
    {

        // Récupération du formateur connecté
        $formateurId = auth()->user()->formateur->id;

        $quiz = Quiz::where('id', $quizId)->firstOrFail();
        if ($quiz->getAttribute('formateur_id') !== $formateurId) {
            abort(403, 'Non autorisé à ajouter une question à ce quiz.');
        }

        $validated = $request->validate([
            'enonce' => 'required|string',
            'type' => 'required|in:single,multiple,vrai_faux,text',
            'note' => 'required|numeric|min:0',
            'options' => 'nullable|array',
            'options.*.text' => 'nullable|string',
            'options.*.correct' => 'boolean',
            'options.*.note_partielle' => 'nullable|numeric',
            'correctAnswer' => 'required_if:type,vrai_faux|boolean', // pour vrai_faux
        ]);

        $question = Question::create([
            'quiz_id' => $quizId,
            'enonce' => $validated['enonce'],
            'type' => $validated['type'],
            'note' => $validated['note'],
            'ordre' => Question::where('quiz_id', $quizId)->count() + 1,
        ]);

        if (in_array($question->getAttribute('type'), ['single', 'multiple']) && !empty($validated['options'])) {
            foreach ($validated['options'] as $option) {
                $question->reponseAttendues()->create([
                    'texte' => $option['text'],
                    'est_correct' => $option['correct'],
                    'note_partielle' => $option['note_partielle'] ?? 0,
                ]);
            }
        }

        if ($question->getAttribute('type') === 'vrai_faux') {
            $question->reponseAttendues()->create([
                'texte' => 'Vrai',
                'est_correct' => $validated['correctAnswer'] === true,
                'note_partielle' => $validated['correctAnswer'] === true ? $validated['note'] : 0,
            ]);
            $question->reponseAttendues()->create([
                'texte' => 'Faux',
                'est_correct' => $validated['correctAnswer'] === false,
                'note_partielle' => $validated['correctAnswer'] === false ? $validated['note'] : 0,
            ]);
        }

        return response()->json($question->load('reponseAttendues'), 201);

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        if ($quiz->getAttribute('formateur_id') !== auth()->user()->formateur->id || $question->getAttribute('quiz_id') !== $quiz->getKey()) {
            abort(403);
        }

        $question->delete();
        return response()->json(['status' => 'deleted']);
    }
}
