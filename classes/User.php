<?php
require_once __DIR__ . '/Database.php';

class User {
    private $conn;
    private $table = "users";
    private $id;
    private $username;
    private $email;
    private $passwordHash;
    private $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function getRole() {
        return $this->role;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function register() {
        try {
            $query = "SELECT id FROM {$this->table} WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return false;
            }

            $query = "INSERT INTO {$this->table} (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password_hash', $this->passwordHash);
            $stmt->bindParam(':role', $this->role);

            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo "Błąd rejestracji: " . $e->getMessage();
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
            }
            else {
                return false;
            }
        }
        catch (PDOException $e) {
            echo "Błąd logowania: " . $e->getMessage();
            return false;
        }
    }
}