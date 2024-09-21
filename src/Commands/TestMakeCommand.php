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
}
