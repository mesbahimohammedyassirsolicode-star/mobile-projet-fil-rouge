<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;
use PDOException;

final class CategorieService extends BaseService
{
    public function create(array $data): int
    {
        try {
            return parent::create($this->validateCategoryData($data));
        } catch (PDOException $exception) {
            $this->rethrowDuplicateName($exception);
            throw $exception;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return parent::update($id, $this->validateCategoryData($data));
        } catch (PDOException $exception) {
            $this->rethrowDuplicateName($exception);
            throw $exception;
        }
    }

    private function validateCategoryData(array $data): array
    {
        if (!array_key_exists('nom', $data) || !is_string($data['nom'])) {
            throw new InvalidArgumentException('Category name is required.');
        }

        $name = trim($data['nom']);
        if ($name === '') {
            throw new InvalidArgumentException('Category name cannot be empty.');
        }

        if ($this->characterLength($name) > 50) {
            throw new InvalidArgumentException('Category name cannot exceed 50 characters.');
        }

        $data['nom'] = $name;

        if (array_key_exists('description', $data) && $data['description'] !== null) {
            if (!is_string($data['description'])) {
                throw new InvalidArgumentException('Category description must be a string or null.');
            }

            $data['description'] = trim($data['description']);
        }

        return $data;
    }

    private function rethrowDuplicateName(PDOException $exception): void
    {
        if ($exception->getCode() === '23000') {
            throw new InvalidArgumentException('A category with this name already exists.', 0, $exception);
        }
    }

    private function characterLength(string $value): int
    {
        $length = preg_match_all('/./us', $value);

        if ($length === false) {
            throw new InvalidArgumentException('Category name must contain valid UTF-8 text.');
        }

        return $length;
    }
}
