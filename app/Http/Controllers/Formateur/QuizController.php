<?php

namespace App\Http\Controllers\Formateur;


use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Module;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function index()
    {
        // archiver les quizzes dont la date de fin est dépassée
        $now = Carbon::now();
        Quiz::where('est_actif', true)
            ->where('archived', false)
            ->whereNotNull('disponible_jusquau')
            ->where('disponible_jusquau', '<', $now)
            ->update(['archived' => true, 'est_actif' => false]);

        $formateur = auth()->user()->formateur;
        // Récupère tous les quizzes du formateur
        $quizzes = $quizzes = Quiz::with('module')->withCount('tentatives')
            ->where('formateur_id', $formateur->id)
            ->where('archived', false)  // exclure archivés
            ->latest()
            ->get();
            
        return Inertia::render('Formateur/QuizPages/QuizzesList', [
            'quizzes' => $quizzes
        ]);
    }

    // Affiche le formulaire de creation de quiz
    public function create()
    {
        $modules = auth()->user()->formateur->modules;

        return Inertia::render('Formateur/QuizPages/CreerQuiz', [
            'modules' => $modules
        ]);
    }

    public function store(Request $request)
    {
        $formateur = auth()->user()->formateur;

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'note_reussite' => 'required|numeric|min:0|max:100',
            'module_id' => 'required|exists:modules,id',
            'disponible_du' => 'nullable|date',
            'disponible_jusquau' => 'nullable|date|after_or_equal:disponible_du',
            'est_actif' => 'sometimes|boolean',
        ]);

        if (!empty($data['disponible_du'])) {
            $data['disponible_du'] = Carbon::parse(str_replace('T', ' ', $data['disponible_du']));
        }
        if (!empty($data['disponible_jusquau'])) {
            $data['disponible_jusquau'] = Carbon::parse(str_replace('T', ' ', $data['disponible_jusquau']));
        }

        $quiz = Quiz::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration' => $data['duration'] ?? null,
            'note_reussite' => $data['note_reussite'],
            'module_id' => $data['module_id'],
            'formateur_id' => $formateur->id,
            'disponible_du' => $data['disponible_du'] ?? null,
            'disponible_jusquau' => $data['disponible_jusquau'] ?? null,
            'est_actif' => $data['est_actif'] ?? false,
        ]);

        return redirect()->route('quizzes.questions.index', ['quiz' => $quiz->getKey()])
            ->with('success', 'Quiz créé avec succès. Ajoutez les questions.');
    }


    public function show(Quiz $quiz)
    {
        $quiz->load('module', 'questions.reponseAttendues');

        return Inertia::render('Formateur/QuizPages/AfficherQuiz', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
        ]);
    }


    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->est_actif) {
            return response()->json(['message' => 'Impossible de modifier un quiz actif.'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'note_reussite' => 'required|numeric|min:0|max:100',
            'module_id' => 'required|exists:modules,id',
            'disponible_du' => 'nullable|date',
            'disponible_jusquau' => 'nullable|date|after_or_equal:disponible_du',
            'est_actif' => 'sometimes|boolean',
        ]);

        if (!empty($data['disponible_du'])) {
            $data['disponible_du'] = Carbon::parse(str_replace('T', ' ', $data['disponible_du']));
        }
        if (!empty($data['disponible_jusquau'])) {
            $data['disponible_jusquau'] = Carbon::parse(str_replace('T', ' ', $data['disponible_jusquau']));
        }

        $quiz->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration' => $data['duration'] ?? null,
            'note_reussite' => $data['note_reussite'],
            'module_id' => $data['module_id'],
            'disponible_du' => $data['disponible_du'] ?? null,
            'disponible_jusquau' => $data['disponible_jusquau'] ?? null,
            'est_actif' => $data['est_actif'] ?? false,
        ]);

        return redirect()->route('quizzes.index')->with('success', 'Quiz mis à jour avec succès.');
    }

    public function activer(Quiz $quiz)
    {
        if ($quiz->questions()->count() === 0) {
            return back()->withErrors(['message' => 'Le quiz doit contenir au moins une question pour être activé.']);
        }

        $quiz->update(['est_actif' => true]);

        return response()->json(['message' => 'Quiz activé.']);
    }

    // archiver un quiz
    public function archive(Quiz $quiz)
    {
        if (!$quiz->est_actif) {
            return back()->withErrors(['message' => 'Seuls les quizzes actifs peuvent être archivés.']);
        }
        $quiz->update(['archived' => true]);

        return response()->json(['message' => 'Quiz archivé avec succès.']);
    }

    // Affiche les quizzes archivés
    public function archives()
    {
        $formateur = auth()->user()->formateur;

        $quizzesArchives = Quiz::with('module')->withCount('tentatives')
            ->where('formateur_id', $formateur->id)
            ->where('archived', true)
            ->latest()
            ->get();

        return Inertia::render('Formateur/QuizPages/QuizzesArchives', [
            'quizzes' => $quizzesArchives,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        if ($quiz->est_actif) {
            return back()->withErrors(['message' => 'Impossible de supprimer un quiz actif. Veuillez l’archiver si vous souhaitez le masquer.']);
        }
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz supprimé avec succès.');
    }
}
