<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Support\MongoAvailability;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check_role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $exception, Request $request) {
            if (! MongoAvailability::isConnectionError($exception)) {
                return null;
            }

            $message = 'Database is temporarily unavailable. Please retry in a moment.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                ], 503);
            }

            return response()->view('errors.service-unavailable', [
                'message' => $message,
            ], 503);
        });
    })

    ->create();