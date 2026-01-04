<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class EnsureAdminRole
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        if (!$user || !Admin::where('user_id', $user->id)->exists()) {
            abort(403, 'Accès admin refusé.');
        }

        return $next($request);
    }
}
