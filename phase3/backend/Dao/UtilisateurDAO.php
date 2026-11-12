<?php
declare(strict_types=1);

namespace App\Daos;

use App\Core\BaseDAO;

final class UtilisateurDAO extends BaseDAO
{
    protected string $tableName = 'utilisateur';
    protected string $primaryKey = 'id_utilisateur';
    protected array $writableColumns = ['nom', 'prenom', 'email', 'mot_de_passe', 'telephone', 'date_naissance', 'photo'];
    protected array $searchableColumns = ['nom', 'prenom', 'email', 'telephone'];
    protected array $filterableColumns = ['email'];
    protected array $hiddenColumns = ['mot_de_passe'];
}
