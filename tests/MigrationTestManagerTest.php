<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JHWelch\PestLaravelMigrations\Exceptions\MigrationTestUsageException;
use JHWelch\PestLaravelMigrations\MigrationTestManager;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->allMigrations = [
        '2014_10_12_000000_create_users_table',
        '2019_12_14_000001_create_personal_access_tokens_table',
        '2024_09_12_000000_update_users_table_combine_names',
        '2024_09_12_000001_update_users_table_remove_name_entirely',
        '2024_09_12_000002_create_teams_table',
    ];

    $this->manager = new MigrationTestManager(
        '2024_09_12_000000_update_users_table_combine_names',
        $this->allMigrations,
    );
});

describe('start', function () {
    beforeEach(function () {
        app('migrator')->getRepository()->createRepository();
    });

    it('fills migration table with all tests but the target', function () {
        $this->manager->start();

        $migrations = DB::table('migrations')
            ->pluck('migration')
            ->toArray();
        expect($migrations)
            ->toHaveCount('4')
            ->toMatchArray([
                '2024_09_12_000001_update_users_table_remove_name_entirely',
                '2024_09_12_000002_create_teams_table',
                '2014_10_12_000000_create_users_table',
                '2019_12_14_000001_create_personal_access_tokens_table',
            ]);
    });

    it('does not actually run the migrations after the target', function () {
        $this->manager->start();

        $this->assertFalse(Schema::hasTable('teams'));
    });

    it('does run the migrations before the target', function () {
        $this->manager->start();

        $this->assertTrue(Schema::hasTable('users'));
    });
});

describe('up', function () {
    beforeEach(function () {
        app('migrator')->getRepository()->createRepository();

        $this->manager->start();
    });

    it('runs the target migration', function () {
        $this->manager->up();

        $this->assertDatabaseHas('migrations', [
            'migration' => '2024_09_12_000000_update_users_table_combine_names',
            'batch' => 3,
        ]);
        $this->assertDatabaseCount('migrations', 5);
    });
});

describe('down', function () {
    beforeEach(function () {
        app('migrator')->getRepository()->createRepository();

        $this->manager->start();
        $this->manager->up();
    });

    it('rolls back the target migration', function () {
        $this->manager->down();

        $this->assertDatabaseMissing('migrations', [
            'migration' => '2024_09_12_000000_update_users_table_combine_names',
            'batch' => 3,
        ]);
        $this->assertDatabaseCount('migrations', 4);
    });
});

describe('error handling', function () {
    it('cannot run up without starting', function () {
        $this->manager->up();
    })->throws(
        MigrationTestUsageException::class,
        'MigrationTestManager must be started before up() can be called',
    );

    it('cannot run down without starting', function () {
        $this->manager->down();
    })->throws(
        MigrationTestUsageException::class,
        'MigrationTestManager must be started before down() can be called',
    );

    it('cannot run down without running up', function () {
        app('migrator')->getRepository()->createRepository();
        $this->manager->start();

        $this->manager->down();
    })->throws(
        MigrationTestUsageException::class,
        '$up() must be called before $down() can be called',
    );

    it('cannot target a migration that does not exist', function () {
        $this->manager = new MigrationTestManager(
            'fake_migration',
            $this->allMigrations,
        );

        $this->manager->start();
    })->throws(
        MigrationTestUsageException::class,
        'Migration "fake_migration" does not exist',
    );
});
