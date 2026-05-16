<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Thin auth helpers alongside Breeze (routes in routes/auth.php).
 * Use this for JSON “who am I” or redirects into the Breeze flow.
 */
class AuthController extends Controller
{
    /**
     * Current authenticated user (JSON — useful for SPAs or map clients).
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => (string) $user->getKey(),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'user',
        ]);
    }

    /**
     * Redirect to Breeze login (single entry if you prefer named route auth.login.redirect).
     */
    public function redirectToLogin(): RedirectResponse
    {
        return redirect()->guest(route('login'));
    }

}
