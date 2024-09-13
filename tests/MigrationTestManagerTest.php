<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
    });
});
