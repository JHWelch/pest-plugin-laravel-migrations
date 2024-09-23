<?php

namespace JHWelch\PestLaravelMigrations\Tests\CommandRegressions;

use JHWelch\PestLaravelMigrations\Tests\TestCase as BaseTestCase;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

class TestCase extends BaseTestCase
{
    use InteractsWithPublishedFiles;

    protected const TEST_DIRECTORY = __DIR__.'/../test_data';

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->setBasePath(self::TEST_DIRECTORY);
        $this->app->useDatabasePath(self::TEST_DIRECTORY.'/database');
    }
}
