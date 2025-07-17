<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $expectedRole = $request->input('role'); // 'formateur' ou 'etudiant'

        if (!is_string($expectedRole)) {
            return back()->withErrors([
                'email' => 'Rôle non spécifié.',
            ]);
        }

        $credentials = $request->only('email', 'password');

        // Choisir le guard en fonction du rôle
        $guard = match ($expectedRole) {
            'formateur' => 'formateur',
            'etudiant' => 'etudiant',
            default => 'web',
        };

        // Authentifier avec le bon guard
        if (!Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Les identifiants sont incorrects.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::guard($guard)->user();

        if (!$user->hasRole($expectedRole)) {
            Auth::guard($guard)->logout();
            return back()->withErrors([
                'email' => 'Vous n’avez pas le rôle requis.',
            ]);
        }

        return match ($expectedRole) {
            'formateur' => redirect()->route('formateur.dashboard'),
            'etudiant' => redirect()->route('etudiant.dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Déconnexion de tous les guards possibles
        foreach (['web', 'formateur', 'etudiant',] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

