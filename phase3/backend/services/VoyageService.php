<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class VoyageService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['titre', 'date_debut', 'date_fin', 'budget', 'description', 'id_utilisateur', 'id_destination'], ['titre', 'date_debut', 'date_fin', 'id_utilisateur', 'id_destination']);
        $this->stringField($data, 'titre', 100);
        $this->dateField($data, 'date_debut');
        $this->dateField($data, 'date_fin');
        $this->assertDateOrder($data, 'date_debut', 'date_fin');
        $this->nonNegativeNumber($data, 'budget');
        if (array_key_exists('description', $data) && $data['description'] !== null && $data['description'] !== '') {
            $this->stringField($data, 'description', 65535, false);
        }
        $this->integerField($data, 'id_utilisateur', true, true);
        $this->integerField($data, 'id_destination', true, true);
        $this->foreignKey($data['id_utilisateur'], 'utilisateur', 'id_utilisateur');
        $this->foreignKey($data['id_destination'], 'destination', 'id_destination');

        return $data;
    }
}
