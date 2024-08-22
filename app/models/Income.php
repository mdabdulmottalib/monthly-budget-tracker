<?php

class Income {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllIncomes($userId) {
        $stmt = $this->db->prepare("SELECT incomes.*, categories.name as category_name 
                                    FROM incomes 
                                    JOIN categories ON incomes.category_id = categories.id 
                                    WHERE incomes.user_id = ? AND incomes.deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIncomeById($incomeId) {
        $stmt = $this->db->prepare("SELECT incomes.*, categories.name as category_name 
                                    FROM incomes 
                                    JOIN categories ON incomes.category_id = categories.id 
                                    WHERE incomes.id = ? AND incomes.deleted = 0");
        $stmt->bind_param("i", $incomeId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addIncome($userId, $categoryId, $budgetAmount, $actualAmount, $date, $description) {
        $stmt = $this->db->prepare("INSERT INTO incomes (user_id, category_id, budget_amount, actual_amount, date, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iiddss", $userId, $categoryId, $budgetAmount, $actualAmount, $date, $description);
        return $stmt->execute();
    }

    public function updateIncome($id, $category_id, $budget_amount, $actual_amount, $date, $description) {
        $stmt = $this->db->prepare("UPDATE incomes SET category_id = ?, budget_amount = ?, actual_amount = ?, date = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("iddssi", $category_id, $budget_amount, $actual_amount, $date, $description, $id);
        return $stmt->execute();
    }

    public function deleteIncome($id) {
        $stmt = $this->db->prepare("UPDATE incomes SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getTotalIncome($userId) {
        $stmt = $this->db->prepare("SELECT SUM(actual_amount) as total_income, SUM(budget_amount) as total_budget_income FROM incomes WHERE user_id = ? AND deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getIncomeData($userId) {
        $stmt = $this->db->prepare("SELECT date, SUM(actual_amount) as amount FROM incomes WHERE user_id = ? AND deleted = 0 GROUP BY date ORDER BY date");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    


    public function getIncomeDataByMonth($userId, $month) {
        $stmt = $this->db->prepare("SELECT * FROM incomes WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ? AND deleted = 0");
        $stmt->bind_param("is", $userId, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIncomeDataByDateRange($userId, $startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT * FROM incomes WHERE user_id = ? AND date BETWEEN ? AND ? AND deleted = 0");
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllMonths($userId) {
        $stmt = $this->db->prepare("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month FROM incomes WHERE user_id = ? AND deleted = 0 ORDER BY month");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'month');
    }
}
?>