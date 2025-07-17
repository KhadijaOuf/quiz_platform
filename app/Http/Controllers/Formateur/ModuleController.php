<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Specialite;
use Inertia\Inertia;

class ModuleController extends Controller
{
    public function index(Specialite $specialite) {
        $modules = $specialite->modules()->whereHas('formateurs', fn ($q) => $q->where('id', auth()->id()))->get();
        return Inertia::render('Formateur/ModulesPage', ['modules' => $modules]);
    }

}
