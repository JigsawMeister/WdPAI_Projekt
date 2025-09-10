<?php
session_start();
require_once __DIR__ . '/../classes/Recipe.php';

if (!isset($_SESSION['user'])) {
    die("Brak dostępu");
}

$recipeObj = new Recipe();
$error = "";

$recipeId = $_GET['id'] ?? null;
if (!$recipeId || !is_numeric($recipeId)) {
    die("Brak ID przepisu");
}

$recipe = $recipeObj->getById($recipeId);
if (!$recipe) {
    die("Przepis nie istnieje");
}

if ($_SESSION['user']['id'] != $recipe['user_id'] && $_SESSION['user']['role'] !== 'admin') {
    die("Brak uprawnień");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeObj->setId($recipeId);

    if ($recipeObj->delete()) {
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Błąd usuwania przepisu";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Usuń przepis</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: auto; }
        button { margin-top: 10px; }
    </style>
</head>
<body>
<h2>Usuń przepis: <?php echo htmlspecialchars($recipe['title']); ?></h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<p>Czy na pewno chcesz usunąć ten przepis?</p>

<form method="POST">
    <button type="submit">Usuń</button>
    <a href="../index.php"><button type="button">Anuluj</button></a>
</form>
</body>
</html>
