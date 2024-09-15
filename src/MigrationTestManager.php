<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use JHWelch\PestLaravelMigrations\Exceptions\MigrationTestUsageException;

final class MigrationTestManager
{
    private bool $started = false;

    private bool $upped = false;

    /**
     * @param  string[]  $migrations
     */
    public function __construct(
        private readonly string $target,
        private readonly array $migrations,
    ) {}

    public function start(): void
    {
        if ($this->started) {
            return;
        }

        $migrations = $this->migrationsAfterTarget();

        DB::table('migrations')
            ->insert(array_map(fn ($migration): array => [
                'migration' => $migration,
                'batch' => 1,
            ], [$this->target, ...$migrations]));

        Artisan::call('migrate');

        DB::table('migrations')
            ->where('migration', $this->target)
            ->delete();

        $this->started = true;
    }

    public function up(): void
    {
        if (! $this->started) {
            throw new MigrationTestUsageException(
                'MigrationTestManager must be started before up() can be called',
            );
        }

        Artisan::call('migrate');

        $this->upped = true;
    }

    public function down(): void
    {
        if (! $this->started) {
            throw new MigrationTestUsageException(
                'MigrationTestManager must be started before down() can be called',
            );
        }

        if (! $this->upped) {
            throw new MigrationTestUsageException(
                '$up() must be called before $down() can be called',
            );
        }

        Artisan::call('migrate:rollback');

        $this->upped = false;
    }

    /**
     * @return string[]
     */
    private function migrationsAfterTarget(): array
    {
        $migrationsAfter = [];
        $found = false;
        foreach ($this->migrations as $migration) {
            if ($found) {
                $migrationsAfter[] = $migration;
            } elseif ($migration === $this->target) {
                $found = true;
            }
        }

        if (! $found) {
            throw new MigrationTestUsageException(
                "Migration \"$this->target\" does not exist",
            );
        }

        return $migrationsAfter;
    }
}
