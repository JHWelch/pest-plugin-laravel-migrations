# Pest Plugin for Laravel Migrations

```php
it('combines first_name and last_name into full_name', function (Closure $up, Closure $down) {
    $user = User::factory([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ])->create();

    $up();

    expect($user->full_name)->toEqual('John Doe');

    $down();

    expect($user)
        ->first_name->toEqual('John')
        ->last_name->toEqual('Doe');
})->migration('2024_09_12_144437_update_users_combine_names');
```
