<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\BaseService;
use InvalidArgumentException;

final class DestinationService extends BaseService
{
    protected function validateData(array $data, bool $isUpdate): array
    {
        $data = $this->payload($data, ['nom', 'description', 'ville', 'region', 'pays', 'image', 'latitude', 'longitude'], ['nom', 'description', 'ville', 'pays']);
        foreach (['nom' => 100, 'description' => 65535, 'ville' => 100, 'pays' => 100] as $field => $length) {
            $this->stringField($data, $field, $length);
        }
        foreach (['region' => 100, 'image' => 255] as $field => $length) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                $this->stringField($data, $field, $length, false);
            }
        }
        $this->coordinate($data, 'latitude', -90, 90);
        $this->coordinate($data, 'longitude', -180, 180);

        return $data;
    }

    private function coordinate(array &$data, string $field, float $min, float $max): void
    {
        if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
            return;
        }
        if (!is_numeric($data[$field]) || (float) $data[$field] < $min || (float) $data[$field] > $max) {
            throw new InvalidArgumentException(sprintf('Field "%s" is outside its valid range.', $field));
        }
        $data[$field] = (float) $data[$field];
    }
}
