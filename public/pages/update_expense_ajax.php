<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expenseModel = new Expense();

    $expenseId = $_POST['id'];
    $budget_amount = $_POST['budget_amount'];
    $actual_amount = $_POST['actual_amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Assuming updateExpense method returns true on success and false on failure
    $success = $expenseModel->updateExpense($expenseId, null, $budget_amount, $actual_amount, $date, $description);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
