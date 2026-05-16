<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->skipWhenMongoUnavailable();
    }

    protected function skipWhenMongoUnavailable(): void
    {
        if (! app()->environment('testing')) {
            return;
        }

        try {
            DB::connection(env('MONGO_USER_CONNECTION', 'mongodb'))
                ->getMongoClient()
                ->selectDatabase('admin')
                ->command(['ping' => 1]);
        } catch (Throwable $exception) {
            if (env('MONGO_TESTS_REQUIRE_CONNECTION', false)) {
                throw $exception;
            }

            $this->markTestSkipped(
                'MongoDB is unreachable for tests. Set TEST_DB_URI to a local Mongo instance (recommended: mongodb://127.0.0.1:27017/?directConnection=true) or set MONGO_TESTS_REQUIRE_CONNECTION=false. Root error: '.$exception->getMessage()
            );
        }
    }
}
