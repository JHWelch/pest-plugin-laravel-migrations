<?php

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrationTestManager
{
    /**
     * @param  string[]  $migrations
     */
    public function __construct(
        protected string $target,
        protected array $migrations,
    ) {}

    public function start(): void
    {
        $migrations = $this->migrationsAfterTarget();

        DB::table('migrations')
            ->insert(array_map(fn ($migration) => [
                'migration' => $migration,
                'batch' => 1,
            ], [$this->target, ...$migrations]));

        Artisan::call('migrate');

        DB::table('migrations')
            ->where('migration', $this->target)
            ->delete();
    }

    public function up(): void
    {
        Artisan::call('migrate');
    }

    public function down(): void {}

    protected function migrationsAfterTarget(): array
    {
        $valuesAfter = [];
        $found = false;
        foreach ($this->migrations as $item) {
            if ($found) {
                $valuesAfter[] = $item;
            } elseif ($item === $this->target) {
                $found = true;
            }
        }

        return $valuesAfter;
    }
}
