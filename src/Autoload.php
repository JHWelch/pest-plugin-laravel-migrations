<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Closure;

/**
 * @return array{Closure(): void, Closure(): void}
 */
function migration(string $target): array
{
    $manager = app(MigrationTestMigrator::class)
        ->makeMigrationTestManager($target);

    $manager->start();

    return [
        fn () => $manager->up(),
        fn () => $manager->down(),
    ];
}
