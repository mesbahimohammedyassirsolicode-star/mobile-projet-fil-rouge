<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class CategorieService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['nom', 'description'], ['nom']);
        $this->stringField($data, 'nom', 50);
        if (array_key_exists('description', $data) && $data['description'] !== null) {
            $this->stringField($data, 'description', 65535, false);
        }
        $this->assertUniquePair('categorie', ['nom'], [$data['nom']], $isUpdate ? $this->currentId : null, 'id_categorie');

        return $data;
    }
}
