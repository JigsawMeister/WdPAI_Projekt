<?php
session_start();
require_once __DIR__ . '/../classes/Recipe.php';

if (!isset($_SESSION['user'])) {
    die("Brak dostępu");
}

$recipeObj = new Recipe();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $steps = trim($_POST['steps'] ?? '');
    $collections = $_POST['collections'] ?? '';

    if (!$id || !$title || !$description || !$ingredients || !$steps) {
        $error = "Wszystkie pola są wymagane";
    } else {
        $recipe = $recipeObj->getById($id);
        if (!$recipe || ($_SESSION['user']['id'] != $recipe['user_id'] && $_SESSION['user']['role'] !== 'admin')) {
            $error = "Brak uprawnień";
        } else {
            $recipeObj->setId($id);
            $recipeObj->setTitle($title);
            $recipeObj->setDescription($description);
            $recipeObj->setIngredients($ingredients);
            $recipeObj->setSteps($steps);
            if (is_string($collections)) {
                $collections = array_filter(array_map('intval', explode(',', $collections)));
            }
            $recipeObj->setCollections($collections);

            if ($recipeObj->update()) {
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Błąd aktualizacji przepisu";
            }
        }
    }
}


$recipeId = $_GET['id'] ?? null;
if (!$recipeId || !is_numeric($recipeId)) die("Brak ID przepisu");

$recipe = $recipeObj->getById($recipeId);
if (!$recipe) die("Przepis nie istnieje");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Edytuj przepis</title>
<style>
body {
    font-family: Arial, sans-serif;
    max-width: 700px;
    margin: 40px auto;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #333;
}
form {
    display: flex;
    flex-direction: column;
}
label {
    margin-bottom: 15px;
    font-weight: bold;
}
input[type="text"], textarea {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    resize: vertical;
}
button {
    width: 150px;
    padding: 10px;
    margin-top: 10px;
    align-self: center;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    background-color: #45a049;
}
#msg {
    text-align: center;
    margin-top: 15px;
    color: red;
    font-weight: bold;
}
</style>
</head>
<body>
<h2>Edytuj przepis</h2>
<?php if($error) echo "<p id='msg'>$error</p>"; ?>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($recipe['id']); ?>">
    <label>Nazwa: <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required></label>
    <label>Opis: <textarea name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea></label>
    <label>Składniki: <textarea name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea></label>
    <label>Przepis: <textarea name="steps" required><?php echo htmlspecialchars($recipe['steps']); ?></textarea></label>
    <label>Kolekcje (ID, przecinki): <input type="text" name="collections" value="<?php echo htmlspecialchars(implode(',', $recipe['collections'])); ?>"></label>
    <button type="submit">Zapisz</button>
</form>
<a href="../index.php" style="display:block; text-align:center; margin-top:20px; color:#555; text-decoration:none;">Powrót do strony głównej</a>
</body>
</html>
