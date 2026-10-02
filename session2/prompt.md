Create a BASIC educational prototype for the "Réservation d'Hôtels" project.

IMPORTANT:
The main objective is:
CLASSIFICATION DES CHAMBRES PAR CATÉGORIE.

The prototype must demonstrate:

HTML Form
→ JavaScript fetch()
→ PHP API
→ PHP OOP
→ JSON
→ Classification
→ HTML Table

Do NOT use:
- MySQL
- PDO
- Laravel
- React
- Vue
- Node.js
- XAMPP
- any external database

Use only:
- PHP
- PHP OOP
- JSON
- HTML
- JavaScript
- fetch()
- Tailwind CSS

==================================================
1. JSON DATABASE
==================================================

Create:

backend/data/chambres.json

The file MUST start empty:

[]

The JSON file is the database for this prototype.

Each room must contain only:

- id
- numero
- prix
- category_id

Example after adding data:

[
    {
        "id": 1,
        "numero": "101",
        "prix": 500,
        "category_id": 1
    },
    {
        "id": 2,
        "numero": "102",
        "prix": 750,
        "category_id": 2
    }
]

==================================================
2. CATEGORIES
==================================================

Use these categories:

1 - Simple
2 - Double
3 - Suite

The category is represented by category_id.

Example:

category_id = 1 → Simple
category_id = 2 → Double
category_id = 3 → Suite

The category options can be defined directly in the HTML form for this basic prototype.

==================================================
3. BACKEND OOP
==================================================

Create:

backend/classes/Chambre.php

Create a simple PHP class called Chambre.

The class must:

- receive the JSON file path
- read the JSON file
- decode the JSON
- return all rooms
- add a room
- automatically generate the next ID
- save the room into the JSON file
- filter rooms by category

Methods:

getAll()

add($numero, $prix, $category_id)

getByCategory($category_id)

Keep the OOP code simple and beginner-friendly.

==================================================
4. API
==================================================

Create:

backend/api/chambres.php

The API must return JSON.

GET all rooms:

GET /backend/api/chambres.php

Return all rooms.

GET rooms by category:

GET /backend/api/chambres.php?category_id=2

Return only rooms belonging to category 2.

POST:

POST /backend/api/chambres.php

Receive JSON:

{
    "numero": "101",
    "prix": 750,
    "category_id": 2
}

The API must:

- read the JSON request
- decode it
- validate the required fields
- call Chambre->add()
- save the room
- return a JSON response

Example:

{
    "success": true,
    "message": "Chambre ajoutée avec succès"
}

==================================================
5. FRONTEND STRUCTURE
==================================================

IMPORTANT:

The frontend MUST NOT be PHP.

Use separate files:

frontend/
├── index.html
└── script.js

Use:

- HTML for the interface
- JavaScript for all frontend logic
- Tailwind CSS for styling

Do NOT put JavaScript inside the HTML file.

==================================================
6. index.html
==================================================

Create a simple page containing:

A form:

Numéro de chambre
[________________]

Prix par nuit
[________________]

Catégorie
[ Sélectionner une catégorie ▼ ]

Options:

1 - Simple
2 - Double
3 - Suite

[ Ajouter la chambre ]

Then create a category filter:

Filtrer par catégorie:

[ Toutes les catégories ▼ ]

Options:

Toutes les catégories
Simple
Double
Suite

Then create a table:

| ID | Numéro | Prix/Nuit | Catégorie |
|----|--------|-----------|-----------|

Add an element for success/error messages.

Use basic Tailwind CSS.

Keep the design simple.

==================================================
7. script.js
==================================================

All JavaScript logic MUST be inside:

frontend/script.js

The JavaScript must:

1. Load rooms when the page opens.

Use:

fetch("../backend/api/chambres.php")

2. Display rooms inside the table.

3. Handle the form submit.

When submitting:

- preventDefault()
- read numero
- read prix
- read category_id
- create a JavaScript object
- use fetch()
- method POST
- Content-Type: application/json
- JSON.stringify()

Example:

{
    numero: "101",
    prix: 750,
    category_id: 2
}

4. After successful insertion:

- display success message
- reset the form
- reload the table using GET

5. Handle category filtering.

When the user selects a category:

If "Toutes les catégories":

fetch("../backend/api/chambres.php")

If category 2:

fetch("../backend/api/chambres.php?category_id=2")

Then update the table.

6. Convert category_id into category name.

Example:

1 → Simple
2 → Double
3 → Suite

The table must display:

| ID | Numéro | Prix/Nuit | Catégorie |
| 1  | 101    | 500 DH   | Simple    |
| 2  | 102    | 750 DH   | Double    |

==================================================
8. TAILWIND CSS
==================================================

Use basic Tailwind CSS.

The design should contain:

- centered container
- simple card
- form
- labels
- inputs
- select
- button
- filter
- table
- success/error message

Keep the UI at a BASIC educational level.

Do NOT create:

- dashboard
- sidebar
- authentication
- pagination
- modal
- animations
- charts
- advanced components

==================================================
9. PROJECT STRUCTURE
==================================================

The final structure MUST be:

project/
│
├── backend/
│   ├── data/
│   │   └── chambres.json
│   │
│   ├── classes/
│   │   └── Chambre.php
│   │
│   └── api/
│       └── chambres.php
│
└── frontend/
    ├── index.html
    └── script.js

==================================================
10. COMPLETE FLOW
==================================================

ADD ROOM:

HTML Form
    ↓
script.js
    ↓
fetch() POST
    ↓
PHP API
    ↓
Chambre OOP
    ↓
chambres.json
    ↓
JSON response
    ↓
script.js
    ↓
HTML Table

CLASSIFICATION:

Category Filter
    ↓
script.js
    ↓
fetch()
    ↓
GET ?category_id=X
    ↓
PHP API
    ↓
Chambre::getByCategory()
    ↓
chambres.json
    ↓
JSON response
    ↓
script.js
    ↓
Filtered HTML Table

==================================================
11. RUNNING
==================================================

The project must work with PHP built-in server.

Run the server from the project root:

php -S localhost:8000

The frontend should be accessible through:

http://localhost:8000/frontend/

The API should be accessible through:

http://localhost:8000/backend/api/chambres.php

IMPORTANT:
Do not open index.html using file://.

Use the PHP server because JavaScript fetch() must communicate with the PHP API.

==================================================
12. CODE LEVEL
==================================================

Keep everything BASIC.

The code must be:

- simple
- readable
- beginner-friendly
- easy to explain during live coding

Use simple PHP functions:

file_get_contents()
file_put_contents()
json_decode()
json_encode()

Use simple JavaScript:

fetch()
response.json()
JSON.stringify()
addEventListener()
preventDefault()

Do not over-engineer.

FINAL OBJECTIVE:

At the beginning:

chambres.json

[]

The user opens:

frontend/index.html

The user fills:

Numéro: 101
Prix: 750
Catégorie: Double

Click:

Ajouter la chambre

JavaScript sends the data using fetch().

PHP API receives the JSON.

The Chambre OOP class saves the data.

The JSON becomes:

[
    {
        "id": 1,
        "numero": "101",
        "prix": 750,
        "category_id": 2
    }
]

The room appears in the HTML table.

Then the user selects:

Double

The application displays only rooms classified as:

Double.

The prototype must clearly demonstrate:

HTML + JavaScript + fetch()
+
PHP API
+
PHP OOP
+
JSON
+
Classification par catégorie
+
HTML Table