<?php
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\Config\\' => __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR,
        'App\\Core\\' => __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
        'App\\Models\\' => __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR,
        'App\\Daos\\' => __DIR__ . DIRECTORY_SEPARATOR . 'Dao' . DIRECTORY_SEPARATOR,
        'App\\Services\\' => __DIR__ . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR,
        'App\\Api\\' => __DIR__ . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR,
        'App\\Data\\' => __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR,
    ];

    foreach ($prefixes as $prefix => $baseDirectory) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDirectory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (is_file($file)) {
            require_once $file;
        }

        return;
    }
});
