<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class VoyageDAO extends BaseDAO
{
    protected string $tableName = 'voyage';
    protected string $primaryKey = 'id_voyage';
    protected array $writableColumns = ['titre', 'date_debut', 'date_fin', 'budget', 'description', 'id_utilisateur', 'id_destination'];
    protected array $searchableColumns = ['titre', 'description'];
    protected array $filterableColumns = ['id_utilisateur', 'id_destination'];
}
