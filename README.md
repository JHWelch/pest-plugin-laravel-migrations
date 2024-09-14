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

This package is a **Work In Progress**. Once the package is tagged 1.0.0 it will abide by [Semver](https://semver.org/), however in this period the API is not guaranteed to maintain consistent.

Please lock any usage to the minor version, and be wary about any production usage.

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
