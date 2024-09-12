<?php

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function JHWelch\PestLaravelMigrations\migration;

uses(TestCase::class);

it('runs only the migrations before the given migration', function ($up, $_) {
    $up();

    $migrations = DB::table('migrations')->get();

    expect($migrations)
        ->toHaveCount(2)
        ->{0}->name->toEqual('2014_10_12_000000_create_users_table')
        ->{1}->name->toEqual('2019_12_14_000001_create_personal_access_tokens_table');
})->with(migration('2024_09_12_000000_update_users_table_combine_names'));
