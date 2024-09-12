<?php

use function JHWelch\PestLaravelMigrations\migration;

it('returns expected dataset format', function () {
    expect(migration('2024_09_12_144437_update_users_combine_names'))
        ->toBeArray()
        ->toHaveCount(2)
        ->{0}->toBeInstanceOf(Closure::class)
        ->{0}->toBeInstanceOf(Closure::class);
});
