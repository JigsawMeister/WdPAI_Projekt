<?php
session_start();
require_once __DIR__ . '/classes/Recipe.php';
require_once __DIR__ . '/classes/Collection.php';
require_once __DIR__ . '/classes/User.php';

$recipeObj = new Recipe();
$collectionObj = new Collection();
$userObj = new User();

$recipes = $recipeObj->getAll();

$collections = [];
if (isset($_SESSION['user'])) {
    $collections = $collectionObj->getByUserId($_SESSION['user']['id']);
}

function getUsernameById($userId) {
    $userObj = new User();
    $user = $userObj->getById($userId);
    return $user ? $user['username'] : 'Nieznany';
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Strona główna</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}
header {
    background-color: #4CAF50;
    color: white;
    padding: 20px;
    text-align: center;
}
.container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 0 20px;
}
h2 {
    margin-top: 40px;
    border-bottom: 2px solid #4CAF50;
    padding-bottom: 5px;
}
.card {
    background-color: white;
    padding: 15px 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.card ul { margin: 10px 0 0 20px; }
.card a {
    margin-right: 10px;
    color: #4CAF50;
    text-decoration: none;
}
.card a:hover { text-decoration: underline; }
.actions {
    margin-bottom: 20px;
}
.actions a {
    margin-right: 15px;
    color: white;
    background-color: #4CAF50;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
}
.actions a:hover {
    background-color: #45a049;
}
</style>
</head>
<body>

<header>
    <h1>Świat Smaku</h1>
</header>

<div class="container">

<?php if(isset($_SESSION['user'])): ?>
    <p>Zalogowany jako: <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong> 
       (<a href="backend/logout.php" style="color:red;">Wyloguj</a>)</p>
    <div class="actions">
        <a href="public/add_recipe.html">Dodaj przepis</a>
        <a href="public/add_collection.php">Utwórz kolekcję</a>
    </div>
<?php else: ?>
    <div class="actions">
        <a href="public/login.php">Zaloguj się</a>
        <a href="public/register.php">Zarejestruj</a>
    </div>
<?php endif; ?>

<h2>Wszystkie przepisy</h2>
<?php foreach($recipes as $r): ?>
<div class="card">
    <strong><?php echo htmlspecialchars($r['title']); ?></strong> 
    <em>autor: <?php echo htmlspecialchars(getUsernameById($r['user_id'])); ?></em>
    <p><?php echo nl2br(htmlspecialchars($r['description'])); ?></p>
    <p><strong>Składniki:</strong> <?php echo nl2br(htmlspecialchars($r['ingredients'])); ?></p>
    <p><strong>Przepis:</strong> <?php echo nl2br(htmlspecialchars($r['steps'])); ?></p>
    <?php if(isset($_SESSION['user']) && ($_SESSION['user']['id'] == $r['user_id'] || $_SESSION['user']['role'] === 'admin')): ?>
        <a href="public/edit_recipe.php?id=<?php echo $r['id']; ?>">Edytuj</a>
        <a href="public/delete_recipe.php?id=<?php echo $r['id']; ?>">Usuń</a>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<h2>Twoje kolekcje</h2>
<?php foreach($collections as $c): ?>
<div class="card">
    <strong><?php echo htmlspecialchars($c['name']); ?></strong>
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['id'] == $c['user_id']): ?>
        <a href="public/edit_collection.php?id=<?php echo $c['id']; ?>">Edytuj</a>
        <a href="public/delete_collection.php?id=<?php echo $c['id']; ?>">Usuń</a>
    <?php endif; ?>
    <ul>
        <?php
        $collectionObj->setId($c['id']);
        $recipesInCollection = $collectionObj->getRecipes();
        foreach($recipesInCollection as $rc):
        ?>
            <li><?php echo htmlspecialchars($rc['title']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endforeach; ?>
</div>
</body>
</html>