<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Closure;
use JHWelch\PestLaravelMigrations\Exceptions\MigrationTestUsageException;
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
    return test('Test migration: '.$target, function () use ($target, $closure) {
        $incompatibleTraits = [
            \Illuminate\Foundation\Testing\DatabaseMigrations::class,
            \Illuminate\Foundation\Testing\DatabaseTransactions::class,
            \Illuminate\Foundation\Testing\DatabaseTruncation::class,
            \Illuminate\Foundation\Testing\LazilyRefreshDatabase::class,
            \Illuminate\Foundation\Testing\RefreshDatabase::class,
        ];

        if ($intersect = array_intersect(class_uses($this), $incompatibleTraits)) {
            throw new MigrationTestUsageException(
                'The following traits are incompatible with the `testMigration` usage: '.implode(', ', $intersect)
            );
        }

        return $closure->bindTo($this, self::class)(...migration($target));
    });
}
