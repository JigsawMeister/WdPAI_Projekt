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
    private $collection_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function getSteps() {
        return $this->steps;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getCollectionId() {
        return $this->collection_id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setIngredients($ingredients) {
        $this->ingredients = $ingredients;
    }

    public function setSteps($steps) {
        $this->steps = $steps;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setCollectionId($collection_id) {
        $this->collection_id = $collection_id;
    }

    public function create() {

    }

    public function read() {

    }

    public function update() {

    }

    public function delete() {

    }
}