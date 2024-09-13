<?php

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\MigrationServiceProvider;

class PestLaravelMigrationServiceProvider extends MigrationServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();

        $this->registerMigrator();

        // $this->registerCreator();

        $this->registerMigrateCommand();
        $this->registerMigrateRollbackCommand();

        $this->commands([
            MigrateCommand::class,
            RollbackCommand::class,
        ]);

        $this->app->singleton(SelectiveMigrator::class, function ($app) {
            return new SelectiveMigrator($app['migrator']);
        });
    }
}
