<?php
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\' => __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR,
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
