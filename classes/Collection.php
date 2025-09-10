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

    public function setId($id) {
        $this->id = $id;
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

    public function delete() {
        if (!$this->id)
            return false;

        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo "Błąd usuwania kolekcji: " . $e->getMessage();
            return false;
        }
    }


    public function getByUserId(int $userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Błąd pobierania kolekcji: " . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        catch (PDOException $e) {
            echo "Błąd pobierania kolekcji: " . $e->getMessage();
            return null;
        }
    }

    public function getAll() {
        try {
            $stmt = $this->conn->query("SELECT * FROM {$this->table} ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Błąd pobierania kolekcji: " . $e->getMessage();
            return [];
        }
    }

    public function getRecipes() {
        if (!$this->id) return [];

        try {
            $stmt = $this->conn->prepare("
            SELECT r.id, r.title 
            FROM recipes r
            JOIN recipe_collections rc ON r.id = rc.recipe_id
            WHERE rc.collection_id = :col_id
            ");
            $stmt->bindParam(':col_id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Błąd pobierania przepisów w kolekcji: " . $e->getMessage();
            return [];
        }
    }
}