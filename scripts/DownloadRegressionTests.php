<?php

require __DIR__.'/../vendor/autoload.php';

const GITHUB_BASE_URL = 'https://raw.githubusercontent.com/laravel/framework/11.x/';

$commands = [
    [
        'input' => 'tests/Integration/Generators/MigrateMakeCommandTest.php',
        'output' => 'tests/CommandRegressions/MigrateMakeCommandTest.php',
    ],
    [
        'input' => 'tests/Integration/Generators/TestMakeCommandTest.php',
        'output' => 'tests/CommandRegressions/TestMakeCommandTest.php',
    ],
];

echo 'Downloading overridden command test files from Laravel...'.PHP_EOL;

$client = new \GuzzleHttp\Client([
    'base_uri' => GITHUB_BASE_URL,
]);

foreach ($commands as $command) {
    echo "Downloading {$command['input']}...".PHP_EOL;
    $commandContent = $client->get($command['input'])->getBody()->getContents();

    $replacedNamespace = str_replace(
        'Illuminate\Tests\Integration\Generators',
        'JHWelch\PestLaravelMigrations\Tests\CommandRegressions',
        $commandContent,
    );

    echo "Placing in {$command['output']}...".PHP_EOL;

    file_put_contents($command['output'], $replacedNamespace);
}

echo 'Done.'.PHP_EOL;
