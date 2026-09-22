# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**VOST Portugal — "Já Não Dá Para Abastecer" v2.0**: a crowd-sourced map of Portuguese fuel-station availability, originally built to track supply during the 2019 hazardous-materials drivers' strike (*greve dos motoristas de matérias perigosas*). Users report whether a station has gasoline/diesel/LPG; consensus of N unique-IP reports auto-promotes to public state. A back-office panel lets operators moderate reports, manage stations, and grant external partners API access.

Legacy stack — pinned to old versions on purpose:

- **Laravel 5.8** on **PHP ^7.1.3** (composer.json)
- MySQL/MariaDB via Eloquent
- **Laravel Mix 4** (webpack) + Bootstrap 4 + jQuery + Vue 2 + DataTables for the panel; the public map is a Blade view driven by a static JSON snapshot
- Optional Cloudflare cache purging is hard-coded into the write path (see below)

## Commands

Everything is run from this directory. There is no docker-compose here — you install PHP 7.1+/Composer/Node locally, or use Homestead.

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:refresh --seed      # rebuilds DB, seeds are minimal (see below)
php artisan serve                        # or point a vhost at public/
```

Frontend assets (via Laravel Mix, entrypoints wired in `webpack.mix.js`):

```sh
npm install
npm run dev        # or: watch, hot, prod
```

Coding style — **PSR-2** plus the strict rules in `.php_cs.dist` (see "Coding style gotchas" below):

```sh
composer cs-check              # dry-run, used by CI and the pre-commit hook
composer cs-fix <path>         # apply fixes
cp pre-commit.sh .git/hooks/pre-commit   # install the CS pre-commit hook
```

Tests (PHPUnit 7.5 — note `tests/` currently contains only `ExampleTest.php`):

```sh
vendor/bin/phpunit
vendor/bin/phpunit tests/Feature/ExampleTest.php    # single file
vendor/bin/phpunit --filter testMethodName          # single test
```

## Architecture — the parts that need multi-file reading

### Domain: Entries → Observer → FuelStation

The heart of the app is the crowd-sourced promotion pipeline:

1. **`App\Entry`** (`app/Entry.php`) — a single anonymous report (`fuel_station`, `has_gasoline`, `has_diesel`, `has_lpg`, `ip`, `used`). Rich query scopes: `related`, `relatedNoIP`, `lastHour`, `lastDay`, `notUsed`, `used`.
2. **`App\Observers\EntryObserver::created`** (registered in `AppServiceProvider::boot`) — on every new Entry, counts how many *distinct-IP* reports in the last hour match `(fuel_station, has_gasoline, has_diesel, has_lpg)` excluding the submitter's IP. When it hits the `num_entries_required` Option (default 10), it calls `$entry->push()`.
3. **`Entry::push()`** — marks all matching entries `used = 1`, updates the `FuelStation`, then re-generates the public JSON cache via `CacheController::updateStations()`.

Manual promotion by operators goes through `EntriesController::push` (`/panel/entries/push`) and reaches the same `Entry::push()` path.

**Important**: `EntriesController::add` (the public submission endpoint at `/panel/entries/add`) is currently short-circuited — line 21 returns `['success' => 0]` before any validation runs. The captcha/reCAPTCHA v3 logic below that line is unreachable dead code. Do not "fix" this by removing the early return without checking with the user — it's a deliberate lockdown.

### Public map is served from a static JSON snapshot

The `/` route renders `map.blade.php`, which loads `/storage/data/cache.json` — a full dump of `fuel_stations` regenerated on **every write** by `CacheController::updateStations()`. Same pattern for stats (`stats_global.json`, `stats_entries.json`, per-district / per-county / per-brand files, `places.json`, `stats_entries_hourly.json`, `stats_entries_last12.json`).

Anywhere you mutate a `FuelStation`, you **must** call `(new CacheController)->updateStations()` — the DB and the served JSON are otherwise out of sync. `updateStats()` is the more expensive rebuild used for the stats pages.

Every JSON write is followed by a Cloudflare purge of that specific URL. That's gated by `CLOUDFLARE_API_ENABLE=true` in `.env`. `clearCloudflare()` also `usleep(500ms)` between calls to stay under Cloudflare rate limits — do not remove that sleep.

### The lat/long swap

`fuel_stations.long` and `fuel_stations.lat` are **stored swapped**. Every code path that touches coordinates for external output flips them back:

- `APIController::fetch` writes `'lat' => $station->long, 'long' => $station->lat` (lines 56–57).
- `APIController::add`/`change` write `'long' => floatval($lat), 'lat' => floatval($long)` (lines 171–172, 247–248).
- `fetch_csv`/`fetch_api_csv` swap `long` and `lat` on each row before writing.

This is intentional — the DB was seeded with swapped columns and nothing has migrated it. If you touch any coordinate-handling code, preserve the swap. Fixing the schema is a bigger project (needs a migration + updating every consumer + the seeded snapshot).

### External partner API (`/api/v1/...`)

`APIController` implements a key/secret pair auth via the `external_auth` table (`App\ExternalAuth`, soft-deleted). Each row has a `brand` field with two magic values:

- `READONLY` — can fetch **all** stations, cannot write.
- `WRITEREAD` — can read/write any station and can add stations under any brand.
- Any other value — treated as a brand name; the caller can read/write only stations whose `brand` or `brand_management` matches.

Endpoints (all POST unless noted): `info`, `fetch`, `push` (update availability), `add`, `change`. Plus `GET fetchcsv` (public, full station dump) and `GET fetchapicsv?password=…` (guarded by `API_V1_DOWNLOAD_API_CSV` env var, filters to `source_id = 'api'`).

**No CSRF/route middleware group** — the routes are declared in `routes/web.php`, not `routes/api.php`. Do not casually move them; the panel and API share session-cookie assumptions.

### Options table = feature flags

`App\Option` is a keyed KV store with a string primary key on `name` (no auto-increment). Seeded values (see `2019_07_25_212225_create_options_values.php`):

- `num_entries_required` — consensus threshold in `EntryObserver`, default `10`.
- `stress_lockdown` — when `!= 0`, disables the heavy stations-list panel view (`FuelStationsController::list`). Toggle it in the Options panel when the site is under load.

Anything you add here needs both a migration insert and a UI row in `resources/views/options`.

### Panel routes vs public routes

All operator screens live under `/panel/*` and require `auth` middleware (declared per group in `routes/web.php`). Auth uses Laravel's stock scaffolding with `register` disabled and `verify` enabled — new operators are created via `UsersController@create` inside the panel (`/panel/users/add`) and must be marked verified by an existing admin.

## Coding style gotchas (enforced by `.php_cs.dist`)

The CS rules are strict and easy to violate accidentally. When writing PHP in this repo:

- Every file starts with `<?php` **immediately followed by** `declare(strict_types=1);` (`declare_strict_types` + `linebreak_after_opening_tag`).
- **`native_function_invocation`** — call native PHP functions with a leading backslash: `\intval(...)`, `\json_encode(...)`, `\fopen(...)`, etc. The existing code shows this everywhere; new code that omits it will fail `cs-check`.
- **`mb_str_functions`** — use the `mb_*` variants for string functions (`mb_strtolower`, not `strtolower`). See `CacheController` for examples.
- Single quotes for strings, short array syntax `[]`, ordered/alphabetized imports, trailing commas in multiline arrays, `!` operator with a trailing space (`! is_nan(...)` — but note the `\` prefix rule: `! \is_nan(...)`).
- Return type declarations with **no space** before the colon.
- `binary_operator_spaces` = `align_single_space_minimal` — array/assignment blocks in existing code use column alignment; the fixer will re-align them, don't fight it.

If `composer cs-check` fails on a file, run `composer cs-fix <file>` and re-stage.

## Deployment note

There is no Docker or CI config checked in. Deploys are done manually to VOST infrastructure; a `Cloudflare` zone sits in front and is purged on writes as described above. If the `.env` doesn't set `CLOUDFLARE_API_ENABLE=true`, the code degrades gracefully — the JSON snapshots still get regenerated locally, just not purged from the edge.
