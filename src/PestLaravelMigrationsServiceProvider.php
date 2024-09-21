<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Illuminate\Foundation\Console\TestMakeCommand as LaravelTestMakeCommand;
use Illuminate\Support\ServiceProvider;
use JHWelch\PestLaravelMigrations\Commands\MigrateMakeCommand;
use JHWelch\PestLaravelMigrations\Commands\TestMakeCommand;

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
        if (! config('pest-laravel-migrations.override_commands', false)) {
            return;
        }

        $this->overrideMigrationMakeCommand();
        $this->overrideTestMakeCommand();
    }

    public function overrideMigrationMakeCommand(): void
    {
        $this->app->extend(
            LaravelMigrateMakeCommand::class,
            fn ($_, array $app): MigrateMakeCommand => new MigrateMakeCommand(
                $app['migration.creator'],
                $app['composer']
            ));
    }

    private function overrideTestMakeCommand(): void
    {
        $this->app->extend(
            LaravelTestMakeCommand::class,
            fn ($_, $app): TestMakeCommand => new TestMakeCommand($app['files']),
        );
    }
}
