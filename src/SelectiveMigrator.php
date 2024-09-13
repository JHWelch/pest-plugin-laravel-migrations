<?php

namespace JHWelch\PestLaravelMigrations;

use Illuminate\Database\Migrations\Migrator;

class SelectiveMigrator
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

    protected function getMigrationPath(): string
    {
        return app()->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
