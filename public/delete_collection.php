<?php
session_start();
require_once __DIR__ . '/../classes/Collection.php';

if (!isset($_SESSION['user'])) {
    die("Brak dostępu");
}

$collectionId = $_GET['id'] ?? null;
if (!$collectionId || !is_numeric($collectionId)) {
    die("Brak ID kolekcji");
}

$collectionObj = new Collection();
$collection = $collectionObj->getById($collectionId);

if (!$collection || $collection['user_id'] != $_SESSION['user']['id']) {
    die("Brak uprawnień");
}

$collectionObj->setId($collectionId);

if ($collectionObj->delete()) {
    header("Location: ../index.php");
    exit;
}
else {
    die("Błąd usuwania kolekcji");
}