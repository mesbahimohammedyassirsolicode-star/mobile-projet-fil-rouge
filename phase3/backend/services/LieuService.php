<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class LieuService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['nom', 'description', 'adresse', 'prix_entree', 'horaires', 'image', 'latitude', 'longitude', 'id_destination', 'id_categorie'], ['nom', 'description', 'id_destination', 'id_categorie']);
        $this->stringField($data, 'nom', 100);
        $this->stringField($data, 'description', 65535);
        foreach (['adresse' => 255, 'horaires' => 100, 'image' => 255] as $field => $length) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                $this->stringField($data, $field, $length, false);
            }
        }
        $this->integerField($data, 'id_destination', true, true);
        $this->integerField($data, 'id_categorie', true, true);
        $this->foreignKey($data['id_destination'], 'destination', 'id_destination');
        $this->foreignKey($data['id_categorie'], 'categorie', 'id_categorie');
        $this->nonNegativeNumber($data, 'prix_entree');

        return $data;
    }
}
