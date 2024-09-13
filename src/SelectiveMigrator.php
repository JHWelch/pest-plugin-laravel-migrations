<?php

declare(strict_types=1);

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Migrations\Migrator;

final class SelectiveMigrator
{
    public function __construct(
        protected Migrator $migrator,
    ) {}

    public function makeMigrationTestManager(string $target): MigrationTestManager
    {
        $this->migrator->getRepository()->createRepository();

        $migrations = array_keys(
            $this->migrator->getMigrationFiles($this->getMigrationPaths())
        );

        return new MigrationTestManager($target, $migrations);
    }

    /**
     * @return string[]
     */
    public function getMigrationPaths(): array
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
