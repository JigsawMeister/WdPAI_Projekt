<?php
session_start();
require_once __DIR__ . '/classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'user';

    $user = new User();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setRole($role);

    if ($user->register()) {
        $message = "Rejestracja zakończona sukcesem!";
    }
    else {
        $message = "Błąd: użytkownik o takim email już istnieje.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
</head>
<body>
    <h2>Rejestracja</h2>
    <form method="POST">
        <label>Login: <input type="text" name="username" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Hasło: <input type="password" name="password" required></label><br>
        <button type="submit">Zarejestruj</button>
    </form>
    <p><?php echo $message; ?></p>
    <a href="login.php">Masz konto? Zaloguj się</a>
</body>
</html>