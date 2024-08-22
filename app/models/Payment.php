<?php

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addPayment($userId, $subscriptionId, $amount, $paymentMethod, $status) {
        $stmt = $this->db->prepare("INSERT INTO payments (user_id, subscription_id, amount, payment_date, payment_method, status, created_at, updated_at) VALUES (?, ?, ?, CURDATE(), ?, ?, NOW(), NOW())");
        $stmt->bind_param("iisss", $userId, $subscriptionId, $amount, $paymentMethod, $status);
        return $stmt->execute();
    }

    public function getPaymentsByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
