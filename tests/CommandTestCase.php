<?php

namespace JHWelch\PestLaravelMigrations\Tests;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CommandTestCase extends TestCase
{
    protected const TEST_DIRECTORY = __DIR__.'/../test_data';

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->setBasePath(self::TEST_DIRECTORY);
        $this->app->useDatabasePath(self::TEST_DIRECTORY.'/database');
    }

    protected function clearTestData(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::TEST_DIRECTORY, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() !== '.gitignore') {
                unlink($file->getPathname());
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            }
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->clearTestData();
    }
}
