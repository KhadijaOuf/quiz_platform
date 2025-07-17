<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SpecialiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $specialites = auth()->user()->formateur->specialites()->with('modules')->get();
        return Inertia::render('Formateur/SpecialitesPage', ['specialites' => $specialites]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }
 
}
