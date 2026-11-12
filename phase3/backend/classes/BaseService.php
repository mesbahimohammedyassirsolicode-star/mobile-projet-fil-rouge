<?php
declare(strict_types=1);

namespace App\Core;

use App\Config\JsonStorage;
use InvalidArgumentException;

abstract class BaseService
{
    protected ?int $currentId = null;

    public function __construct(
        protected BaseDAO $dao,
        protected JsonStorage $connection
    )
    {
    }

    public function getAll(array $filters = [], int $page = 1, int $perPage = 25): array
    {
        return $this->dao->findAll($filters, $page, $perPage);
    }

    public function getById(int $id): ?array
    {
        $this->assertId($id);

        return $this->dao->findById($id);
    }

    public function create(array $data): int
    {
        $this->currentId = null;
        return $this->dao->create($this->validateData($data, false));
    }

    public function update(int $id, array $data): bool
    {
        $this->assertId($id);
        $this->currentId = $id;
        $data = $this->validateData($data, true);

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

    protected function validateData(array $data, bool $isUpdate): array
        {
            $this->assertData($data);

            return $data;
        }

        protected function payload(array $data, array $allowed, array $required = []): array
        {
            $unknown = array_diff(array_keys($data), $allowed);
            if ($unknown !== []) {
                throw new InvalidArgumentException('Unknown fields: ' . implode(', ', $unknown) . '.');
            }

            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                    throw new InvalidArgumentException(sprintf('Field "%s" is required.', $field));
                }
            }

            return $data;
        }

        protected function stringField(array &$data, string $field, int $maxLength, bool $required = true): void
        {
            if (!array_key_exists($field, $data)) {
                if ($required) {
                    throw new InvalidArgumentException(sprintf('Field "%s" is required.', $field));
                }
                return;
            }
            if (!is_string($data[$field]) || trim($data[$field]) === '') {
                throw new InvalidArgumentException(sprintf('Field "%s" must be a non-empty string.', $field));
            }
            $data[$field] = trim($data[$field]);
            $length = function_exists('mb_strlen') ? mb_strlen($data[$field]) : strlen($data[$field]);
            if ($length > $maxLength) {
                throw new InvalidArgumentException(sprintf('Field "%s" is too long.', $field));
            }
        }

        protected function integerField(array &$data, string $field, bool $required = true, bool $positive = false): void
        {
            if (!array_key_exists($field, $data)) {
                if ($required) {
                    throw new InvalidArgumentException(sprintf('Field "%s" is required.', $field));
                }
                return;
            }
            if (filter_var($data[$field], FILTER_VALIDATE_INT) === false) {
                throw new InvalidArgumentException(sprintf('Field "%s" must be an integer.', $field));
            }
            $data[$field] = (int) $data[$field];
            if ($positive && $data[$field] < 1) {
                throw new InvalidArgumentException(sprintf('Field "%s" must be positive.', $field));
            }
        }

        protected function nonNegativeNumber(array &$data, string $field, bool $required = false): void
        {
            if (!array_key_exists($field, $data)) {
                if ($required) {
                    throw new InvalidArgumentException(sprintf('Field "%s" is required.', $field));
                }
                return;
            }
            if (!is_numeric($data[$field]) || (float) $data[$field] < 0) {
                throw new InvalidArgumentException(sprintf('Field "%s" must be non-negative.', $field));
            }
            $data[$field] = (float) $data[$field];
        }

        protected function dateField(array &$data, string $field, bool $required = true): void
        {
            if (!array_key_exists($field, $data)) {
                if ($required) {
                    throw new InvalidArgumentException(sprintf('Field "%s" is required.', $field));
                }
                return;
            }
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', (string) $data[$field]);
            if ($date === false || $date->format('Y-m-d') !== $data[$field]) {
                throw new InvalidArgumentException(sprintf('Field "%s" must be a valid date.', $field));
            }
        }

        protected function timeField(array &$data, string $field): void
        {
            if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                return;
            }
            if (preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', (string) $data[$field]) !== 1) {
                throw new InvalidArgumentException(sprintf('Field "%s" must be a valid time.', $field));
            }
        }

        protected function assertTimeOrder(array $data, string $startField, string $endField): void
        {
            if (empty($data[$startField]) || empty($data[$endField])) {
                return;
            }
            if ((string) $data[$endField] <= (string) $data[$startField]) {
                throw new InvalidArgumentException(sprintf('%s must be later than %s.', $endField, $startField));
            }
        }

        protected function assertDateOrder(array $data, string $startField, string $endField): void
        {
            if (strcmp((string) $data[$endField], (string) $data[$startField]) < 0) {
                throw new InvalidArgumentException(sprintf('%s cannot be before %s.', $endField, $startField));
            }
        }

        protected function assertUniquePair(string $table, array $fields, array $values, ?int $ignoreId = null, string $primaryKey = ''): void
        {
            if (count($fields) !== count($values) || preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $table) !== 1) {
                throw new InvalidArgumentException('Invalid uniqueness rule.');
            }
            foreach ($fields as $field) {
                if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', (string) $field) !== 1) {
                    throw new InvalidArgumentException('Invalid uniqueness field.');
                }
            }
            if ($ignoreId !== null && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $primaryKey) !== 1) {
                throw new InvalidArgumentException('Invalid uniqueness primary key.');
            }
            $rows = $this->connection->all($table);
            foreach ($rows as $row) {
                if ($ignoreId !== null && (int) ($row[$primaryKey] ?? 0) === $ignoreId) {
                    continue;
                }
                $matches = true;
                foreach (array_values($fields) as $index => $field) {
                    if ((string) ($row[$field] ?? '') !== (string) $values[$index]) {
                        $matches = false;
                        break;
                    }
                }
                if ($matches) {
                    throw new InvalidArgumentException('A record with the same unique values already exists.');
                }
            }
        }

        protected function assertDateWithinVoyage(int $voyageId, string $date): void
        {
            $voyage = null;
            foreach ($this->connection->all('voyage') as $row) {
                if ((int) ($row['id_voyage'] ?? 0) === $voyageId) {
                    $voyage = $row;
                    break;
                }
            }
            if ($voyage === null) {
                throw new InvalidArgumentException('Referenced voyage does not exist.');
            }
            if ($date < $voyage['date_debut'] || $date > $voyage['date_fin']) {
                throw new InvalidArgumentException('Step date must be within the voyage dates.');
            }
        }

        protected function foreignKey(int $id, string $table, string $column): void
        {
            if ($id < 1 || preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $table) !== 1 || preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $column) !== 1) {
                throw new InvalidArgumentException('Invalid foreign key reference.');
            }
            foreach ($this->connection->all($table) as $row) {
                if ((int) ($row[$column] ?? 0) === $id) {
                    return;
                }
            }
            throw new InvalidArgumentException(sprintf('Referenced record does not exist: %s=%d.', $column, $id));
        }
}
