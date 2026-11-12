<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class FavoriLieuDAO extends BaseDAO
{
    protected string $tableName = 'favori_lieu';
    protected string $primaryKey = 'id_favori_lieu';
    protected array $writableColumns = ['id_utilisateur', 'id_lieu'];
    protected array $filterableColumns = ['id_utilisateur', 'id_lieu'];
}
