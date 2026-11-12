<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Config\Database;
use App\Daos\CategorieDAO;
use App\Services\CategorieService;

function assertCategory(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$connection = getenv('DB_USER') !== false && getenv('DB_PASS') !== false
    ? Database::getConnection()
    : new PDO('sqlite::memory:');

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
    $connection->exec(
        'CREATE TABLE categorie (id_categorie INTEGER PRIMARY KEY AUTOINCREMENT, nom VARCHAR(50) NOT NULL UNIQUE, description TEXT)'
    );
}

$service = new CategorieService(new CategorieDAO($connection));
$initialCategories = $service->getAll();
assertCategory(is_array($initialCategories), 'getAll() should return categories.');

$name = 'Test Category ' . bin2hex(random_bytes(3));
$id = $service->create(['nom' => $name, 'description' => 'Created by the integration test']);
$created = $service->getById($id);
assertCategory($created !== null && $created['nom'] === $name, 'Created category should be retrievable.');

$updatedName = $name . ' Updated';
assertCategory(
    $service->update($id, ['nom' => $updatedName, 'description' => 'Updated description']),
    'Category update should report success.'
);
assertCategory($service->getById($id)['nom'] === $updatedName, 'Updated category should persist.');
assertCategory($service->delete($id), 'Category deletion should report success.');
assertCategory($service->getById($id) === null, 'Deleted category should not be retrievable.');

foreach (['', str_repeat('x', 51)] as $invalidName) {
    try {
        $service->create(['nom' => $invalidName]);
        throw new RuntimeException('Expected invalid category name to throw.');
    } catch (InvalidArgumentException) {
    }
}

$driver = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
echo "Category integration test passed using {$driver}.\n";
