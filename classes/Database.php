<?php
class Database {
    private $host = "db";
    private $db_name = "db";
    private $username = "docker";
    private $password = "docker";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo "Błąd połączenia: " . $e->getMessage();
        }

        return $this->conn;
    }
}