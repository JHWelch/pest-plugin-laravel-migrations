# Pest Plugin for Laravel Migrations

A Pest PHP plugin that lets you test Laravel migrations with a simple and straight forward syntax.

```php
testMigration('2024_09_12_000000_update_users_table_combine_names', function ($up, $down) {
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

With Pest and other modern tooling, it is easy to test almost every aspect of a Laravel application. However, one of the places with the greatest permanent data implications remains untested. 

_Database migrations._

Any given test is run against the latest state of a database. This makes it difficult to test any migration that is doing any sort of complicated data migration. 

This package seeks to fill that gap.

## This package is a **Work In Progress**

Once the package is marked stable it will abide by [Semver](https://semver.org/), however in this period the API is not guaranteed to maintain consistent.

## Usage

This package consists of two Pest functions: `testMigration` and `migration`. 

### `testMigration`

`testMigration` is the most straight forward way to write a migration test. 

All migrations are run up until the target. The target is then migrated on `$up()` and rolled back with `$down()`, allowing for setup and assertions at each step.

```php
use function JHWelch\PestLaravelMigrations\testMigration;

testMigration('2024_09_12_000000_migration_name', ($up, $down) {
    // Setup test Data
    // All migrations up until target have been run

    $up();

    // Run Assertions after migration "up"

    $down();

    // Run Assertions after migration "down"
});
```


### `migration`

This function is the core of the functionality that `testMigration` is wrapping, but allows for more customizability.

It returns a two item array with an `$up` and `$down` `Closure`s that trigger each half of the migration.

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

### More Examples

For more realistic examples see [ExampleUsageTest.php](tests/ExampleUsageTest.php).
