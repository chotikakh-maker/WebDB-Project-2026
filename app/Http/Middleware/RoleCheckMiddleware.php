<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheckMiddleware
{
    public function handle(Request $request, Closure $next, $roleName)
    {
        if (!Auth::check() || Auth::user()->role->name !== $roleName) {
            abort(403, 'Unauthorized access.');
        }
        return $next($request);
    }
}