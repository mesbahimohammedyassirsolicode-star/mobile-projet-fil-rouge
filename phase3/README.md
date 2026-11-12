# Smart Tourism

Smart Tourism is a PHP 8 tourism platform foundation. Phase 1 prepares the
admin-dashboard backend architecture and demonstrates category persistence.
Dashboard screens and the remaining CRUD modules are intentionally outside
this phase.

## Requirements

- PHP 8.0 or newer with PDO and the MySQL driver
- MySQL 8 or compatible server
- A web browser
- Optional: Composer is not required for Phase 1

## Project structure

```text
app/
├── config/       Database configuration
├── core/         BaseDAO and BaseService
├── daos/         Concrete data access classes
├── models/       Domain value objects
├── services/     Business validation and orchestration
├── controllers/  Future HTTP controllers
└── helpers/      Future shared helpers
public/
├── index.php     Minimal health entry point
├── api/          Future API endpoints
└── assets/       CSS, JavaScript, and images
views/            Future layouts and dashboard views
database/         Database schema and notes
tests/            Small executable PHP tests
```

## Installation

1. Import the existing schema:

   ```sh
   mysql -u root -p < database/smart_tourism.sql
   ```

2. Copy `.env.example` to `.env` for reference, then export the values in the
   process that runs PHP. The Phase 1 custom bootstrap does not parse `.env`
   files:

   ```sh
   # PowerShell
   $env:DB_HOST="127.0.0.1"
   $env:DB_PORT="3306"
   $env:DB_NAME="smart_tourism"
   $env:DB_USER="root"
   $env:DB_PASS="your-password"
   ```

3. Start the PHP development server:

   ```sh
   php -S localhost:8000 -t public
   ```

4. Open <http://localhost:8000/>.

## Architecture

`bootstrap.php` provides a small PSR-4-style loader for the `App\` namespace.
`App\Config\Database` creates a configured PDO connection with UTF-8,
exceptions, associative fetches, and native prepared statements.

Concrete DAOs configure trusted table and primary-key identifiers and inherit
safe CRUD operations from `BaseDAO`. `BaseService` receives a DAO through
dependency injection, validates IDs and payloads, and delegates persistence.
`CategorieService` adds category-specific name validation. Services do not
contain SQL.

## Testing the Phase 1 example

The executable tests use SQLite in-memory storage when no MySQL credentials
are exported, so the DAO and service behavior can be checked locally:

```sh
php tests/configuration_test.php
php tests/base_dao_test.php
php tests/base_service_test.php
php tests/category_integration_test.php
```

When `DB_USER` and `DB_PASS` are available, the category integration test uses
the configured MySQL connection and the existing `categorie` table. It creates
and removes its own uniquely named test category.

## Tailwind preparation

`public/assets/css/app.css` is reserved as the future Tailwind entry point.
No dashboard UI or Tailwind dependency is installed in Phase 1. A later phase
can add a Tailwind CLI build or use the official CDN for a quick prototype.

## Scope

This phase does not include authentication, dashboard pages, API CRUD
endpoints, or the other tourism modules. The existing database schema remains
the source of truth.
