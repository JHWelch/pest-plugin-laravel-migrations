<?php

use Illuminate\Support\Facades\Schema;
use JHWelch\PestLaravelMigrations\Tests\TestCase;

use function JHWelch\PestLaravelMigrations\migration;

uses(TestCase::class);

it('can run and rollback migration', function () {
    [$up, $down] = migration('2024_09_12_000000_update_users_table_combine_names');

    $this->assertTrue(Schema::hasColumn('users', 'first_name'));
    $this->assertFalse(Schema::hasColumn('users', 'full_name'));
    $this->assertFalse(Schema::hasTable('teams'));

    $up();

    $this->assertTrue(Schema::hasColumn('users', 'full_name'));
    $this->assertFalse(Schema::hasColumn('users', 'first_name'));
    $this->assertFalse(Schema::hasTable('teams'));

    $down();

    $this->assertTrue(Schema::hasColumn('users', 'first_name'));
    $this->assertFalse(Schema::hasColumn('users', 'full_name'));
    $this->assertFalse(Schema::hasTable('teams'));
});
