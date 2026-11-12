<?php
declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use PDO;

abstract class BaseDAO
{
    protected string $tableName = '';
    protected string $primaryKey = '';

    public function __construct(protected PDO $connection)
    {
        $this->assertIdentifier($this->tableName, 'table name');
        $this->assertIdentifier($this->primaryKey, 'primary key');
    }

    public function findAll(): array
    {
        $statement = $this->connection->query(sprintf('SELECT * FROM `%s`', $this->tableName));

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $this->assertId($id);

        $statement = $this->connection->prepare(
            sprintf('SELECT * FROM `%s` WHERE `%s` = :id LIMIT 1', $this->tableName, $this->primaryKey)
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        return $result === false ? null : $result;
    }

    public function create(array $data): int
    {
        $this->assertData($data);
        $columns = array_keys($data);
        $this->assertIdentifiers($columns, 'column');
        $placeholders = array_map(static fn(string $column): string => ':' . $column, $columns);

        $statement = $this->connection->prepare(sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $this->tableName,
            implode('`, `', $columns),
            implode(', ', $placeholders)
        ));
        $this->bindData($statement, $data);
        $statement->execute();

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $this->assertId($id);
        $this->assertData($data);
        $columns = array_keys($data);
        $this->assertIdentifiers($columns, 'column');
        $assignments = array_map(
            static fn(string $column): string => sprintf('`%s` = :%s', $column, $column),
            $columns
        );

        $statement = $this->connection->prepare(sprintf(
            'UPDATE `%s` SET %s WHERE `%s` = :id',
            $this->tableName,
            implode(', ', $assignments),
            $this->primaryKey
        ));
        $this->bindData($statement, $data);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $this->assertId($id);

        $statement = $this->connection->prepare(
            sprintf('DELETE FROM `%s` WHERE `%s` = :id', $this->tableName, $this->primaryKey)
        );
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    private function bindData(\PDOStatement $statement, array $data): void
    {
        foreach ($data as $column => $value) {
            $statement->bindValue(':' . $column, $value);
        }
    }

    private function assertData(array $data): void
    {
        if ($data === []) {
            throw new InvalidArgumentException('Database data cannot be empty.');
        }
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
            throw new InvalidArgumentException(sprintf('Invalid SQL %s: %s.', $type, $identifier));
        }
    }
}
