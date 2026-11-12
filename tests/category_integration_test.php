<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Config\Database;
use App\Daos\CategorieDAO;
use App\Models\Categorie;
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
$model = Categorie::fromArray([
    'id_categorie' => 7,
    'nom' => 'Model category',
    'description' => 'Model description',
]);
assertCategory($model->id === 7 && $model->nom === 'Model category', 'Categorie model should map database rows.');
$initialCategories = $service->getAll();
assertCategory(is_array($initialCategories), 'getAll() should return categories.');

$name = 'Test Category ' . bin2hex(random_bytes(3));
$id = 0;
try {
    $id = $service->create(['nom' => $name, 'description' => 'Created by the integration test']);
    $created = $service->getById($id);
    assertCategory($created !== null && $created['nom'] === $name, 'Created category should be retrievable.');

    $updatedName = $name . ' Updated';
    assertCategory(
        $service->update($id, ['nom' => $updatedName, 'description' => 'Updated description']),
        'Category update should report success.'
    );
    assertCategory($service->getById($id)['nom'] === $updatedName, 'Updated category should persist.');
} finally {
    if ($id > 0) {
        assertCategory($service->delete($id), 'Category deletion should report success.');
    }
}
assertCategory($service->getById($id) === null, 'Deleted category should not be retrievable.');

try {
    $duplicateId = $service->create(['nom' => 'Duplicate Category']);
    try {
        $service->create(['nom' => 'Duplicate Category']);
        throw new RuntimeException('Expected duplicate category name to throw.');
    } catch (InvalidArgumentException) {
    } finally {
        $service->delete($duplicateId);
    }
} catch (InvalidArgumentException $exception) {
    assertCategory(
        str_contains($exception->getMessage(), 'already exists'),
        'Duplicate category errors should explain the conflict.'
    );
}

$unicodeName = str_repeat('é', 50);
$unicodeId = 0;
try {
    $unicodeId = $service->create(['nom' => $unicodeName]);
    assertCategory($service->getById($unicodeId)['nom'] === $unicodeName, '50-character UTF-8 names should be accepted.');
} finally {
    if ($unicodeId > 0) {
        $service->delete($unicodeId);
    }
}

foreach (['', str_repeat('x', 51)] as $invalidName) {
    try {
        $service->create(['nom' => $invalidName]);
        throw new RuntimeException('Expected invalid category name to throw.');
    } catch (InvalidArgumentException) {
    }
}

$driver = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
echo "Category integration test passed using {$driver}.\n";
