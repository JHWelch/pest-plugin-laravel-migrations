<?php

use Illuminate\Support\Carbon;
use JHWelch\PestLaravelMigrations\Tests\CommandTestCase;

uses(CommandTestCase::class);

beforeEach(function () {
    $this->app->setBasePath(__DIR__.'/../test_data');
    Carbon::setTestNow(Carbon::create(2024, 9, 17, 0, 0, 0));
});

it('creates a test for the migration', function () {
    $this->artisan('make:migration', [
        'name' => '2024_09_12_000000_update_users_table_combine_names',
        '--test' => true,
    ])
        ->expectsOutputToContain('Test [tests/Migration/UpdateUsersTableCombineNamesTest.php] created successfully.');

    $this->assertFileExists(__DIR__.'/../test_data/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});
it('behaves normally without the test flag', function () {
    $this->artisan('make:migration', [
        'name' => '2024_09_12_000000_update_users_table_combine_names',
    ])
        ->doesntExpectOutputToContain('Test [tests/Migration/UpdateUsersTableCombineNamesTest.php] created successfully.');

    $this->assertFileDoesNotExist(__DIR__.'/../test_data/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});
