<?php

class Expense {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllExpenses($userId) {
        $stmt = $this->db->prepare("SELECT expenses.*, categories.name as category_name 
                                    FROM expenses 
                                    JOIN categories ON expenses.category_id = categories.id 
                                    WHERE expenses.user_id = ? AND expenses.deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getExpenseById($expenseId) {
        $stmt = $this->db->prepare("SELECT expenses.*, categories.name as category_name 
                                    FROM expenses 
                                    JOIN categories ON expenses.category_id = categories.id 
                                    WHERE expenses.id = ? AND expenses.deleted = 0");
        $stmt->bind_param("i", $expenseId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addExpense($userId, $categoryId, $budgetAmount, $actualAmount, $date, $description) {
        $stmt = $this->db->prepare("INSERT INTO expenses (user_id, category_id, budget_amount, actual_amount, date, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iiddss", $userId, $categoryId, $budgetAmount, $actualAmount, $date, $description);
        return $stmt->execute();
    }

    public function updateExpense($id, $category_id, $budget_amount, $actual_amount, $date, $description) {
        $stmt = $this->db->prepare("UPDATE expenses SET category_id = ?, budget_amount = ?, actual_amount = ?, date = ?, description = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("iddssi", $category_id, $budget_amount, $actual_amount, $date, $description, $id);
        return $stmt->execute();
    }

    public function deleteExpense($id) {
        $stmt = $this->db->prepare("UPDATE expenses SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getTotalExpense($userId) {
        $stmt = $this->db->prepare("SELECT SUM(actual_amount) as total_expense, SUM(budget_amount) as total_budget_expense FROM expenses WHERE user_id = ? AND deleted = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getExpenseData($userId) {
        $stmt = $this->db->prepare("SELECT date, SUM(actual_amount) as amount FROM expenses WHERE user_id = ? AND deleted = 0 GROUP BY date ORDER BY date");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getExpensesByDateRange($userId, $startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT expenses.*, categories.name as category_name 
                                    FROM expenses 
                                    JOIN categories ON expenses.category_id = categories.id 
                                    WHERE expenses.user_id = ? AND expenses.date >= ? AND expenses.date <= ? AND expenses.deleted = 0");
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    


    public function getExpenseDataByMonth($userId, $month) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ? AND deleted = 0");
        $stmt->bind_param("is", $userId, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getExpenseDataByDateRange($userId, $startDate, $endDate) {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE user_id = ? AND date BETWEEN ? AND ? AND deleted = 0");
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllMonths($userId) {
        $stmt = $this->db->prepare("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month FROM expenses WHERE user_id = ? AND deleted = 0 ORDER BY month");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'month');
    }

    public function getExpensesByCategory($userId, $month) {
        $stmt = $this->db->prepare("SELECT categories.name as category_name, SUM(budget_amount) as budget_amount, SUM(actual_amount) as actual_amount
                                    FROM expenses
                                    JOIN categories ON expenses.category_id = categories.id
                                    WHERE expenses.user_id = ? AND DATE_FORMAT(expenses.date, '%Y-%m') = ? AND expenses.deleted = 0
                                    GROUP BY categories.name");
        $stmt->bind_param("is", $userId, $month);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
