<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;

final class AvisLieuService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['note', 'commentaire', 'id_utilisateur', 'id_lieu'], ['note', 'id_utilisateur', 'id_lieu']);
        $this->integerField($data, 'note', true);
        if ($data['note'] < 1 || $data['note'] > 5) {
            throw new InvalidArgumentException('Review note must be between 1 and 5.');
        }
        $this->integerField($data, 'id_utilisateur', true, true);
        $this->integerField($data, 'id_lieu', true, true);
        $this->foreignKey($data['id_utilisateur'], 'utilisateur', 'id_utilisateur');
        $this->foreignKey($data['id_lieu'], 'lieu_touristique', 'id_lieu');
        if (array_key_exists('commentaire', $data) && $data['commentaire'] !== null && $data['commentaire'] !== '') {
            $this->stringField($data, 'commentaire', 65535, false);
        }
        $this->assertUniquePair('avis_lieu', ['id_utilisateur', 'id_lieu'], [$data['id_utilisateur'], $data['id_lieu']], $isUpdate ? $this->currentId : null, 'id_avis_lieu');

        return $data;
    }
}
