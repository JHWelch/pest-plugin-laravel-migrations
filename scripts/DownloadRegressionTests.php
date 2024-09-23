<?php

require __DIR__.'/../vendor/autoload.php';

$commands = [
    [
        'input' => 'https://raw.githubusercontent.com/laravel/framework/11.x/tests/Integration/Generators/MigrateMakeCommandTest.php',
        'output' => 'tests/CommandRegressions/MigrateMakeCommandTest.php',
    ],
    [
        'input' => 'https://raw.githubusercontent.com/laravel/framework/11.x/tests/Integration/Generators/TestMakeCommandTest.php',
        'output' => 'tests/CommandRegressions/TestMakeCommandTest.php',
    ],
];

echo 'Downloading overridden command test files from Laravel...';

$client = new \GuzzleHttp\Client;

foreach ($commands as $command) {
    echo "Downloading {$command['input']}...";
    $commandContent = $client->get($command['input'])->getBody()->getContents();

    $replacedNamespace = str_replace(
        'Illuminate\Tests\Integration\Generators',
        'JHWelch\PestLaravelMigrations\Tests\CommandRegressions',
        $commandContent,
    );

    echo "Placing in {$command['output']}...";

    file_put_contents($command['output'], $replacedNamespace);
}
