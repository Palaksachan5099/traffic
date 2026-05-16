<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts routes to users with role "admin".
 * Prefer this alias when you want middleware without parameters (vs role:admin).
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ($user->role ?? null) !== 'admin') {
            abort(403, 'Administrators only.');
        }

        return $next($request);
    }
}
