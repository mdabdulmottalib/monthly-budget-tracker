<?php

class DebtSnowball {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addDebt($userId, $debtName, $balance, $interestRate, $minPayment, $startingDate, $extraPaymentBeginning, $extraPaymentMonthly) {
        $stmt = $this->db->prepare("INSERT INTO debt_snowball (user_id, debt_name, balance, interest_rate, min_payment, starting_date, extra_payment_beginning, extra_payment_monthly, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("isdddsdd", $userId, $debtName, $balance, $interestRate, $minPayment, $startingDate, $extraPaymentBeginning, $extraPaymentMonthly);
        return $stmt->execute();
    }

    public function getAllDebts($userId) {
        $stmt = $this->db->prepare("SELECT * FROM debt_snowball WHERE user_id = ? AND deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDebtById($id) {
        $stmt = $this->db->prepare("SELECT * FROM debt_snowball WHERE id = ? AND deleted = 0");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateDebt($id, $userId, $debtName, $balance, $interestRate, $minPayment, $startingDate, $extraPaymentBeginning, $extraPaymentMonthly) {
        $stmt = $this->db->prepare("UPDATE debt_snowball SET debt_name = ?, balance = ?, interest_rate = ?, min_payment = ?, starting_date = ?, extra_payment_beginning = ?, extra_payment_monthly = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sdddsddii", $debtName, $balance, $interestRate, $minPayment, $startingDate, $extraPaymentBeginning, $extraPaymentMonthly, $id, $userId);
        return $stmt->execute();
    }

    public function deleteDebt($id) {
        $stmt = $this->db->prepare("UPDATE debt_snowball SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
