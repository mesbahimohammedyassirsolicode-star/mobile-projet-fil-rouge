<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

try {
    $connection = \App\Config\Database::getConnection();
    if (!$connection instanceof PDO) {
        throw new RuntimeException('Database::getConnection() must return PDO.');
    }

    echo "Configuration test passed.\n";
} catch (RuntimeException $exception) {
    if (getenv('DB_HOST') === false && str_contains($exception->getMessage(), 'DB_')) {
        echo "Configuration test passed: missing environment was reported.\n";
        exit(0);
    }

    throw $exception;
}
