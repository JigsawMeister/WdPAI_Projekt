<?php
session_start();
require_once __DIR__ . '/../classes/Collection.php';
require_once __DIR__ . '/../classes/Recipe.php';

if (!isset($_SESSION['user'])) {
    die("Brak dostępu");
}

$error = "";
$recipesObj = new Recipe();
$allRecipes = $recipesObj->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $selectedRecipes = $_POST['recipes'] ?? [];

    if (!$name) {
        $error = "Nazwa kolekcji jest wymagana";
    }
    else {
        $collection = new Collection();
        $collection->setUserId($_SESSION['user']['id']);
        $collection->setName($name);

        if ($collection->create($selectedRecipes)) {
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Błąd tworzenia kolekcji";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Dodaj kolekcję</title>
<style>
body {
    font-family: Arial, sans-serif;
    max-width: 700px;
    margin: 40px auto;
    background: #f9f9f9;
    padding: 25px;
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
input[type="text"], select {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}
select {
    resize: vertical;
}
button {
    width: 180px;
    padding: 10px;
    margin-top: 15px;
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
#errorMsg {
    text-align: center;
    margin-bottom: 15px;
    color: red;
    font-weight: bold;
}
a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #555;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<h2>Dodaj kolekcję</h2>
<?php if($error) echo "<p id='errorMsg'>$error</p>"; ?>
<form method="POST">
    <label>Nazwa kolekcji: <input type="text" name="name" required></label>

    <label>Dodaj przepisy do kolekcji (możesz zaznaczyć wiele):</label>
    <select name="recipes[]" multiple size="5">
        <?php foreach($allRecipes as $recipe): ?>
            <option value="<?php echo $recipe['id']; ?>"><?php echo htmlspecialchars($recipe['title']); ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Dodaj</button>
</form>
<a href="../index.php">Powrót do strony głównej</a>
</body>
</html>