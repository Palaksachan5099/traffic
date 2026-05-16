<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;

/**
 * Usage: Route::middleware('check_role:admin,officer') — comma-separated roles, any match allows access.
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowed = [];
        foreach ($roles as $roleChunk) {
            foreach (explode(',', $roleChunk) as $role) {
                $normalized = strtolower(trim($role));
                if ($normalized !== '') {
                    $allowed[] = $normalized;
                }
            }
        }
        $userRole = strtolower(trim((string) ($user->role ?? '')));

        if ($allowed === [] || ! in_array($userRole, $allowed, true)) {
            // If a logged-in user hits a route for a different role (e.g. admin visiting /dashboard),
            // redirect them to the appropriate dashboard instead of showing a 403.
            if (method_exists($user, 'dashboardRoute')) {
                $targetRoute = $user->dashboardRoute();

                // Avoid redirect loops when current route is already the computed target.
                if ($request->routeIs($targetRoute)) {
                    abort(403, 'You do not have permission for this page.');
                }

                return redirect()->route($targetRoute);
            }

            abort(403, 'You do not have permission for this action.');
        }

        return $next($request);
    }
}
