# Copilot / AI Agent Instructions for this repository

Purpose: provide concise, actionable guidance so an AI coding agent can be immediately productive in this Laravel + Vite project.

Quick Setup and common commands
- PHP & dependencies: Run `composer install` (PHP 8.2 required). The project autoloads `App\\` -> `app/` via `composer.json`.
- Frontend: Run `npm install` then `npm run dev` (or `npm run build` for production). The project uses Vite + Tailwind.
- All-in-one dev: `composer run dev` executes a concurrently script that starts `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev`.
- Database: the project expects a SQLite file at `database/database.sqlite` (created by Composer post-create hooks). To migrate: `php artisan migrate`.
- Tests: `composer test` runs `php artisan test`. The repo includes Pest in dev dependencies.

Key architecture and where to make changes
- HTTP routes: edit `routes/web.php` for web routes and `routes/auth.php` for auth-specific routes.
- Controllers: `app/Http/Controllers/` — follow existing controllers for request/response patterns.
- Models & data: `app/Models/` contains domain models (e.g. `Product.php`, `Transaction.php`, `TransactionItem.php`, cash-related models). Use Eloquent conventions (PSR-4 autoloading).
- Database migrations: `database/migrations/` — migrations are timestamped; add new migrations here and run `php artisan migrate`.
- Seeders & factories: `database/seeders/` and `database/factories/` — use these for test data.
- Views & assets: Blade views under `resources/views/`, CSS/JS in `resources/css` and `resources/js`; Vite compiles assets.

Project-specific conventions and patterns (observable)
- PSR-4 namespace: `App\\` maps to `app/` (see `composer.json`). Keep models/controllers under those folders.
- Tests use Pest (`pestphp/pest`) instead of plain PHPUnit syntax. Look at `tests/` for examples.
- Formatting & linting: `laravel/pint` and `laravel/pail` are included as dev tools. Use `pint` for PHP style, and `pail` is used in the `composer dev` script for log streaming.
- Frontend: Tailwind + `@tailwindcss/vite` plugin — prefer utility-first classes in Blade templates and `resources/css`.

Integration points and external dependencies
- Laravel framework (v12). Main runtime hooks are through `artisan` commands.
- Vite + npm packages for frontend. Use `npm run dev` / `npm run build`.
- Background queue: uses Laravel queues; the dev script runs `php artisan queue:listen` — long-running jobs depend on queue workers.

Examples (common edits)
- Add a route: modify `routes/web.php`, then verify controller exists in `app/Http/Controllers/`.
- Add a migration: create `php artisan make:migration add_x_to_table` and update models in `app/Models/`.
- Run full dev stack locally: `composer run dev` (starts server, queue, pail, vite). Alternatively run parts separately: `php artisan serve` + `npm run dev`.

What to avoid / assumptions not true here
- Do not assume a separate Node monorepo — frontend lives inside this repo and uses Vite.
- Do not assume Docker or Sail is required; Sail is present in dev deps but project provides standard `composer` and `npm` scripts.

Where to look for context when uncertain
- Routes & entrypoints: `routes/` and `app/Http/Controllers/`.
- Data shape & history: `database/migrations/` and `app/Models/`.
- Dev scripts & tasks: `composer.json` and `package.json` (see `scripts` sections).

If you edit code, run the focused checks
- Run `composer test` (runs `php artisan test`) to validate backend behavior.
- Run `npm run dev` and visit the dev server to validate UI changes.
- Run `./vendor/bin/pint` (or `composer run pint` if configured) to format PHP.

If changes are large
- Add a short note to `README.md` describing how to run your change locally and any new environment variables.

Questions or missing docs
- If anything above is unclear, ask for which area to expand (routes, models, migrations, or frontend build).

Generated: concise, repo-discoverable guidance for AI agents.
