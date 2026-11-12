<?php
declare(strict_types=1);

namespace App\Data;

use App\Config\JsonStorage;

final class DatabaseSeeder
{
    public function run(): void
    {
        new JsonStorage(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'smart_tourism.json');
    }
}
