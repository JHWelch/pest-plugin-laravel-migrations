<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Support\ServiceProvider;

final class PestLaravelMigrationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SelectiveMigrator::class,
            fn ($app): SelectiveMigrator => new SelectiveMigrator($app['migrator'])
        );
    }
}
