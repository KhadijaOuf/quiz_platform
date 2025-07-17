<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();

            if ($user->hasRole('admin')) {
                $request->session()->regenerate();
                return redirect()->intended('/admin');
            }

            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Vous n\'avez pas les droits d\'accès admin.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }
}



