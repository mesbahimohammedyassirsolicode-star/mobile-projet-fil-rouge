<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class LieuDAO extends BaseDAO
{
    protected string $tableName = 'lieu_touristique';
    protected string $primaryKey = 'id_lieu';
    protected array $writableColumns = ['nom', 'description', 'adresse', 'prix_entree', 'horaires', 'image', 'latitude', 'longitude', 'id_destination', 'id_categorie'];
    protected array $searchableColumns = ['nom', 'description', 'adresse', 'horaires'];
    protected array $filterableColumns = ['id_destination', 'id_categorie'];
}
