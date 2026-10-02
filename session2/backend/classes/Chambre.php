<?php
declare(strict_types=1);

class Chambre
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        $rooms = json_decode($json ?: '[]', true);

        return is_array($rooms) ? $rooms : [];
    }

    public function add(string $numero, float $prix, int $category_id): array
    {
        $rooms = $this->getAll();
        $nextId = 1;

        foreach ($rooms as $room) {
            $nextId = max($nextId, ((int) ($room['id'] ?? 0)) + 1);
        }

        $newRoom = [
            'id' => $nextId,
            'numero' => $numero,
            'prix' => $prix,
            'category_id' => $category_id,
        ];
        $rooms[] = $newRoom;

        $json = json_encode($rooms, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || file_put_contents($this->filePath, $json, LOCK_EX) === false) {
            throw new RuntimeException('Impossible d’enregistrer les chambres.');
        }

        return $newRoom;
    }

    public function getByCategory(int $category_id): array
    {
        return array_values(array_filter(
            $this->getAll(),
            fn (array $room): bool => (int) ($room['category_id'] ?? 0) === $category_id
        ));
    }
}
