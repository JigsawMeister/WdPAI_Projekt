<?php
session_start();
?>

<DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Strona główna</title>
</head>
<body>
    <h1>Witaj na stronie z przepisami</h1>

    <?php if(isset($_SESSION['user'])): ?>
        <p>Zalogowany jako <b><?php echo $_SESSION['user']['username']; ?></b> (rola: <?php echo $_SESSION['user']['role']; ?>)</p>
        <a href="logout.php">Wyloguj</a>
    <?php else: ?>
        <p><a href="login.php">Zaloguj się</a> | <a href="register.php">Zarejestruj się</a></p>
    <?php endif; ?>
</body>
</html>