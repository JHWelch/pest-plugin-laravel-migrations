<?php

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\MigrationServiceProvider;

class PestLaravelMigrationServiceProvider extends MigrationServiceProvider
{
    public function register(): void
    {
        $this->registerRepository();

        $this->registerMigrator();

        $this->registerMigrateCommand();
        $this->registerMigrateRollbackCommand();

        $this->commands([
            MigrateCommand::class,
            RollbackCommand::class,
        ]);

        $this->app->singleton(
            SelectiveMigrator::class,
            fn ($app): SelectiveMigrator => new SelectiveMigrator($app['migrator'])
        );
    }
}
