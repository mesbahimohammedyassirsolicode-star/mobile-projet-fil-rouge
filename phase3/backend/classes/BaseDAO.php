<?php
declare(strict_types=1);

namespace App\Core;

use App\Config\JsonStorage;
use InvalidArgumentException;

abstract class BaseDAO
{
    protected string $tableName = '';
    protected string $primaryKey = '';
    protected array $writableColumns = [];
    protected array $searchableColumns = [];
    protected array $filterableColumns = [];
    protected array $hiddenColumns = [];

    public function __construct(protected JsonStorage $connection)
    {
        $this->assertIdentifier($this->tableName, 'table name');
        $this->assertIdentifier($this->primaryKey, 'primary key');
        $this->assertIdentifiers($this->writableColumns, 'column');
        $this->assertIdentifiers($this->searchableColumns, 'column');
        $this->assertIdentifiers($this->filterableColumns, 'column');
        $this->assertIdentifiers($this->hiddenColumns, 'column');
    }

    public function findAll(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        $page = max(1, $page);
        $perPage = min(100, max(1, $perPage));
        $rows = array_filter($this->connection->all($this->tableName), fn(array $row): bool => $this->matches($row, $filters));
        usort($rows, fn(array $a, array $b): int => (int) ($b[$this->primaryKey] ?? 0) <=> (int) ($a[$this->primaryKey] ?? 0));
        $total = count($rows);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($rows, $offset, $perPage);

        return [
            'items' => array_map(fn(array $row): array => $this->sanitizeRow($row), $items),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'pages' => $total === 0 ? 0 : (int) ceil($total / $perPage),
            ],
        ];
    }

    public function findById(int $id): ?array
    {
        $this->assertId($id);
        foreach ($this->connection->all($this->tableName) as $row) {
            if ((int) ($row[$this->primaryKey] ?? 0) === $id) {
                return $this->sanitizeRow($row);
            }
        }
        return null;
    }

    public function create(array $data): int
    {
        return $this->connection->insert($this->tableName, $this->primaryKey, $this->prepareData($data));
    }

    public function update(int $id, array $data): bool
    {
        $this->assertId($id);
        return $this->connection->update($this->tableName, $this->primaryKey, $id, $this->prepareData($data));
    }

    public function delete(int $id): bool
    {
        $this->assertId($id);
        return $this->connection->delete($this->tableName, $this->primaryKey, $id);
    }

    public function exists(int $id): bool
    {
        return $this->findById($id) !== null;
    }

    private function prepareData(array $data): array
    {
        if ($data === []) {
            throw new InvalidArgumentException('JSON data cannot be empty.');
        }
        foreach (array_keys($data) as $column) {
            if (!is_string($column) || !in_array($column, $this->writableColumns, true)) {
                throw new InvalidArgumentException(sprintf('Column is not writable: %s.', (string) $column));
            }
        }
        return $data;
    }

    private function matches(array $row, array $filters): bool
    {
        if (isset($filters['search']) && trim((string) $filters['search']) !== '') {
            $search = mb_strtolower(trim((string) $filters['search']));
            $found = false;
            foreach ($this->searchableColumns as $column) {
                if (str_contains(mb_strtolower((string) ($row[$column] ?? '')), $search)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }
        foreach ($this->filterableColumns as $column) {
            if (array_key_exists($column, $filters) && $filters[$column] !== '' && (string) ($row[$column] ?? '') !== (string) $filters[$column]) {
                return false;
            }
        }
        return true;
    }

    private function sanitizeRow(array $row): array
    {
        foreach ($this->hiddenColumns as $column) {
            unset($row[$column]);
        }
        return $row;
    }

    private function assertId(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('The ID must be a positive integer.');
        }
    }

    private function assertIdentifiers(array $identifiers, string $type): void
    {
        foreach ($identifiers as $identifier) {
            $this->assertIdentifier((string) $identifier, $type);
        }
    }

    private function assertIdentifier(string $identifier, string $type): void
    {
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $identifier) !== 1) {
            throw new InvalidArgumentException(sprintf('Invalid JSON %s: %s.', $type, $identifier));
        }
    }
}
