<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class FavoriActiviteDAO extends BaseDAO
{
    protected string $tableName = 'favori_activite';
    protected string $primaryKey = 'id_favori_activite';
    protected array $writableColumns = ['id_utilisateur', 'id_activite'];
    protected array $filterableColumns = ['id_utilisateur', 'id_activite'];
}
