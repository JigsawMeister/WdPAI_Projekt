<?php
session_start();
require_once __DIR__ . '/classes/User.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user'] = $loggedInUser;
        header("Location: index.php");
        exit;
    }
    else {
        $message = "Błąd logowania: niepoprawny email lub hasło.";
    }
}
?>

<DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
</head>
<body>
    <h2>Logowanie</h2>
    <form method="POST">
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Hasło: <input type="password" name="password" required></label><br>
        <button type="submit">Zaloguj</button>
    </form>
    <p><?php echo $message; ?></p>
    <a href="register.php">Nie masz konta? Zarejestruj się</a>
</body>
</html>