<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\MongoAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'email' => 'Login is temporarily unavailable because the database connection failed.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route($request->user()->dashboardRoute(), absolute: false));
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
