<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $expenseModel = new Expense();

    $expenseId = $_GET['id'];

    // Execute the delete
    $success = $expenseModel->deleteExpense($expenseId);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database delete failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

?>
