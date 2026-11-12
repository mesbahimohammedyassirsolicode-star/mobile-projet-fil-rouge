<?php
declare(strict_types=1);

namespace App\Models;

final class Categorie
{
    public function __construct(
        public ?int $id,
        public string $nom,
        public ?string $description = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id_categorie']) ? (int) $data['id_categorie'] : null,
            (string) ($data['nom'] ?? ''),
            isset($data['description']) ? (string) $data['description'] : null
        );
    }
}
