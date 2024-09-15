<?php

use JHWelch\PestLaravelMigrations\App\Models\User;
use JHWelch\PestLaravelMigrations\Tests\TestCase;

use function JHWelch\PestLaravelMigrations\migration;
use function JHWelch\PestLaravelMigrations\migrationFunctions;

uses(TestCase::class);

it('combines first_name and last_name into full_name', function () {
    [$up] = migrationFunctions('2024_09_12_000000_update_users_table_combine_names');

    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');
});

it('combines first_name and last_name into full_name and can revert', function () {
    [$up, $down] = migrationFunctions('2024_09_12_000000_update_users_table_combine_names');

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

// migration('2024_09_12_000000_update_users_table_combine_names', function ($up) {
//     [$up] = migrationFunctions('2024_09_12_000000_update_users_table_combine_names');

//     $user = User::create([
//         'first_name' => 'John',
//         'last_name' => 'Doe',
//     ]);

//     $up();

//     expect($user->fresh())
//         ->full_name->toEqual('John Doe');
// });

migration('2024_09_12_000000_update_users_table_combine_names', function ($up, $down) {
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
