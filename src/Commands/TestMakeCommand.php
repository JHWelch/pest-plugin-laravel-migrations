<?php

namespace JHWelch\PestLaravelMigrations\Commands;

use Illuminate\Foundation\Console\TestMakeCommand as LaravelTestMakeCommand;
use Override;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends LaravelTestMakeCommand
{
    /**
     * @return array
     */
    #[Override]
    protected function getOptions()
    {
        return [
            ...parent::getOptions(),
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a migration test'],
        ];
    }

    /**
     * @return string
     */
    #[Override]
    protected function getStub()
    {
        return $this->option('migration')
            ? $this->resolveMigrationStubPath('/stubs/pest.migration.stub')
            : parent::getStub();
    }

    /**
     * @return string
     */
    protected function resolveMigrationStubPath(string $stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }

    /**
     * @param  string  $rootNamespace
     * @return string
     */
    #[Override]
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->option('migration')
            ? $rootNamespace.'\Migration'
            : parent::getDefaultNamespace($rootNamespace);
    }

    /**
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    #[Override]
    protected function buildClass($name)
    {
        return $this->replaceMigrationName(parent::buildClass($name));
    }

    /**
     * @param  string  $stub
     * @return string
     */
    protected function replaceMigrationName($stub)
    {
        $migrationName = $this->option('migration');

        if (! $migrationName) {
            return $stub;
        }

        if ($migrationName === true) {
            $migrationName = 'update_table_add_column';
        }

        return str_replace(['{{ MigrationName }}', '{{ MigrationName }}'], $migrationName, $stub);
    }
}
