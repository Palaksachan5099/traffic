<?php

namespace App\Support;

use Throwable;
use MongoDB\Driver\Exception\Exception as MongoDriverException;

class MongoAvailability
{
    public static function isConnectionError(Throwable $exception): bool
    {
        if ($exception instanceof MongoDriverException) {
            return true;
        }

        $message = strtolower($exception->getMessage());

        return str_contains($message, 'server selection')
            || str_contains($message, 'no suitable servers')
            || str_contains($message, 'connection timed out')
            || str_contains($message, 'failed to connect')
            || str_contains($message, 'getaddrinfo failed')
            || str_contains($message, 'topology is closed');
    }
}
