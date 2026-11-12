<?php
declare(strict_types=1);

namespace App\Api;

use App\Core\BaseService;
use InvalidArgumentException;
use RuntimeException;

abstract class BaseController
{
    public function __construct(
        private BaseService $service,
        private string $resourceName
    ) {
    }

    public function dispatch(
        string $method,
        ?int $id,
        array $query,
        array $body,
        array $headers = []
    ): array {
        $method = strtoupper($method);
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $this->authorize($headers);
        }

        return match ($method) {
            'GET' => $this->read($id, $query),
            'POST' => $this->create($body, $id),
            'PUT', 'PATCH' => $this->update($id, $body),
            'DELETE' => $this->delete($id),
            default => throw new HttpException('HTTP method is not allowed.', 405),
        };
    }

    private function read(?int $id, array $query): array
    {
        if ($id !== null) {
            $record = $this->service->getById($id);
            if ($record === null) {
                throw new HttpException($this->resourceName . ' record was not found.', 404);
            }

            return [200, ['success' => true, 'message' => 'Record retrieved successfully.', 'data' => $record]];
        }

        $page = $this->positiveInt($query['page'] ?? 1, 'page');
        $perPage = $this->positiveInt($query['per_page'] ?? 25, 'per_page');
        return [200, ['success' => true, 'message' => 'Records retrieved successfully.', 'data' => $this->service->getAll($query, $page, $perPage)]];
    }

    private function create(array $body, ?int $id): array
    {
        if ($id !== null) {
            throw new HttpException('POST cannot target an individual record.', 405);
        }
        $newId = $this->service->create($body);
        return [201, ['success' => true, 'message' => 'Record created successfully.', 'data' => $this->service->getById($newId)]];
    }

    private function update(?int $id, array $body): array
    {
        if ($id === null) {
            throw new HttpException('An ID is required for updates.', 400);
        }
        if ($this->service->getById($id) === null) {
            throw new HttpException($this->resourceName . ' record was not found.', 404);
        }
        $this->service->update($id, $body);
        return [200, ['success' => true, 'message' => 'Record updated successfully.', 'data' => $this->service->getById($id)]];
    }

    private function delete(?int $id): array
    {
        if ($id === null) {
            throw new HttpException('An ID is required for deletion.', 400);
        }
        if (!$this->service->delete($id)) {
            throw new HttpException($this->resourceName . ' record was not found.', 404);
        }
        return [200, ['success' => true, 'message' => 'Record deleted successfully.', 'data' => []]];
    }

    private function authorize(array $headers): void
    {
        $expected = getenv('ADMIN_API_TOKEN');
        if ($expected === false || $expected === '') {
            return;
        }
        $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (!is_string($authorization) || !str_starts_with($authorization, 'Bearer ')) {
            throw new HttpException('Authentication is required.', 401);
        }
        $provided = substr($authorization, 7);
        if (!hash_equals($expected, $provided)) {
            throw new HttpException('Authentication failed.', 403);
        }
    }

    private function positiveInt(mixed $value, string $field): int
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false || (int) $value < 1) {
            throw new InvalidArgumentException(sprintf('%s must be a positive integer.', $field));
        }
        return (int) $value;
    }
}
