<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Support\ServiceProvider;

final class PestLaravelMigrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        $this->app->singleton(
            MigrationTestMigrator::class,
            fn ($app): MigrationTestMigrator => new MigrationTestMigrator($app['migrator'])
        );
    }
}
