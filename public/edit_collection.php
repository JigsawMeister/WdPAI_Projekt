<?php
session_start();
require_once __DIR__ . '/../classes/Collection.php';

if (!isset($_SESSION['user'])) {
    die("Brak dostępu");
}

$error = "";
$collectionId = $_GET['id'] ?? null;
if (!$collectionId || !is_numeric($collectionId)) die("Brak ID kolekcji");

$collectionObj = new Collection();
$collection = $collectionObj->getById($collectionId);

if (!$collection || $collection['user_id'] != $_SESSION['user']['id']) {
    die("Brak uprawnień");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if (!$name) {
        $error = "Nazwa kolekcji jest wymagana";
    }
    else {
        $collectionObj->setId($collectionId);
        $collectionObj->setName($name);

        if ($collectionObj->update()) {
            header("Location: ../index.php");
            exit;
        }
        else {
            $error = "Błąd aktualizacji kolekcji";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Edytuj kolekcję</title>
<style>
body { font-family: Arial; max-width: 600px; margin: auto; }
input { width: 100%; margin-bottom: 10px; }
</style>
</head>
<body>
<h2>Edytuj kolekcję</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>Nazwa kolekcji: <input type="text" name="name" value="<?php echo htmlspecialchars($collection['name']); ?>" required></label>
    <button type="submit">Zapisz</button>
</form>
<a href="../index.php">Powrót do strony głównej</a>
</body>
</html>