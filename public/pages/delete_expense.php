<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

Auth::checkRole([1]); // Allow Admin role only
Auth::checkSubscription(); // Check if user has an active subscription

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expenseId = $_POST['id'] ?? null;

    if ($expenseId) {
        $expenseModel = new Expense();
        $deleted = $expenseModel->deleteExpense($expenseId);

        if ($deleted) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete expense']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid expense ID']);
    }
    exit;
}
?>
