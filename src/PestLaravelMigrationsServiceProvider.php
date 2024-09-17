<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Illuminate\Support\ServiceProvider;
use JHWelch\PestLaravelMigrations\Commands\MigrateMakeCommand;

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

    public function boot(): void
    {
        $this->replaceCommands();
    }

    public function replaceCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->app->extend(LaravelMigrateMakeCommand::class, function ($command, $app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }
}
