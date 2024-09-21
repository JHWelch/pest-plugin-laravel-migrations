<?php

use JHWelch\PestLaravelMigrations\Tests\CommandTestCase;

uses(CommandTestCase::class);

it('has migration option', function () {
    $this->artisan('make:test', [
        'name' => 'UpdateUsersTableCombineNamesTest',
        '--migration' => true,
    ])
        ->expectsOutputToContain('Test [tests/Migration/UpdateUsersTableCombineNamesTest.php] created successfully.');
});

it('uses migration stub', function () {
    $this->artisan('make:test', [
        'name' => 'UpdateUsersTableCombineNamesTest',
        '--migration' => true,
    ])->run();

    $this->assertFileExists(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
    $this->assertMatchesRegularExpression(
        '/migration/',
        file_get_contents(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php')
    );
});

it('can replace with migration name', function () {
    $this->artisan('make:test', [
        'name' => 'UpdateUsersTableCombineNamesTest',
        '--migration' => '2021_01_01_000000_create_users_table',
    ])->run();

    $this->assertFileExists(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
    $this->assertTrue(str_contains(
        file_get_contents(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php'),
        "migration('2021_01_01_000000_create_users_table', function"
    ));
});
