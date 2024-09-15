<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Artisan;

final class MigrationTestMigrator
{
    public function __construct(
        protected Migrator $migrator,
    ) {}

    public function makeMigrationTestManager(string $target): MigrationTestManager
    {
        $this->clearDatabase();
        $this->createMigrationTable();

        $migrations = array_keys(
            $this->migrator->getMigrationFiles($this->getMigrationPaths())
        );

        return new MigrationTestManager($target, $migrations);
    }

    private function clearDatabase(): void
    {
        Artisan::call('db:wipe');
    }

    private function createMigrationTable(): void
    {
        $this->migrator->getRepository()->createRepository();
    }

    /**
     * @return string[]
     */
    private function getMigrationPaths(): array
    {
        return [
            ...$this->migrator->paths(),
            $this->getMigrationPath(),
        ];
    }

    private function getMigrationPath(): string
    {
        return app()->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
