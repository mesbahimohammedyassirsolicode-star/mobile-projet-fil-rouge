<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class CategorieDAO extends BaseDAO
{
    protected string $tableName = 'categorie';
    protected string $primaryKey = 'id_categorie';
    protected array $writableColumns = ['nom', 'description'];
    protected array $searchableColumns = ['nom', 'description'];
}
