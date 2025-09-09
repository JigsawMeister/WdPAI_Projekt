<?php
require_once __DIR__ . '/Database.php';

class Comment {
    private $conn;
    private $table = "comments";
    private $id;
    private $recipe_id;
    private $user_id;
    private $content;
    private $status

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function getRecipeId() {
        return $this->recipe_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setRecipeId($recipe_id) {
        $this->recipe_id = $recipe_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}