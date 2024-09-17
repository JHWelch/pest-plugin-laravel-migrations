<?php

namespace JHWelch\PestLaravelMigrations\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
        parent::writeMigration($name, $table, $create);

        if ($this->option('test')) {
            $this->createMigrationTest($name);
        }
    }

    protected function createMigrationTest($name)
    {
        $name = Str::studly(preg_replace('/[0-9]+/', '', $name)).'Test';

        Artisan::call("make:test Tests/Migration/$name");
    }
}
