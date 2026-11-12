# Phase 3 Builder Prompt

You are an experienced full-stack developer. Complete **Phase 3: Smart Tourism
Administration Dashboard** in the existing repository. Work directly in the
current project and preserve its architecture.

## Project context

This repository contains:

- A PHP 8+ backend using a layered architecture:
  `controllers -> services -> DAOs -> JsonStorage`.
- A JSON data store at `backend/data/smart_tourism.json`.
- REST-style JSON endpoints under `backend/public`.
- A framework-free frontend using HTML, modern vanilla JavaScript modules,
  Tailwind CSS from CDN, and the Fetch API.
- Existing shared frontend files:
  `frontend/js/api.js`, `frontend/js/main.js`, `frontend/js/utils.js`,
  `frontend/css/style.css`, and page entry points under
  `frontend/pages/`.
- The legacy SQL file is reference-only. Do not migrate the application to
  MySQL, PDO, Laravel, React, Vue, or another framework.

Read `README.md`, the backend controllers/services/models, the existing
frontend JavaScript, and the JSON seed data before changing anything. Reuse
existing helpers and naming conventions.

## Goal

Deliver a polished, usable, responsive administration dashboard for managing
the Smart Tourism platform. An administrator must be able to inspect,
search, create, edit, and delete the available tourism data from the browser
through the existing API.

## Resources that must be supported

Implement consistent UI and CRUD behavior for:

1. Categories - `/api/categories`
2. Destinations - `/api/destinations`
3. Tourist places - `/api/lieux`
4. Activities - `/api/activites`
5. Users - `/api/utilisateurs`
6. Place favorites - `/api/favoris-lieux`
7. Activity favorites - `/api/favoris-activites`
8. Place reviews - `/api/avis-lieux`
9. Activity reviews - `/api/avis-activites`
10. Trips - `/api/voyages`
11. Place trip steps - `/api/etapes-lieux`
12. Activity trip steps - `/api/etapes-activites`

Use the actual field names and validation rules already defined by the
backend and `backend/data/smart_tourism.json`. Do not guess a schema when the
repository already defines it.

## Required dashboard experience

### Application shell

- Create a clear admin layout with:
  - responsive sidebar/navigation;
  - dashboard header;
  - page title and breadcrumbs or contextual subtitle;
  - main content area;
  - mobile navigation behavior;
  - consistent loading, empty, success, and error states.
- Make the interface work on desktop, tablet, and mobile.
- Use accessible semantic HTML, labels, keyboard navigation, visible focus
  states, and sufficient color contrast.
- Keep the visual style coherent: modern tourism dashboard, clean cards,
  readable tables, restrained colors, and useful whitespace.
- Do not add a build system if the current CDN/vanilla setup can support the
  feature.

### Dashboard home

Add a useful overview page showing:

- counts for the main resources;
- a short activity/recent-data section when the API data allows it;
- quick links to the main management pages;
- graceful handling when one API request fails.

### Resource management

For each resource:

- Show records in a readable table on large screens and a usable responsive
  layout on small screens.
- Provide search using the API's `search` query parameter where supported.
- Support pagination using `page` and `per_page` where supported.
- Add create and edit forms in a modal or an equally clear inline flow.
- Validate required values in the browser, while keeping the backend as the
  source of truth.
- Confirm destructive deletion before sending the request.
- Refresh the displayed data after mutations.
- Show actionable error messages from failed API responses.
- Escape all server-provided values before inserting them into HTML.
- Never display user password values. Preserve the backend's password hashing
  and response filtering behavior.

### Relationships and special fields

- Use select controls for foreign-key fields when related data is available.
- Display friendly labels instead of raw IDs when possible.
- Handle dates, booleans, numeric values, text areas, and image URLs with
  appropriate controls.
- Make review, favorite, trip, and trip-step records understandable rather
  than exposing an unstructured JSON blob.
- If a relationship cannot be resolved, display the raw ID with a clear
  fallback instead of failing the whole page.

## Backend requirements

Only change backend code when it is necessary to make the documented Phase 3
experience work correctly. Preserve:

- JSON request and response formats;
- existing routes and HTTP methods;
- the DAO/service/controller separation;
- file locking and safe JSON persistence;
- ID validation and existing business validation;
- optional `ADMIN_API_TOKEN` protection for mutating requests;
- password hashing and password omission from API responses.

If a backend defect blocks the dashboard, fix the smallest root cause and
update related documentation. Do not silently swallow exceptions or return
fake success responses.

## Frontend implementation requirements

- Keep API communication centralized in `frontend/js/api.js`.
- Keep reusable UI helpers in shared frontend modules rather than duplicating
  logic in every page.
- Use `escapeHtml` or an equivalent safe rendering strategy everywhere user
  or server data is rendered.
- Use event delegation or properly managed listeners so repeated renders do
  not create duplicate handlers.
- Keep modules small and maintainable.
- Avoid inline JavaScript where a module function is clearer.
- Do not hard-code records that should come from the API.
- Preserve direct access to the existing page entry points.
- Keep the API base URL easy to configure for local development.

## Verification

Before considering the work complete:

1. Run PHP syntax checks on changed PHP files.
2. Start the API with:
   `php -S localhost:8000 -t backend/public backend/public/router.php`
3. Serve the frontend with:
   `php -S localhost:5500 -t frontend`
4. Verify the dashboard loads without console errors.
5. Verify list, search, create, update, and delete flows for representative
   resources, including at least one resource with relationships.
6. Verify loading, empty, invalid-input, API-error, and delete-confirmation
   states.
7. Verify responsive behavior and keyboard accessibility.
8. Verify that passwords are never rendered or returned by the frontend.
9. Update `README.md` with any new setup, route, or usage instructions.

Do not claim completion until the checks above have been run. Report:

- files changed;
- important implementation decisions;
- commands executed and their results;
- any remaining limitation that is genuinely caused by the repository or
  environment.

Implement the complete phase, not a mockup or a partial static prototype.
