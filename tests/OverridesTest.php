<?php

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use JHWelch\PestLaravelMigrations\Commands\MigrateMakeCommand;
use JHWelch\PestLaravelMigrations\Tests\TestCase;

uses(TestCase::class);

describe('MigrateMakeCommand', function () {
    it('matches existing signature with only test added', function () {
        $creator = invade(app(LaravelMigrateMakeCommand::class))->creator;
        $composer = invade(app(LaravelMigrateMakeCommand::class))->composer;
        $baseSignature = invade(new LaravelMigrateMakeCommand($creator, $composer))->signature;
        $overriddenSignature = invade(new MigrateMakeCommand($creator, $composer))->signature;
        $addedCommands = "        {--test : Create a new test for the migration}\n";

        $this->assertEquals(
            $baseSignature,
            str_replace($addedCommands, '', $overriddenSignature),
        );
    });
});
