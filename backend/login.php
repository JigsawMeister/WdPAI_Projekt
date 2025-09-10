<?php
session_start();
require_once __DIR__ . '/../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user'] = $loggedInUser;
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['message'] = "Błąd logowania: niepoprawny email lub hasło.";
        header("Location: /public/login.php");
        exit;
    }
}