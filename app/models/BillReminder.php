<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Database.php';

class BillReminder {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getReminders($userId, $startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT * FROM bill_reminders WHERE user_id = ? AND date BETWEEN ? AND ?");
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addReminder($userId, $date, $description) {
        $stmt = $this->db->prepare("INSERT INTO bill_reminders (user_id, date, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $date, $description);
        return $stmt->execute();
    }

    public function updateReminder($id, $isPaid) {
        $stmt = $this->db->prepare("UPDATE bill_reminders SET is_paid = ? WHERE id = ?");
        $stmt->bind_param("ii", $isPaid, $id);
        return $stmt->execute();
    }
}
?>
