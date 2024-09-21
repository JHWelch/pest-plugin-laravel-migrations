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
