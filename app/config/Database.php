<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = self::environment('DB_HOST', '127.0.0.1');
        $port = self::environment('DB_PORT', '3306');
        $name = self::environment('DB_NAME', 'smart_tourism');
        $user = self::environment('DB_USER');
        $password = getenv('DB_PASS');

        if ($user === null || $password === false) {
            throw new RuntimeException(
                'Database configuration is incomplete. Set DB_USER and DB_PASS in the process environment.'
            );
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

        try {
            self::$connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Unable to connect to the configured MySQL database.',
                0,
                $exception
            );
        }

        return self::$connection;
    }

    private static function environment(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);

        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}
