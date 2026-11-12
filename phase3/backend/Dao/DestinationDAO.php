<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class DestinationDAO extends BaseDAO
{
    protected string $tableName = 'destination';
    protected string $primaryKey = 'id_destination';
    protected array $writableColumns = ['nom', 'description', 'ville', 'region', 'pays', 'image', 'latitude', 'longitude'];
    protected array $searchableColumns = ['nom', 'ville', 'region', 'pays'];
    protected array $filterableColumns = ['pays', 'region'];
}
