<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use JHWelch\PestLaravelMigrations\Tests\CommandTestCase;

uses(CommandTestCase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::create(2024, 9, 17, 0, 0, 0));
});

it('creates a test for the migration', function () {
    $this->artisan('make:migration', [
        'name' => 'update_users_table_combine_names',
        '--test' => true,
    ])
        ->expectsOutputToContain('Test [tests/Migration/UpdateUsersTableCombineNamesTest.php] created successfully.');

    $this->assertFileExists(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});

it('behaves normally without the test flag', function () {
    $this->artisan('make:migration', [
        'name' => 'update_users_table_combine_names',
    ])
        ->doesntExpectOutputToContain('Test [tests/Migration/UpdateUsersTableCombineNamesTest.php] created successfully.');

    $this->assertFileDoesNotExist(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
});

it('uses migration test stub', function () {
    $this->artisan('make:migration', [
        'name' => 'update_users_table_combine_names',
        '--test' => true,
    ])->run();

    $this->assertFileExists(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
    $this->assertMatchesRegularExpression(
        '/migration(.*?)(?<!\\\\)update_users_table_combine_names/',
        file_get_contents(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php')
    );
});

describe('overridden migration', function () {
    beforeEach(function () {
        $stub = <<<'PHP'
        <?php

        // Testing migration stub
        migration('{{ MigrationName }}', function ($up, $down) {

        });
        PHP;

        if (! is_dir($stubsPath = app()->basePath('stubs'))) {
            (new Filesystem)->makeDirectory($stubsPath);
        }
        file_put_contents(self::TEST_DIRECTORY.'/stubs/pest.migration.stub', $stub);
    });

    it('uses overridden migration test stub', function () {
        $this->artisan('make:migration', [
            'name' => 'update_users_table_combine_names',
            '--test' => true,
        ])->run();

        $this->assertFileExists(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php');
        $this->assertMatchesRegularExpression(
            '/Testing migration stub/',
            file_get_contents(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php')
        );
        $this->assertMatchesRegularExpression(
            '/migration(.*?)(?<!\\\\)update_users_table_combine_names/',
            file_get_contents(self::TEST_DIRECTORY.'/tests/Migration/UpdateUsersTableCombineNamesTest.php')
        );
    });
});
