<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class EtapeActiviteDAO extends BaseDAO
{
    protected string $tableName = 'etape_activite';
    protected string $primaryKey = 'id_etape_activite';
    protected array $writableColumns = ['date_etape', 'heure_debut', 'heure_fin', 'ordre', 'notes', 'id_voyage', 'id_activite'];
    protected array $filterableColumns = ['id_voyage', 'id_activite', 'date_etape'];
}
