<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expenseModel = new Expense();

    $expenseId = $_POST['id'];
    $category_id = null;  // Adjust if needed
    $budget_amount = $_POST['budget_amount'];
    $actual_amount = $_POST['actual_amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Execute the update
    $success = $expenseModel->updateExpense($expenseId, $category_id, $budget_amount, $actual_amount, $date, $description);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

?>
