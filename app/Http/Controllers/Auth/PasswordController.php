<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\MongoAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Throwable;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
        } catch (Throwable $exception) {
            if (! MongoAvailability::isConnectionError($exception)) {
                throw $exception;
            }

            return back()->withErrors([
                'current_password' => 'Password update is temporarily unavailable because the database connection failed.',
            ]);
        }

        return back()->with('status', 'password-updated');
    }
}
