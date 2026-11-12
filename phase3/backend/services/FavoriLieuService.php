<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class FavoriLieuService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['id_utilisateur', 'id_lieu'], ['id_utilisateur', 'id_lieu']);
        $this->integerField($data, 'id_utilisateur', true, true);
        $this->integerField($data, 'id_lieu', true, true);
        $this->foreignKey($data['id_utilisateur'], 'utilisateur', 'id_utilisateur');
        $this->foreignKey($data['id_lieu'], 'lieu_touristique', 'id_lieu');
        $this->assertUniquePair('favori_lieu', ['id_utilisateur', 'id_lieu'], [$data['id_utilisateur'], $data['id_lieu']], $isUpdate ? $this->currentId : null, 'id_favori_lieu');

        return $data;
    }
}
