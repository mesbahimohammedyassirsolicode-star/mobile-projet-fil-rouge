<?php
declare(strict_types=1);

namespace App\Config;

use RuntimeException;

final class JsonStorage
{
    public function __construct(private string $file)
    {
        $directory = dirname($this->file);
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create the JSON data directory.');
        }

        if (!is_file($this->file)) {
            $this->write($this->emptyData());
        }
    }

    public function all(string $table): array
    {
        $data = $this->read();
        $rows = $data[$table] ?? [];

        if (!is_array($rows)) {
            throw new RuntimeException(sprintf('JSON table "%s" must contain an array.', $table));
        }

        return array_values(array_filter($rows, 'is_array'));
    }

    public function insert(string $table, string $primaryKey, array $row): int
    {
        $data = $this->read();
        $rows = $data[$table] ?? [];
        $id = 1;
        foreach ($rows as $existing) {
            $id = max($id, ((int) ($existing[$primaryKey] ?? 0)) + 1);
        }

        $row[$primaryKey] = $id;
        $this->applyDefaults($table, $row);
        $rows[] = $row;
        $data[$table] = $rows;
        $this->write($data);

        return $id;
    }

    public function update(string $table, string $primaryKey, int $id, array $changes): bool
    {
        $data = $this->read();
        $rows = $data[$table] ?? [];
        $updated = false;

        foreach ($rows as &$row) {
            if ((int) ($row[$primaryKey] ?? 0) !== $id) {
                continue;
            }
            $row = array_merge($row, $changes);
            $updated = true;
            break;
        }
        unset($row);

        if ($updated) {
            $data[$table] = $rows;
            $this->write($data);
        }

        return $updated;
    }

    public function delete(string $table, string $primaryKey, int $id): bool
    {
        $data = $this->read();
        $rows = $data[$table] ?? [];
        $remaining = array_values(array_filter(
            $rows,
            static fn(array $row): bool => (int) ($row[$primaryKey] ?? 0) !== $id
        ));

        if (count($remaining) === count($rows)) {
            return false;
        }

        $data[$table] = $remaining;
        $this->write($data);
        return true;
    }

    private function read(): array
    {
        $contents = file_get_contents($this->file);
        if ($contents === false || trim($contents) === '') {
            return $this->emptyData();
        }

        $data = json_decode($contents, true);
        if (!is_array($data)) {
            throw new RuntimeException('The JSON data file is invalid.');
        }

        return $data;
    }

    private function write(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if (file_put_contents($this->file, $json . PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException('Unable to write the JSON data file.');
        }
    }

    private function applyDefaults(string $table, array &$row): void
    {
        if (in_array($table, ['favori_lieu', 'favori_activite'], true)) {
            $row['date_ajout'] = date('Y-m-d H:i:s');
        }
        if (in_array($table, ['avis_lieu', 'avis_activite'], true)) {
            $row['date_avis'] = date('Y-m-d H:i:s');
        }
        if ($table === 'utilisateur') {
            $row['date_inscription'] = date('Y-m-d H:i:s');
        }
    }

    private function emptyData(): array
    {
        return [
            'utilisateur' => [],
            'destination' => [],
            'categorie' => [],
            'lieu_touristique' => [],
            'activite' => [],
            'favori_lieu' => [],
            'favori_activite' => [],
            'avis_lieu' => [],
            'avis_activite' => [],
            'voyage' => [],
            'etape_lieu' => [],
            'etape_activite' => [],
        ];
    }
}
