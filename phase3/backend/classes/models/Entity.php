<?php
declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

abstract class Entity
{
    private array $attributes = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $name => $value) {
            $this->set((string) $name, $value);
        }
    }

    public function get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function set(string $name, mixed $value): static
    {
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $name) !== 1) {
            throw new InvalidArgumentException('Invalid entity property name.');
        }

        $this->attributes[$name] = $value;
        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
