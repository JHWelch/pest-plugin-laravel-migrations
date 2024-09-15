<?php

use Tests\Stubs\User;
use Tests\TestCase;

use function JHWelch\PestLaravelMigrations\migration;
use function JHWelch\PestLaravelMigrations\testMigration;

uses(TestCase::class);

it('combines first_name and last_name into full_name', function () {
    [$up] = migration('2024_09_12_000000_update_users_table_combine_names');

    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');
});

it('combines first_name and last_name into full_name and can revert', function () {
    [$up, $down] = migration('2024_09_12_000000_update_users_table_combine_names');

    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');

    $down();

    expect($user->fresh())
        ->first_name->toEqual('John')
        ->last_name->toEqual('Doe');
});

// testMigration('2024_09_12_000000_update_users_table_combine_names', function ($up) {
//     [$up] = migration('2024_09_12_000000_update_users_table_combine_names');

//     $user = User::create([
//         'first_name' => 'John',
//         'last_name' => 'Doe',
//     ]);

//     $up();

//     expect($user->fresh())
//         ->full_name->toEqual('John Doe');
// });

testMigration('2024_09_12_000000_update_users_table_combine_names', function ($up, $down) {
    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');

    $down();

    expect($user->fresh())
        ->first_name->toEqual('John')
        ->last_name->toEqual('Doe');
});
