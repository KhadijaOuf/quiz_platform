<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
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
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            Auth::logout();
            return redirect()->away(route('admin.login'))->withErrors(['email' => 'Veuillez utiliser la page de connexion admin.']);
            // on bloque la connexion via ce contrôleur car un admin doit passer par /admin/login (login Filament)
        } elseif ($user->hasRole('formateur')) {
            return redirect()->route('formateur.dashboard');
        } elseif ($user->hasRole('etudiant')) {
            return redirect()->route('etudiant.dashboard');
        } else {
            // Si pas de rôle ou rôle inconnu)
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre compte ne peut pas se connecter ici.']);
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
