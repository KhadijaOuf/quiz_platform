<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEtudiant
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('etudiant')->check()) {
            return redirect()->route('login.etudiant');
        }

        return $next($request);
    }
}
