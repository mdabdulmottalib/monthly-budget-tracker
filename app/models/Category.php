<?php

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getCategoriesByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE user_id = ? AND deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoriesByType($userId, $type) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE (user_id = ? OR user_id IS NULL) AND type = ? AND deleted = 0");
        $stmt->bind_param("is", $userId, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ? AND deleted = 0");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addCategory($name, $type, $userId) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, type, user_id, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssi", $name, $type, $userId);
        return $stmt->execute();
    }

    public function updateCategory($id, $name, $type) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, type = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $name, $type, $id);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("UPDATE categories SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function categoryExists($name, $type, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM categories WHERE name = ? AND type = ? AND user_id = ? AND deleted = 0");
        $stmt->bind_param("ssi", $name, $type, $userId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
?>
