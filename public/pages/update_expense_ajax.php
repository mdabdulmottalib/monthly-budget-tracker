<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

$expenseModel = new Expense();

$expenseId = $_POST['id'];
$budget_amount = $_POST['budget_amount'];
$actual_amount = $_POST['actual_amount'];
$date = $_POST['date'];
$description = $_POST['description'];

$success = $expenseModel->updateExpense($expenseId, null, $budget_amount, $actual_amount, $date, $description);

header('Content-Type: application/json');
echo json_encode(['success' => $success]);
?>
