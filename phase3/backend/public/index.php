<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

function respond(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_THROW_ON_ERROR);
    exit;
}

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($path === '') {
    respond(200, ['success' => true, 'message' => 'Smart Tourism backend is ready.', 'data' => []]);
}

$segments = explode('/', $path);
if (($segments[0] ?? '') !== 'api' || !isset($segments[1]) || $segments[1] === '') {
    respond(404, ['success' => false, 'message' => 'Route not found.', 'data' => []]);
}

$routes = [
    'utilisateurs' => [\App\Api\UtilisateurController::class, \App\Services\UtilisateurService::class, \App\Daos\UtilisateurDAO::class, 'users'],
    'destinations' => [\App\Api\DestinationController::class, \App\Services\DestinationService::class, \App\Daos\DestinationDAO::class, 'destinations'],
    'categories' => [\App\Api\CategorieController::class, \App\Services\CategorieService::class, \App\Daos\CategorieDAO::class, 'categories'],
    'lieux' => [\App\Api\LieuController::class, \App\Services\LieuService::class, \App\Daos\LieuDAO::class, 'places'],
    'activites' => [\App\Api\ActiviteController::class, \App\Services\ActiviteService::class, \App\Daos\ActiviteDAO::class, 'activities'],
    'favoris-lieux' => [\App\Api\FavoriLieuController::class, \App\Services\FavoriLieuService::class, \App\Daos\FavoriLieuDAO::class, 'place favorites'],
    'favoris-activites' => [\App\Api\FavoriActiviteController::class, \App\Services\FavoriActiviteService::class, \App\Daos\FavoriActiviteDAO::class, 'activity favorites'],
    'avis-lieux' => [\App\Api\AvisLieuController::class, \App\Services\AvisLieuService::class, \App\Daos\AvisLieuDAO::class, 'place reviews'],
    'avis-activites' => [\App\Api\AvisActiviteController::class, \App\Services\AvisActiviteService::class, \App\Daos\AvisActiviteDAO::class, 'activity reviews'],
    'voyages' => [\App\Api\VoyageController::class, \App\Services\VoyageService::class, \App\Daos\VoyageDAO::class, 'trips'],
    'etapes-lieux' => [\App\Api\EtapeLieuController::class, \App\Services\EtapeLieuService::class, \App\Daos\EtapeLieuDAO::class, 'place trip steps'],
    'etapes-activites' => [\App\Api\EtapeActiviteController::class, \App\Services\EtapeActiviteService::class, \App\Daos\EtapeActiviteDAO::class, 'activity trip steps'],
];

$route = $routes[$segments[1]] ?? null;
if ($route === null || count($segments) > 3) {
    respond(404, ['success' => false, 'message' => 'Route not found.', 'data' => []]);
}

$id = null;
if (isset($segments[2])) {
    if (filter_var($segments[2], FILTER_VALIDATE_INT) === false || (int) $segments[2] < 1) {
        respond(400, ['success' => false, 'message' => 'Resource ID must be a positive integer.', 'data' => []]);
    }
    $id = (int) $segments[2];
}

try {
    $connection = \App\Config\Database::getConnection();
    [$controllerClass, $serviceClass, $daoClass, $resourceName] = $route;
    $dao = new $daoClass($connection);
    $service = new $serviceClass($dao, $connection);
    $controller = new $controllerClass($service, $resourceName);
    $body = in_array($method, ['POST', 'PUT', 'PATCH'], true)
        ? (json_decode((string) file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR) ?? [])
        : [];
    [$status, $payload] = $controller->dispatch(
        $method,
        $id,
        $_GET,
        is_array($body) ? $body : [],
        function_exists('getallheaders') ? getallheaders() : []
    );
    respond($status, $payload);
} catch (\App\Api\HttpException $exception) {
    respond($exception->getCode() >= 400 ? $exception->getCode() : 400, ['success' => false, 'message' => $exception->getMessage(), 'data' => []]);
} catch (\InvalidArgumentException $exception) {
    respond(422, ['success' => false, 'message' => $exception->getMessage(), 'data' => []]);
} catch (\JsonException $exception) {
    respond(400, ['success' => false, 'message' => 'Request body must contain valid JSON.', 'data' => []]);
} catch (\RuntimeException $exception) {
    error_log($exception->getMessage());
    respond(500, ['success' => false, 'message' => $exception->getMessage(), 'data' => []]);
} catch (\Throwable $exception) {
    error_log($exception->getMessage());
    respond(500, ['success' => false, 'message' => 'An internal server error occurred.', 'data' => []]);
}
