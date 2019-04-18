## VOST Portugal - Já Não Dá Para Abastecer

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

Generate JWT secret key:
```sh
php artisan jwt:secret
```

### Database
Execute the migration and seeders:
```sh
php artisan migrate:refresh --seed
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
This project is open source software licensed under the [MIT LICENSE](LICENSE.md).