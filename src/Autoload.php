<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Closure;

/**
 * @return array<Closure>
 */
function migration(string $name): array
{
    $manager = app(SelectiveMigrator::class)->makeMigrationTestManager($name);

    $manager->start();

    return [
        fn () => $manager->up(),
        fn () => $manager->down(),
    ];
}
