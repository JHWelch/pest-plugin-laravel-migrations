<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Closure;
use Pest\PendingCalls\TestCall;
use Pest\Support\HigherOrderTapProxy;

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

function testMigration(
    string $target,
    Closure $closure
): HigherOrderTapProxy|TestCall {
    return test(
        'Test migration: '.$target,
        fn () => $closure->bindTo($this, self::class)(...migration($target)),
    );
}
