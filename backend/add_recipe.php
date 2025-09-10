<?php
session_start();
require_once __DIR__ . '/../classes/Recipe.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Musisz być zalogowany', 'success' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['error' => 'Brak danych', 'success' => false]);
    exit;
}

$title = $input['title'] ?? '';
$description = $input['description'] ?? '';
$ingredients = $input['ingredients'] ?? '';
$steps = $input['steps'] ?? '';
$collections = $input['collections'] ?? [];
$userId = $_SESSION['user']['id'];

if (empty($title) || empty($description) || empty($ingredients) || empty($steps)) {
    echo json_encode(['error' => 'Wszystkie pola są wymagane', 'success' => false]);
    exit;
}

$recipe = new Recipe();
$recipe->setTitle($title);
$recipe->setDescription($description);
$recipe->setIngredients($ingredients);
$recipe->setSteps($steps);
$recipe->setUserId($userId);
$recipe->setCollections($collections);

if ($recipe->create()) {
    echo json_encode(['message' => 'Przepis dodany pomyślnie', 'success' => true]);
} else {
    echo json_encode(['error' => 'Błąd podczas dodawania przepisu', 'success' => false]);
}
