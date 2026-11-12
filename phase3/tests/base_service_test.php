<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\BaseDAO;
use App\Core\BaseService;

final class FakeServiceDAO extends BaseDAO
{
    protected string $tableName = 'fake_items';
    protected string $primaryKey = 'id';
    public array $calls = [];

    public function findAll(): array
    {
        $this->calls[] = 'findAll';
        return [['id' => 1, 'name' => 'Item']];
    }

    public function findById(int $id): ?array
    {
        $this->calls[] = ['findById', $id];
        return $id === 1 ? ['id' => 1, 'name' => 'Item'] : null;
    }

    public function create(array $data): int
    {
        $this->calls[] = ['create', $data];
        return 2;
    }

    public function update(int $id, array $data): bool
    {
        $this->calls[] = ['update', $id, $data];
        return true;
    }

    public function delete(int $id): bool
    {
        $this->calls[] = ['delete', $id];
        return true;
    }
}

final class TestService extends BaseService
{
}

function assertSameValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message);
    }
}

$dao = new FakeServiceDAO(new PDO('sqlite::memory:'));
$service = new TestService($dao);
assertSameValue([['id' => 1, 'name' => 'Item']], $service->getAll(), 'getAll() should delegate.');
assertSameValue(['id' => 1, 'name' => 'Item'], $service->getById(1), 'getById() should delegate.');
assertSameValue(2, $service->create(['name' => 'New']), 'create() should delegate.');
assertSameValue(true, $service->update(1, ['name' => 'Updated']), 'update() should delegate.');
assertSameValue(true, $service->delete(1), 'delete() should delegate.');

foreach (
    [
        static fn(): ?array => $service->getById(0),
        static fn(): int => $service->create([]),
        static fn(): bool => $service->update(0, ['name' => 'Invalid']),
        static fn(): bool => $service->update(1, []),
        static fn(): bool => $service->delete(0),
    ] as $operation
) {
    try {
        $operation();
        throw new RuntimeException('Expected invalid service input to throw.');
    } catch (InvalidArgumentException) {
    }
}

echo "BaseService test passed.\n";
