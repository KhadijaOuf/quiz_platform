<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Specialite;

class ModuleController extends Controller
{
    public function index()
    {  // récupère les modules liés au formateur avec specialités
        $formateur = auth()->user()->formateur;
        $modules = $formateur->modules()->with('specialites')->get();

        $specialites = Specialite::orderBy('nom')->get();
        return Inertia::render('Formateur/ModulesPage', [
            'modules' => $modules,
            'specialites' => $specialites,
        ]);
    }

}
