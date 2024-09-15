<?php

use Illuminate\Foundation\Testing\DatabaseTruncation;
use JHWelch\PestLaravelMigrations\Exceptions\MigrationTestUsageException;
use JHWelch\PestLaravelMigrations\Tests\TestCase;

use function JHWelch\PestLaravelMigrations\testMigration;

uses(TestCase::class);
uses(DatabaseTruncation::class);

testMigration('2024_09_12_000000_update_users_table_combine_names', function ($up, $down) {
    // Test will Immediately fail
})->throws(
    MigrationTestUsageException::class,
    'The following traits are incompatible with the `testMigration` usage: Illuminate\Foundation\Testing\DatabaseTruncation'
);
