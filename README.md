## VOST Portugal - Já Não Dá Para Abastecer - Version 2.0

> **Legacy baseline.** This branch is Laravel 5.8 / PHP 7.4. The modern rewrite lives on the `v3` branch (Laravel 11 / PHP 8.3). See `/Users/tomahock/.claude/plans/isto-um-projeto-rippling-shell.md` for the migration roadmap.

## Running with Docker (recommended)

Three services: `app` (PHP 7.4-fpm-alpine), `nginx` (:8080), `db` (MariaDB 10.3). Uses **Composer 1** inside the image because Laravel 5.8.12 predates the Composer 2 `installed.json` format.

```sh
docker compose up -d
docker compose exec app composer install
docker compose exec app cp .env.docker .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

App at http://localhost:8080. MariaDB exposed on host port `3307` (creds `greve` / `greve`).

## Running without Docker

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
This project is open source software licensed under the [MIT LICENSE](LICENSE).
