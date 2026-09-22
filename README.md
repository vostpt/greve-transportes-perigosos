# VOST Portugal — Já Não Dá Para Abastecer (v3)

Crowd-sourced map of fuel-station availability in Portugal during hazardous-materials driver strikes. Users report gasoline/diesel/LPG availability at each station; consensus among distinct IPs auto-promotes the report to public state. Third parties can consume the same data through a key/secret API.

**Stack:** Laravel 12 · PHP 8.4 · MariaDB 10.11 LTS · Redis 7 · Vite + Bootstrap 5 · Mapbox GL 3.

**Legacy:** the 2019 Laravel 5.8 codebase lives on branch `master`. This branch (`v3`) is the ground-up rewrite, tracked in `/Users/tomahock/.claude/plans/isto-um-projeto-rippling-shell.md`.

## Running with Docker

```sh
docker compose up -d
docker compose exec app cp .env.docker .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

App at http://localhost:8080.

Host ports:

| Service | Port |
|---------|------|
| nginx | 8080 |
| MariaDB | 3308 (creds `greve` / `greve`) |
| Redis | 6380 |
| Vite dev server | 5173 (`docker compose --profile dev up node`) |

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md).

## License

MIT — see [LICENSE](LICENSE).
