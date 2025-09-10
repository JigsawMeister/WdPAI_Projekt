<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

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
        $_SESSION['message'] = "Rejestracja zakończona sukcesem!";
        header("Location: /public/login.php");
        exit;
    } else {
        $_SESSION['message'] = "Błąd: użytkownik o takim email już istnieje.";
        header("Location: /public/register.php");
        exit;
    }
}