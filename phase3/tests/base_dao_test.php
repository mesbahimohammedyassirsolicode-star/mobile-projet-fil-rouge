<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\BaseDAO;

final class TestDAO extends BaseDAO
{
    protected string $tableName = 'categories';
    protected string $primaryKey = 'category_id';
}

final class InvalidIdentifierDAO extends BaseDAO
{
    protected string $tableName = 'categories; DROP TABLE categories';
    protected string $primaryKey = 'category_id';
}

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$connection = new PDO('sqlite::memory:');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->exec(
    'CREATE TABLE categories (category_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, description TEXT)'
);

$dao = new TestDAO($connection);
assertTrue($dao->findAll() === [], 'findAll() should return an empty array initially.');
assertTrue($dao->findById(999) === null, 'findById() should return null for a missing row.');

$id = $dao->create(['name' => 'Medina', 'description' => 'Historic district']);
assertTrue($id > 0, 'create() should return the generated integer ID.');
assertTrue($dao->findById($id)['name'] === 'Medina', 'findById() should retrieve the created row.');
assertTrue($dao->update($id, ['name' => 'Updated Medina']), 'update() should report success.');
assertTrue($dao->findById($id)['name'] === 'Updated Medina', 'update() should persist values.');
assertTrue($dao->delete($id), 'delete() should report success.');
assertTrue($dao->findById($id) === null, 'delete() should remove the row.');

foreach (
    [
        static fn(): int => $dao->findById(0),
        static fn(): int => $dao->create([]),
        static fn(): bool => $dao->update(0, ['name' => 'Invalid']),
        static fn(): bool => $dao->update(1, []),
        static fn(): bool => $dao->delete(0),
    ] as $operation
) {
    try {
        $operation();
        throw new RuntimeException('Expected invalid DAO input to throw.');
    } catch (InvalidArgumentException) {
    }
}

try {
    new InvalidIdentifierDAO($connection);
    throw new RuntimeException('Expected invalid identifiers to throw.');
} catch (InvalidArgumentException) {
}

echo "BaseDAO test passed.\n";
