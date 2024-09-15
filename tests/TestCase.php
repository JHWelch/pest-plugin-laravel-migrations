<?php

namespace JHWelch\PestLaravelMigrations\Tests;

use Illuminate\Database\MigrationServiceProvider;
use JHWelch\PestLaravelMigrations\PestLaravelMigrationsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            MigrationServiceProvider::class,
            PestLaravelMigrationsServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        app()->useDatabasePath(__DIR__.'/../resources/database');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
