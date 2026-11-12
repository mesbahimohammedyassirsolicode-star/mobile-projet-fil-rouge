<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class AvisActiviteDAO extends BaseDAO
{
    protected string $tableName = 'avis_activite';
    protected string $primaryKey = 'id_avis_activite';
    protected array $writableColumns = ['note', 'commentaire', 'id_utilisateur', 'id_activite'];
    protected array $filterableColumns = ['id_utilisateur', 'id_activite', 'note'];
}
