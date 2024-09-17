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

        $this->mergeConfigFrom(
            __DIR__.'/../config/pest-laravel-migrations.php', 'pest-laravel-migrations'
        );
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->replaceCommands();

        $this->publishes([
            __DIR__.'/../config/pest-laravel-migrations.php' => config_path('pest-laravel-migrations.php'),
        ]);
    }

    public function replaceCommands(): void
    {
        array_map(fn ($command) => match ($command) {
            MigrateMakeCommand::class => $this->overrideMigrationMakeCommand(),
        }, config('pest-laravel-migrations.override_commands', []));
    }

    public function overrideMigrationMakeCommand(): void
    {
        $this->app->extend(LaravelMigrateMakeCommand::class, function ($command, $app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }
}
