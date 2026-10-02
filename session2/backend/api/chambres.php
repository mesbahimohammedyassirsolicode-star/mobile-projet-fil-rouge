<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../classes/Chambre.php';

$chambres = new Chambre(__DIR__ . '/../data/chambres.json');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    if (isset($_GET['category_id'])) {
        $categoryId = filter_var($_GET['category_id'], FILTER_VALIDATE_INT);
        if ($categoryId === false || $categoryId < 1 || $categoryId > 3) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Catégorie invalide.'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo json_encode($chambres->getByCategory($categoryId), JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode($chambres->getAll(), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    header('Allow: GET, POST');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Le corps de la requête doit contenir un objet JSON valide.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$numero = trim((string) ($data['numero'] ?? ''));
$prix = $data['prix'] ?? null;
$categoryId = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT);

if ($numero === '' || !is_numeric($prix) || (float) $prix <= 0 || $categoryId === false || $categoryId < 1 || $categoryId > 3) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Veuillez indiquer un numéro, un prix positif et une catégorie valide.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $room = $chambres->add($numero, (float) $prix, $categoryId);
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Chambre ajoutée avec succès.',
        'chambre' => $room,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de l’enregistrement.'], JSON_UNESCAPED_UNICODE);
}
