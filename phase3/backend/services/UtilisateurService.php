<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;

final class UtilisateurService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $required = $isUpdate ? ['nom', 'prenom', 'email'] : ['nom', 'prenom', 'email', 'mot_de_passe'];
        $data = $this->payload($data, ['nom', 'prenom', 'email', 'mot_de_passe', 'telephone', 'date_naissance', 'photo'], $required);
        $this->stringField($data, 'nom', 50);
        $this->stringField($data, 'prenom', 50);
        $this->stringField($data, 'email', 100);
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Email address is invalid.');
        }
        if (array_key_exists('mot_de_passe', $data)) {
            $this->stringField($data, 'mot_de_passe', 255);
            if (strlen($data['mot_de_passe']) < 8) {
                throw new InvalidArgumentException('Password must contain at least 8 characters.');
            }
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        foreach (['telephone' => 20, 'photo' => 255] as $field => $length) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                $this->stringField($data, $field, $length, false);
            }
        }
        $this->dateField($data, 'date_naissance', false);

        return $data;
    }
}
