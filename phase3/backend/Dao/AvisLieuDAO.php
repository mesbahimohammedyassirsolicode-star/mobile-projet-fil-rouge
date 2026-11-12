<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class AvisLieuDAO extends BaseDAO
{
    protected string $tableName = 'avis_lieu';
    protected string $primaryKey = 'id_avis_lieu';
    protected array $writableColumns = ['note', 'commentaire', 'id_utilisateur', 'id_lieu'];
    protected array $filterableColumns = ['id_utilisateur', 'id_lieu', 'note'];
}
