<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\MongoAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route($request->user()->dashboardRoute(), absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            return back()->withErrors([
                'email' => 'Email verification is temporarily unavailable because the database connection failed.',
            ]);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
