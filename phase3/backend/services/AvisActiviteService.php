<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;

final class AvisActiviteService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['note', 'commentaire', 'id_utilisateur', 'id_activite'], ['note', 'id_utilisateur', 'id_activite']);
        $this->integerField($data, 'note', true);
        if ($data['note'] < 1 || $data['note'] > 5) {
            throw new InvalidArgumentException('Review note must be between 1 and 5.');
        }
        $this->integerField($data, 'id_utilisateur', true, true);
        $this->integerField($data, 'id_activite', true, true);
        $this->foreignKey($data['id_utilisateur'], 'utilisateur', 'id_utilisateur');
        $this->foreignKey($data['id_activite'], 'activite', 'id_activite');
        if (array_key_exists('commentaire', $data) && $data['commentaire'] !== null && $data['commentaire'] !== '') {
            $this->stringField($data, 'commentaire', 65535, false);
        }
        $this->assertUniquePair('avis_activite', ['id_utilisateur', 'id_activite'], [$data['id_utilisateur'], $data['id_activite']], $isUpdate ? $this->currentId : null, 'id_avis_activite');

        return $data;
    }
}
