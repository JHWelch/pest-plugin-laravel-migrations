<?php

use Illuminate\Filesystem\Filesystem;
use JHWelch\PestLaravelMigrations\Tests\CommandTestCase;

uses(CommandTestCase::class);

describe('stub folder does not exist', function () {
    it('publishes migration stub', function () {
        $this->artisan('pest-migrations:stubs')
            ->expectsOutputToContain('Stubs published successfully.');

        $this->assertFileExists(app()->basePath('stubs/pest.migration.stub'));
    });
});

describe('stub folder exists', function () {
    beforeEach(function () {
        (new Filesystem)->makeDirectory(app()->basePath('stubs'));
    });

    it('publishes migration stub', function () {
        $this->artisan('pest-migrations:stubs')
            ->expectsOutputToContain('Stubs published successfully.');

        $this->assertFileExists(app()->basePath('stubs/pest.migration.stub'));
    });
});
