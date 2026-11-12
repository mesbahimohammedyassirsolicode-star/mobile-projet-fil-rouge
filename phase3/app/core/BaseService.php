<?php
declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;

abstract class BaseService
{
    public function __construct(protected BaseDAO $dao)
    {
    }

    public function getAll(): array
    {
        return $this->dao->findAll();
    }

    public function getById(int $id): ?array
    {
        $this->assertId($id);

        return $this->dao->findById($id);
    }

    public function create(array $data): int
    {
        $this->assertData($data);

        return $this->dao->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $this->assertId($id);
        $this->assertData($data);

        return $this->dao->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $this->assertId($id);

        return $this->dao->delete($id);
    }

    private function assertData(array $data): void
    {
        if ($data === []) {
            throw new InvalidArgumentException('Service data cannot be empty.');
        }
    }

    private function assertId(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('The ID must be a positive integer.');
        }
    }
}
