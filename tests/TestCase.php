<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertDatabaseCount(string $table, int $count): void
    {
        $tableCount = DB::table($table)->count();

        static::assertEquals($count, $tableCount);
    }
}
