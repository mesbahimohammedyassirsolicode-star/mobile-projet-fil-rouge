<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class EtapeLieuDAO extends BaseDAO
{
    protected string $tableName = 'etape_lieu';
    protected string $primaryKey = 'id_etape_lieu';
    protected array $writableColumns = ['date_etape', 'heure_debut', 'heure_fin', 'ordre', 'notes', 'id_voyage', 'id_lieu'];
    protected array $filterableColumns = ['id_voyage', 'id_lieu', 'date_etape'];
}
