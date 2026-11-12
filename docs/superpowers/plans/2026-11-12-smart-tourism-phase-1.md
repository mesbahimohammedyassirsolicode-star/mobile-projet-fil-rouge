# Smart Tourism Phase 1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the dependency-light PHP 8 foundation for Smart Tourism and prove the DAO/service architecture with category persistence operations.

**Architecture:** A custom PSR-4-style bootstrap maps `App\` to `app/`. `Database` supplies a configured PDO connection, concrete DAOs configure trusted table metadata and extend `BaseDAO`, and services validate input before delegating to DAOs. A minimal public entry point and CLI integration test demonstrate the foundation without adding CRUD UI.

**Tech Stack:** PHP 8, OOP, PDO, MySQL, HTML5, Tailwind CSS preparation, vanilla JavaScript.

**Spec:** `docs/superpowers/specs/2026-11-12-smart-tourism-phase-1-design.md`

## Global Constraints

- Use PHP 8 with `declare(strict_types=1)`, namespaces, and typed APIs.
- Connect to MySQL database `smart_tourism` using `utf8mb4`.
- Enable PDO exceptions and disable emulated prepared statements.
- Bind all values; only trusted, validated DAO identifiers may enter SQL.
- Do not change the existing 12-table database schema.
- Do not build dashboard CRUD interfaces or add frameworks/dependencies.
- Keep the existing `session1/` and `session2/` prototypes untouched.

## Review Focus

- Missing or invalid environment configuration must produce an explicit actionable configuration error; test in Task 1.
- PDO must use exception mode, associative fetches, native prepares, and `utf8mb4`; test in Task 1.
- Empty data and invalid IDs must be rejected before SQL; test in Task 2 and Task 3.
- Identifier injection attempts must be rejected rather than interpolated; test in Task 2.
- Duplicate/invalid category names must produce meaningful service validation errors; test in Task 4.

### Task 1: Bootstrap and database configuration

**Files:**
- Create: `bootstrap.php`
- Create: `.env.example`
- Create: `.gitignore`
- Create: `app/config/Database.php`
- Create: `public/index.php`
- Create: `public/assets/css/app.css`
- Create: `public/assets/js/app.js`
- Create: `public/assets/images/.gitkeep`
- Create: `public/api/.gitkeep`
- Create: `app/models/.gitkeep`
- Create: `app/controllers/.gitkeep`
- Create: `app/helpers/.gitkeep`
- Create: `app/services/.gitkeep`
- Create: `views/layouts/.gitkeep`
- Create: `views/dashboard/.gitkeep`

**Interfaces:**
- Produces `App\Config\Database::getConnection(): PDO` and a bootstrap that loads `App\` classes from `app/`.

- [ ] **Step 1: Write a failing configuration smoke test** in `tests/configuration_test.php` that loads `bootstrap.php`, calls `Database::getConnection()`, and asserts the result is a `PDO`; if configuration is absent, assert the message explains the required variables.
- [ ] **Step 2: Run the smoke test** with `php tests/configuration_test.php`; expected result is a clear configuration failure until valid environment variables are supplied.
- [ ] **Step 3: Implement** `bootstrap.php` with a strict namespace-to-file loader and `app/config/Database.php` with `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS` environment values, defaulting only non-sensitive host/port/name values. Configure `PDO::ERRMODE_EXCEPTION`, `PDO::FETCH_ASSOC`, and `PDO::ATTR_EMULATE_PREPARES => false`; wrap connection failures in an explicit `RuntimeException` without exposing credentials.
- [ ] **Step 4: Add** `.env.example`, `.gitignore`, the minimal `public/index.php` health response, and empty CSS/JS/frontend directories. Document that `.env.example` is copied/exported into process environment because no dotenv dependency is added.
- [ ] **Step 5: Run** `php -l bootstrap.php`, `php -l app/config/Database.php`, and `php -l public/index.php`; expected result is no syntax errors.

### Task 2: Safe reusable BaseDAO

**Files:**
- Create: `app/core/BaseDAO.php`
- Create: `tests/base_dao_test.php`

**Interfaces:**
- Consumes `PDO` through `BaseDAO::__construct(PDO $connection)`.
- Produces abstract methods `findAll(): array`, `findById(int $id): ?array`, `create(array $data): int`, `update(int $id, array $data): bool`, and `delete(int $id): bool`; concrete classes provide protected `string $tableName` and `string $primaryKey`.

- [ ] **Step 1: Write failing tests** covering empty data, IDs less than 1, an invalid configured identifier, and CRUD SQL generated with bound values against a temporary SQLite PDO where available; skip only the SQLite integration portion if the PHP build lacks SQLite, while retaining validation assertions.
- [ ] **Step 2: Run** `php tests/base_dao_test.php`; expected result is failure because `BaseDAO` does not exist.
- [ ] **Step 3: Implement** `BaseDAO` with constructor identifier validation using a strict SQL identifier pattern, fixed configured metadata only, prepared statements for every value, empty-data guards, `lastInsertId()` conversion, and boolean row-count results. Never accept table/column names as method input.
- [ ] **Step 4: Run** `php tests/base_dao_test.php`; expected result is PASS, including rejection of invalid identifiers and IDs.

### Task 3: BaseService validation and delegation

**Files:**
- Create: `app/core/BaseService.php`
- Create: `tests/base_service_test.php`

**Interfaces:**
- Consumes a DAO object through `BaseService::__construct(BaseDAO $dao)`.
- Produces `getAll(): array`, `getById(int $id): ?array`, `create(array $data): int`, `update(int $id, array $data): bool`, and `delete(int $id): bool`.

- [ ] **Step 1: Write failing tests** using a small in-memory fake DAO subclass to assert delegation and rejection of IDs less than 1, empty create/update data, and missing records where applicable.
- [ ] **Step 2: Run** `php tests/base_service_test.php`; expected result is failure because `BaseService` does not exist.
- [ ] **Step 3: Implement** `BaseService` with typed delegation, positive integer ID validation, non-empty data validation, and meaningful `InvalidArgumentException` messages. Keep SQL entirely in the DAO.
- [ ] **Step 4: Run** `php tests/base_service_test.php`; expected result is PASS.

### Task 4: Category example and integration test

**Files:**
- Create: `app/models/Categorie.php`
- Create: `app/services/CategorieService.php`
- Create: `app/daos/CategorieDAO.php`
- Create: `tests/category_integration_test.php`

**Interfaces:**
- `App\Daos\CategorieDAO` extends `BaseDAO` with trusted metadata `categorie` and `id_categorie`.
- `App\Services\CategorieService` extends `BaseService` and validates a non-empty `nom` (maximum 50 characters) and optional `description`, exposing the inherited CRUD methods.
- `App\Models\Categorie` is a typed category value object/array boundary suitable for future controllers; it does not change the database schema.

- [ ] **Step 1: Write** `category_integration_test.php` to connect through `Database`, fetch all categories, optionally fetch an existing first ID, create a uniquely named category, verify retrieval, update it, verify the update, delete it, and verify deletion. Ensure cleanup runs when an assertion fails after creation.
- [ ] **Step 2: Run** `php tests/category_integration_test.php`; expected result is an explicit environment/database failure when MySQL is not configured, otherwise failure because the concrete classes do not exist.
- [ ] **Step 3: Implement** the category DAO, model, and service using the existing schema names without adding tables or UI. Service validation must reject blank/overlong names and normalize string inputs consistently.
- [ ] **Step 4: Run** `php tests/category_integration_test.php` with valid `smart_tourism` environment variables; expected result is PASS and no test category remains.

### Task 5: Documentation and final verification

**Files:**
- Modify: `README.md`
- Create: `database/smart_tourism.sql`
- Create: `database/README.md`
- Create: `public/api/README.md`

**Interfaces:**
- Documentation describes the root structure, requirements, environment configuration, MySQL import using the existing `db.sql` schema content, PHP server command, DAO/service flow, category test command, and Tailwind preparation.

- [ ] **Step 1: Document** installation and operation in `README.md`, explicitly distinguishing Phase 1 infrastructure from future CRUD interfaces.
- [ ] **Step 2: Add** `database/smart_tourism.sql` as the agreed schema copy or a clear reference-compatible schema file without modifying table definitions; add short placeholders explaining that API/views are intentionally empty.
- [ ] **Step 3: Run** all PHP syntax checks over created PHP files and rerun the configuration, BaseDAO, BaseService, and category tests with the available environment.
- [ ] **Step 4: Inspect** `git diff --check` and `git status --short`; expected result is no whitespace errors, only Phase 1 files changed, and no credentials or generated dependencies committed.

