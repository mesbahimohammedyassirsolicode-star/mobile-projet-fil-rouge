<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class FavoriActiviteService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['id_utilisateur', 'id_activite'], ['id_utilisateur', 'id_activite']);
        $this->integerField($data, 'id_utilisateur', true, true);
        $this->integerField($data, 'id_activite', true, true);
        $this->foreignKey($data['id_utilisateur'], 'utilisateur', 'id_utilisateur');
        $this->foreignKey($data['id_activite'], 'activite', 'id_activite');
        $this->assertUniquePair('favori_activite', ['id_utilisateur', 'id_activite'], [$data['id_utilisateur'], $data['id_activite']], $isUpdate ? $this->currentId : null, 'id_favori_activite');

        return $data;
    }
}
