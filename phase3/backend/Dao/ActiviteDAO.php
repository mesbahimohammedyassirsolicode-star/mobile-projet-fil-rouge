<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class ActiviteDAO extends BaseDAO
{
    protected string $tableName = 'activite';
    protected string $primaryKey = 'id_activite';
    protected array $writableColumns = ['nom', 'description', 'duree', 'prix', 'image', 'niveau', 'id_destination', 'id_categorie'];
    protected array $searchableColumns = ['nom', 'description', 'niveau'];
    protected array $filterableColumns = ['id_destination', 'id_categorie', 'niveau'];
}
