# Pest Plugin for Laravel Migrations

```php
it('combines first_name and last_name into full_name', function () {
    [$up, $down] = migration('2024_09_12_144437_update_users_combine_names');
    $user = User::factory([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ])->create();

    $up();

    expect($user->fresh())
        ->full_name->toEqual('John Doe');

    $down();

    expect($user->fresh())
        ->first_name->toEqual('John')
        ->last_name->toEqual('Doe');
})
```
