<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;

final class EtapeActiviteService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['date_etape', 'heure_debut', 'heure_fin', 'ordre', 'notes', 'id_voyage', 'id_activite'], ['date_etape', 'ordre', 'id_voyage', 'id_activite']);
        $this->dateField($data, 'date_etape');
        $this->timeField($data, 'heure_debut');
        $this->timeField($data, 'heure_fin');
        $this->assertTimeOrder($data, 'heure_debut', 'heure_fin');
        $this->integerField($data, 'ordre', true, true);
        $this->integerField($data, 'id_voyage', true, true);
        $this->integerField($data, 'id_activite', true, true);
        $this->foreignKey($data['id_voyage'], 'voyage', 'id_voyage');
        $this->foreignKey($data['id_activite'], 'activite', 'id_activite');
        $this->assertDateWithinVoyage($data['id_voyage'], $data['date_etape']);
        if (array_key_exists('notes', $data) && $data['notes'] !== null && $data['notes'] !== '') {
            $this->stringField($data, 'notes', 65535, false);
        }

        return $data;
    }
}
