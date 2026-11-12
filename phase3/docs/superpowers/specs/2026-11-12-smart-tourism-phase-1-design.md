# Smart Tourism Phase 1 Design

## Intent

Create the initial Smart Tourism admin-dashboard foundation for PHP 8, MySQL,
PDO, and a DAO + Service + Controller-ready architecture. Phase 1 makes the
shared infrastructure functional and demonstrates it with categories; it does
not build dashboard CRUD screens or additional tourism modules.

## Goals

- Establish a simple, scalable root-level project structure.
- Connect to the existing `smart_tourism` MySQL database through strict PDO
  configuration.
- Provide reusable, safe `BaseDAO` and `BaseService` abstractions.
- Demonstrate the architecture with `CategorieDAO` and `CategorieService`.
- Provide a runnable category integration example and setup documentation.
- Prepare frontend asset folders for future Tailwind work without building UI.

## Non-goals and anti-goals

- Do not change the existing 12-table database schema.
- Do not implement category or other CRUD interfaces.
- Do not add authentication, routing frameworks, ORM behavior, or frontend
  frameworks.
- Do not hardcode database credentials.
- Do not preserve the old reservation prototype as the new application
  architecture; its files remain untouched.

## Constraints

- PHP 8 with `declare(strict_types=1)`, namespaces, and typed APIs.
- MySQL database `smart_tourism`, using `utf8mb4`.
- PDO exceptions and native prepared statements.
- User-controlled values are always bound parameters. Table and column
  identifiers are fixed by DAO configuration and validated before SQL use.
- Empty data and invalid identifiers must fail explicitly with meaningful
  exceptions.

## Approaches

### Lightweight custom PSR-4-style autoloader (selected)

Register `App\` against `app/` and `Tests\` against `tests/` in a small
bootstrap file. This keeps the MVP dependency-free while retaining namespaces
and a predictable path convention.

### Composer PSR-4 autoloading (not selected)

Composer would be the conventional production approach, but it introduces a
dependency and installation step that is unnecessary for this first student
phase. It remains easy to adopt later because the namespace layout is already
PSR-4-compatible.

## Architecture

`public/index.php` loads the bootstrap and exposes a minimal health response.
`Database` creates one configured PDO connection. A concrete DAO fixes its
table and primary-key identifiers and delegates common persistence operations
to `BaseDAO`. A concrete service receives that DAO through dependency
injection, validates input and IDs, and delegates persistence without writing
SQL. Controllers and views receive their future extension points through the
folder structure but remain empty in Phase 1.

The initial test script creates a `CategorieService` from a
`CategorieDAO`, verifies read/create/update/delete behavior against the
configured database, and cleans up the created record.

## Remaining builder decisions

- Use `RuntimeException` for configuration/connection failures and
  `InvalidArgumentException` for invalid DAO/service inputs.
- Load `.env` values only from the process environment in Phase 1; document
  copying `.env.example` and exporting variables because no dependency is
  added for dotenv parsing.
- Keep Tailwind preparation to asset directories and a documented optional
  CDN/build path, with no UI implementation.
