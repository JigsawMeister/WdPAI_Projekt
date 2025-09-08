<?php
$host = "db";
$db = "db";
$user = "docker";
$pass = "docker";
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączenie z bazą działa!";
}
catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}