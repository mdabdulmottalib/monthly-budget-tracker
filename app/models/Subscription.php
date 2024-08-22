<?php

class Subscription {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getActiveSubscription($userId) {
        $stmt = $this->db->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status = 'active' AND end_date >= CURDATE()");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addSubscription($userId, $package, $startDate, $endDate) {
        $stmt = $this->db->prepare("INSERT INTO subscriptions (user_id, package, start_date, end_date, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'active', NOW(), NOW())");
        $stmt->bind_param("isss", $userId, $package, $startDate, $endDate);
        return $stmt->execute();
    }

    public function updateSubscriptionStatus($subscriptionId, $status) {
        $stmt = $this->db->prepare("UPDATE subscriptions SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $subscriptionId);
        return $stmt->execute();
    }
}
?>
