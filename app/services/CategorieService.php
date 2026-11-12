<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;

final class CategorieService extends BaseService
{
    public function create(array $data): int
    {
        return parent::create($this->validateCategoryData($data));
    }

    public function update(int $id, array $data): bool
    {
        return parent::update($id, $this->validateCategoryData($data));
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

        if (strlen($name) > 50) {
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
}
