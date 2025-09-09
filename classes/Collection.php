<?php
require_once __DIR__ . '/Database.php';

class Collection {
    private $conn;
    private $table = "collections";
    private $id;
    private $name;
    private $user_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function create() {
        $query = "INSERT INTO {$this->table} (name, user_id) VALUES (:name, :user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }

    public function readAllByUser($user_id) {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name) {
        $query = "UPDATE {$this->table} SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}