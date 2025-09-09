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

    public function register($username, $email, $password) {
        
    }

    public function login($email, $password) {

    }
}