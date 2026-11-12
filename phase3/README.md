# Smart Tourism

Smart Tourism is a web-based tourism management platform with a vanilla
JavaScript frontend and layered PHP backend. The application provides complete
CRUD operations through AJAX requests and stores data in a local JSON file.

## Requirements

- PHP 8.0 or newer
- A web browser
- Write permission for `backend/data/smart_tourism.json`

## Project structure

```text
backend/
├── api/              Controllers and JSON API endpoints
├── config/           JSON storage configuration
├── classes/          BaseDAO and BaseService
├── Dao/              Data access objects
├── services/         Business services
├── data/             JSON data file and utilities
├── public/           PHP API entry point
├── bootstrap.php     Application autoloader
├── .env.example      Configuration note; no database variables are required
└── composer.json      PHP project and autoload configuration
frontend/
├── index.html         Frontend entry point
├── main.js            Frontend module entry point
├── css/style.css      Frontend styles
├── js/                Fetch API and JavaScript utilities
├── assets/            Images and icons
└── pages/             CRUD entry pages
database/
└── smart_tourism.sql  Legacy schema reference; not used by the API
tests/                Small executable PHP tests
```

## Installation

1. The API stores all records in
   [`backend/data/smart_tourism.json`](backend/data/smart_tourism.json).
   Ensure the file is writable by PHP.

2. Start the backend API:

   ```sh
   php -S localhost:8000 -t backend/public backend/public/router.php
   ```

3. In another terminal, serve the independent frontend:

   ```sh
   php -S localhost:5500 -t frontend
   ```

4. Open <http://localhost:5500/>.

No MySQL server, database import, PDO extension, or environment variables are
required.

## Architecture

`backend/bootstrap.php` provides a small PSR-4-style loader for the `App\`
namespace. `App\Config\JsonStorage` reads and writes the JSON data file with
file locking. No database server or PDO connection is required.

Concrete DAOs configure trusted table and primary-key identifiers and inherit
safe CRUD operations from `BaseDAO`. `BaseService` receives a DAO through
dependency injection, validates IDs and payloads, and delegates persistence.
`CategorieService` adds category-specific name validation. Services do not
contain SQL. Controllers expose the CRUD endpoints consumed by the AJAX
frontend.

## API endpoints

All endpoints use JSON request and response bodies:

| Resource | Endpoint |
|---|---|
| Categories | `/api/categories` |
| Destinations | `/api/destinations` |
| Tourist places | `/api/lieux` |
| Activities | `/api/activites` |
| Users | `/api/utilisateurs` |
| Place favorites | `/api/favoris-lieux` |
| Activity favorites | `/api/favoris-activites` |
| Place reviews | `/api/avis-lieux` |
| Activity reviews | `/api/avis-activites` |
| Trips | `/api/voyages` |
| Place trip steps | `/api/etapes-lieux` |
| Activity trip steps | `/api/etapes-activites` |

Each resource supports:

- `GET /api/resource` to list records
- `GET /api/resource/{id}` to retrieve one record
- `POST /api/resource` to create a record
- `PUT /api/resource/{id}` to update a record
- `DELETE /api/resource/{id}` to delete a record

List requests support `search`, `page`, and `per_page` query parameters.

## Testing the API

```sh
php -l backend/public/index.php
php -l backend/config/JsonStorage.php
php -l backend/classes/BaseDAO.php
php -l backend/classes/BaseService.php
php backend/public/index.php
```

Example AJAX request:

```js
fetch('http://localhost:8000/api/categories', {
  headers: { Accept: 'application/json' }
})
  .then((response) => response.json())
  .then((payload) => console.log(payload.data));
```

## Frontend CRUD

`frontend/css/style.css` is the Tailwind entry point for the dashboard.
`frontend/js/api.js` centralizes Fetch API methods and uses the backend JSON
base URL. `frontend/js/main.js` provides shared CRUD behavior for categories,
destinations, tourist places, activities, users, favorites, reviews, trips,
and trip steps. The pages in `frontend/pages/` are direct entry points for the
main modules.

## Data and security

The JSON table names and field names mirror the existing schema. Mutating API
requests can be protected by setting `ADMIN_API_TOKEN`; when set, send
`Authorization: Bearer <token>`.

User passwords are hashed with PHP's `password_hash()` before they are stored.
The password field is never returned by the API.

The file [`database/smart_tourism.sql`](database/smart_tourism.sql) is retained
only as a legacy schema reference. It is not imported or used at runtime.
