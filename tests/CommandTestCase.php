<?php

namespace JHWelch\PestLaravelMigrations\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CommandTestCase extends TestCase
{
    protected const TEST_DIRECTORY = __DIR__.'/test_data';

    protected function setUp(): void
    {
        parent::setUp();

        app()->useDatabasePath(__DIR__.'/test_data/database');

        $this->clearTestData();
    }

    protected function clearTestData(): void
    {

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::TEST_DIRECTORY)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() !== '.gitignore') {
                unlink($file->getPathname());
            }
        }
    }
}
