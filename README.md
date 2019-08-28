# Eloquent Companion

A quintessential package containing companion helper functions to extend the usefulness of Laravel's Eloquent ORM

- [Installation](#installation)
- [Usage](#usage)

## Installation

To install through composer you can either use `composer require ferrisbane/eloquent-companion` (while inside your project folder) or include the package in your `composer.json`.

```php
"ferrisbane/eloquent-companion": "0.1.*"
```

Then run either `composer install` or `composer update` to download the package.

To use the package with Laravel 5 add the service provider to the list of service providers in `config/app.php`.

```php
'providers' => [
    ...

    Ferrisbane\EloquentCompanion\Laravel5ServiceProvider::class

    ...
];
```

## Usage

Once the service provider has been added to your projects provider list the companion helpers are ready to use.

### withWhereHas

The withWhereHas helper is useful if you wish to run a `->whereHas()` query on your model and also want to eager load the same relationship without writing the same query in the `->with()` function. Using `->withWhereHas()` keeps your code clean and DRY!

For example if you have a user model and want to return only users that have paid order AND also eager load the paid orders: 
```php
User::where('active', true)
    ->withWhereHas('orders', function($query) {
        $query->where('paid', true);
    })
    ->get();
```

As a comparison in standard Eloquent you would have to write the query out twice, which can become messy for larger queries and changes to one query will have to be replicated again:
```php
User::where('active', true)
    ->whereHas('orders', function($query) {
        $query->where('paid', true);
    })
    ->with([
        'orders' => function($query) {
            $query->where('paid', true);
        }
    ])
    ->get();
```

### toQuery

When writing Eloquent queries it can be useful to see what SQL query it will run.
Eloquent provides the `->toSql()` function to help you with that, however any bindings wont be populated in the output. The `->toQuery()` function is here to help!

```php
User::where('email', 'john@example.com')
    ->toQuery();
```

Taking a look at the above query the standard `->toSql()` function would return: `SELECT * FROM users WHERE email = ?`. We cant just copy and paste that output into our favorite database client.

However with the `->toQuery()` function we can, as it will output: `SELECT * FROM users where email = 'john@example.com'`. This is extremely useful when debugging those big queries that can contain 10+ bindings.