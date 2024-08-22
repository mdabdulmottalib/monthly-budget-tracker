<?php

class Note {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllNotes($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE user_id = ? AND deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNoteById($noteId) {
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE id = ? AND deleted = 0");
        $stmt->bind_param("i", $noteId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addNote($userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO notes (user_id, content, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("is", $userId, $content);
        return $stmt->execute();
    }

    public function updateNote($noteId, $content) {
        $stmt = $this->db->prepare("UPDATE notes SET content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $content, $noteId);
        return $stmt->execute();
    }

    public function deleteNote($noteId) {
        $stmt = $this->db->prepare("UPDATE notes SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $noteId);
        return $stmt->execute();
    }
}
?>
