<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use JHWelch\PestLaravelMigrations\Exceptions\MigrationTestUsageException;
use JHWelch\PestLaravelMigrations\Tests\TestCase;

use function JHWelch\PestLaravelMigrations\migration;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

migration('2024_09_12_000000_update_users_table_combine_names', function ($up, $down) {
    // Test will Immediately fail
})->throws(
    MigrationTestUsageException::class,
    'The following traits are incompatible with the `migration` usage: Illuminate\Foundation\Testing\LazilyRefreshDatabase'
);
