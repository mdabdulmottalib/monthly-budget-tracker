<?php

class SubscriptionPackage {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllPackages() {
        $result = $this->db->query("SELECT * FROM subscription_packages");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPackageById($packageId) {
        $stmt = $this->db->prepare("SELECT * FROM subscription_packages WHERE id = ?");
        $stmt->bind_param("i", $packageId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
