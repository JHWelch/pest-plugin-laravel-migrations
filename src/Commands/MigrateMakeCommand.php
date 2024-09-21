<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Illuminate\Support\Str;
use JHWelch\PestLaravelMigrations\Exceptions\OverrideCommandException;

class MigrateMakeCommand extends LaravelMigrateMakeCommand
{
    protected $signature = 'make:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--test : Create a new test for the migration}
        {--fullpath : Output the full path of the migration (Deprecated)}';

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool  $create
     * @return void
     */
    protected function writeMigration($name, $table, $create)
    {
        if ($this->option('test')) {
            $this->creator->afterCreate(
                fn ($_, $path) => $this->createMigrationTest($path)
            );
        }

        parent::writeMigration($name, $table, $create);
    }

    protected function createMigrationTest(string $path): void
    {
        $migration = basename($path, '.php');

        $testNameCamel = preg_replace('/\d+/', '', $migration);

        if (! $testNameCamel) {
            throw new OverrideCommandException;
        }

        $testName = Str::studly($testNameCamel).'Test';

        $this->call('make:test', [
            'name' => $testName,
            '--migration' => $migration,
        ]);
    }
}
