<?php
require_once __DIR__ . '/Database.php';

class Recipe {
    private $conn;
    private $table = "recipes";

    private $id;
    private $title;
    private $description;
    private $ingredients;
    private $steps;
    private $user_id;
    private $collections = [];

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
    public function getIngredients() { return $this->ingredients; }
    public function setIngredients($ingredients) { $this->ingredients = $ingredients; }
    public function getSteps() { return $this->steps; }
    public function setSteps($steps) { $this->steps = $steps; }
    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function getCollections() { return $this->collections; }
    public function setCollections(array $collections) { $this->collections = $collections; }
 
    public function create() {
        try {
            $query = "INSERT INTO {$this->table} 
                      (user_id, title, description, ingredients, steps)
                      VALUES (:user_id, :title, :description, :ingredients, :steps)
                      RETURNING id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':ingredients', $this->ingredients);
            $stmt->bindParam(':steps', $this->steps);
            $stmt->execute();
            $this->id = $stmt->fetchColumn();

            $this->updateCollections();

            return true;
        }
        catch (PDOException $e) {
            echo "Błąd tworzenia przepisu: " . $e->getMessage();
            return false;
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($recipe) {
                $recipe['collections'] = $this->getCollectionsIds($recipe['id']);
            }

            return $recipe ?: null;
        }
        catch (PDOException $e) {
            echo "Błąd pobierania przepisu: " . $e->getMessage();
            return null;
        }
    }

    public function update() {
        try {
            $query = "UPDATE {$this->table} SET
                      title = :title,
                      description = :description,
                      ingredients = :ingredients,
                      steps = :steps
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':ingredients', $this->ingredients);
            $stmt->bindParam(':steps', $this->steps);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            $this->updateCollections();

            return true;
        }
        catch (PDOException $e) {
            echo "Błąd aktualizacji przepisu: " . $e->getMessage();
            return false;
        }
    }

    public function delete() {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo "Błąd usuwania przepisu: " . $e->getMessage();
            return false;
        }
    }

    private function updateCollections() {
        if (!$this->id) return;

        $stmt = $this->conn->prepare("DELETE FROM recipe_collections WHERE recipe_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->conn->prepare("INSERT INTO recipe_collections (recipe_id, collection_id) VALUES (:recipe_id, :collection_id)");
        foreach ($this->collections as $collectionId) {
            $stmt->bindParam(':recipe_id', $this->id);
            $stmt->bindParam(':collection_id', $collectionId);
            $stmt->execute();
        }
    }

    private function getCollectionsIds($recipeId) {
        $stmt = $this->conn->prepare("SELECT collection_id FROM recipe_collections WHERE recipe_id = :id");
        $stmt->bindParam(':id', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT r.*, u.username FROM recipes r JOIN users u ON r.user_id = u.id");
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($recipes as &$recipe) {
                $recipe['collections'] = $this->getCollectionsIds($recipe['id']);
            }

            return $recipes;
        }
        catch (PDOException $e) {
            echo "Błąd pobierania przepisów: " . $e->getMessage();
            return [];
        }
    }

    public function addToCollection($collectionId) {
        if (!$this->id || !$collectionId) return false;
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO recipe_collections (recipe_id, collection_id) VALUES (:recipe_id, :collection_id) ON CONFLICT DO NOTHING");
            $stmt->bindParam(':recipe_id', $this->id);
            $stmt->bindParam(':collection_id', $collectionId);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo "Błąd dodawania do kolekcji: " . $e->getMessage();
            return false;
        }
    }

    public function setCollectionsForCollection(int $collectionId, array $recipeIds): bool {
        try {
            $stmt = $this->conn->prepare("DELETE FROM recipe_collections WHERE collection_id = :collection_id");
            $stmt->bindParam(':collection_id', $collectionId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->conn->prepare(
                "INSERT INTO recipe_collections (recipe_id, collection_id) VALUES (:recipe_id, :collection_id)");

            foreach ($recipeIds as $recipeId) {
                $stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
                $stmt->bindParam(':collection_id', $collectionId, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        }
        catch (PDOException $e) {
            echo "Błąd aktualizacji powiązań przepisów w kolekcji: " . $e->getMessage();
            return false;
        }
    }
}