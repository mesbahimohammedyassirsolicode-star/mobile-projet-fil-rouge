<?php
declare(strict_types=1);

namespace App\Models;

final class Categorie extends Entity
{
    private ?int $id;
    private string $nom;
    private ?string $description;

    public function __construct(?int $id = null, string $nom = '', ?string $description = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id_categorie']) ? (int) $data['id_categorie'] : null,
            (string) ($data['nom'] ?? ''),
            isset($data['description']) ? (string) $data['description'] : null
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
