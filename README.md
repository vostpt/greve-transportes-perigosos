## VOST Portugal - Já Não Dá Para Abastecer - Version 2.0

## Project setup
Install dependencies:
```sh
composer install
```

Copy the `.env` file:
```sh
cp .env.example .env
```

Generate an encryption key:
```sh
php artisan key:generate
```

Finally create the symbolic link for Laravel's public disk:
```sh
php artisan storage:link
```

### Database

You'll need a MySQL database. The easiest way to get this up and running is using [Homestead installation per project](https://laravel.com/docs/5.8/homestead#per-project-installation).

After setting up the database, adapt the `.env` file accordingly and execute the migration and seeders:
```sh
php artisan migrate:refresh --seed
```

You can then run the file located in `example_data/homestead_fuel_stations.sql` to add fuel stations and have some test data.

Afterwards run the following command to create the cache file so the website is populated:
```sh
php artisan stations:cache
```

## Testing
To run the tests, execute:

```sh
vendor/bin/phpunit --dump-xdebug-filter xdebug-filter.php
vendor/bin/phpunit --prepend xdebug-filter.php
```

## Contributing
Contributions are always welcome, but before anything else, make sure you get acquainted with the [CONTRIBUTING](CONTRIBUTING.md) guide.

## Credits
- [VOST Portugal](https://github.com/vostpt)

## License
This project is open source software licensed under the [MIT LICENSE](LICENSE).
