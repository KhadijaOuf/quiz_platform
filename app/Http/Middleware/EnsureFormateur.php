<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureFormateur
{
    public function handle(Request $request, Closure $next)
    {
         if (!Auth::guard('formateur')->check()) {
            return redirect()->route('login.formateur');
        }

        return $next($request);
    }
}
