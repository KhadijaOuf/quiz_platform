<?php

namespace App\Http\Controllers\Formateur;


use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Module;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QuizController extends Controller
{
  
    public function index(Module $module) {
        $quizzes = $module->quizzes()->withCount('tentatives')->get();

        return Inertia::render('', [
            'quizzes' => $quizzes
        ]);
    }

    // Affiche le formulaire de creation de quiz
    public function create() {
        $modules = auth()->user()->formateur->modules;

        return Inertia::render('Formateur/CreerQuiz', [
            'modules' => $modules
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
            'date_debut' => 'nullable|date',
            'duree_minutes' => 'nullable|integer|min:1'
        ]);

        $quiz = Quiz::create($data);

        return redirect()->route('formateur.quizzes.show', $quiz);
    }


    public function show(Quiz $quiz)
    {
        //
    }


    public function edit(Quiz $quiz)
    {
        //
    }

    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        //
    }
}
