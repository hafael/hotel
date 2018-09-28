# hafael/hotel

No docs, aqui é na unha!

### Warning
This package supports only Laravel 5.1+

### Installing

In order to install Hotel, run the following command into your laravel root folder:

```
composer require hafael/hotel
php artisan vendor:publish --provider="Hafael\Hotel\HotelServiceProvider"
```

After installing the package, you can now register it's provider into your config/app.php file:

```php
'providers' => [
    ...
    Hafael\Hotel\HotelServiceProvider::class,
]
```

### Usage

Like Laravel's default migrator, this one has some commands similar to the originals. To list the available options, you can see all available options using the `php artisan list` command.

```
tenantdb            Run the database migrations
tenantdb:fresh      Drop all tables and re-run all migrations
tenantdb:install    Create the migration repository
tenantdb:make       Create a new migration file
tenantdb:refresh    Reset and re-run all migrations
tenantdb:reset      Rollback all database migrations
tenantdb:rollback   Rollback the last database migration
tenantdb:status     Show the status of each migration
```

#### Creating Migrations

In order to generate an empty migration, run the following command example.

`php artisan tenantdb:make create_orders_table --create=orders`

This will create a migration class into the right directory.

To declare your table fields, just follow the usual schema build practices, this package don't make anything different there.

#### Running Migrations

In order to run an migration to all tenants, run the following command example.

`php artisan tenantdb:migrate --all`

`php artisan tenantdb:rollback --all`

In order to run an migration to single tenant, run the following command example.

`php artisan tenantdb:migrate --tenant=1001`

`php artisan tenantdb:rollback --tenant=1001`