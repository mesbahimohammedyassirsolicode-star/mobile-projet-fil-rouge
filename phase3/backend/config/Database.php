<?php
declare(strict_types=1);

namespace App\Config;

final class Database
{
    public static function getConnection(): JsonStorage
    {
        return new JsonStorage(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'smart_tourism.json');
    }
}
