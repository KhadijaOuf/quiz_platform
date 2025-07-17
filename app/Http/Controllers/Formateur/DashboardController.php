<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // tu peux passer les données que tu veux à ta vue ici, par exemple :
        $user = Auth::user();

        return Inertia::render('Formateur/Dashboard', [
            'user' => $user,
        ]);
    }
}
