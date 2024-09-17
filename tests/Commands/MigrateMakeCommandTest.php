<?php

use JHWelch\PestLaravelMigrations\Tests\CommandTestCase;

uses(CommandTestCase::class);

beforeEach(function () {
    $this->app->setBasePath(__DIR__.'/../test_data');
});

it('creates a test for the migration', function () {
    $this->artisan('make:migration', [
        'name' => '2024_09_12_000000_update_users_table_combine_names',
        '--test' => true,
    ]);

    $this->assertFileExists(__DIR__.'/../test_data/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});

it('behaves normally without the test flag', function () {
    $this->artisan('make:migration', [
        'name' => '2024_09_12_000000_update_users_table_combine_names',
    ]);

    $this->assertFileDoesNotExist(__DIR__.'/../test_data/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});
