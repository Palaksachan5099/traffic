<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\MongoAvailability;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $home = route($request->user()->dashboardRoute(), absolute: false).'?verified=1';

        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended($home);
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            return redirect()->route('verification.notice')->withErrors([
                'email' => 'Email verification is temporarily unavailable because the database connection failed.',
            ]);
        }

        return redirect()->intended($home);
    }
}
