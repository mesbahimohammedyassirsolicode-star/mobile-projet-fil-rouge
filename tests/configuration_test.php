<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=not-a-port');
putenv('DB_NAME=smart_tourism');
putenv('DB_USER=root');
putenv('DB_PASS=');

try {
    $connection = \App\Config\Database::getConnection();
    throw new RuntimeException('Invalid database configuration was accepted.');
} catch (RuntimeException $exception) {
    if (str_contains($exception->getMessage(), 'DB_PORT')) {
        echo "Configuration test passed: invalid environment was reported.\n";
        exit(0);
    }

    throw $exception;
}
