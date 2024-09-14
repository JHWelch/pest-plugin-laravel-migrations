# Pest Plugin for Laravel Migrations

A Pest PHP plugin that lets you test Laravel migrations with a simple and straight forward syntax.

```php
it('combines first_name and last_name into full_name', function () {
    [$up, $down] = migration('2024_09_12_000000_update_users_table_combine_names');

    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');

    $down();

    expect($user->fresh())
        ->first_name->toEqual('John')
        ->last_name->toEqual('Doe');
});
```

With Pest and other modern tooling, it is easy to test almost every aspect of a Laravel application. However, one of the places with the greatest permanent data implications remains untested. Database migrations.

Any given test is run against the latest state of a database. This makes it difficult to test any migration that is doing any sort of complicated data migration. 

This package seeks to fill that gap.

## This package is a **Work In Progress**
Once the package is tagged 1.0.0 it will abide by [Semver](https://semver.org/), however in this period the API is not guaranteed to maintain consistent.

Please lock any usage to the minor version, and be wary about any production usage.

## Usage

This package consists of the single Pest function `migration`. It returns a two item array with an `$up` and `$down` `Closure`s that trigger each half of the migration.

The easiest way to use this is to destructure the array.

```php
use function JHWelch\PestLaravelMigrations\migration;

it('tests migrations', () {
    # Test only "up"
    [$up] = migration('2024_09_12_000000_migration_name');
    
    # Test both "up" and "down"
    [$up, $down] = migration('2024_09_12_000000_migration_name');
});
```

This can be then used to assert against each step of the migration

```php
use function JHWelch\PestLaravelMigrations\migration;

it('tests migrations', () {
    [$up, $down] = migration('2024_09_12_000000_migration_name');

    // Setup test Data
    // All migrations up until target have been run

    $up();

    // Run Assertions after migration "up"

    $down()

    // Run Assertions after migration "down"
});
```

For more realistic examples see [ExampleUsageTest.php](tests/ExampleUsageTest.php).

## Development 

This package is developed locally with [Laravel Herd](https://herd.laravel.com/), however any modern PHP environment should suffice.

### Install

```sh
composer install
```

### Testing/QC 

Unit tests, static types, and code style are enforced via [GitHub Workflows](./.github/workflows).

Each can be run locally while.

#### Run All Tests

```sh
composer test
```

#### Run Pest Unit Tests

```sh
composer test:unit
```

#### Run PHPStan Static Analysis

```sh
composer test:types
```

#### Run Rector (Automated Refactoring)

```sh
composer test:refacto
```

#### Run Code Style Linter
```sh
# Dry Run
composer test:lint

# Apply Linter
composer lint
```
