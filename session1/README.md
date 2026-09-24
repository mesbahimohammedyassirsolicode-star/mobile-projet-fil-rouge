# HotelDesk — CRUD PHP / MySQL

1. Create the database by importing `database.sql` in MySQL.
2. Configure `DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASS` environment variables if the defaults (`127.0.0.1`, `reservation_hotels`, `root`, empty password) do not match your MySQL installation.
3. From this folder, run `php -S localhost:8000` and open `http://localhost:8000`.

The application uses PDO and a small MVC-style organization: `models/` contains queries, `controllers/` validates and orchestrates requests, `views/` renders the interface, and `config/` contains connection and entity metadata.
