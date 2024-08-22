<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

$expenseModel = new Expense();

$expenseId = $_GET['id'];

$success = $expenseModel->deleteExpense($expenseId);

header('Content-Type: application/json');
echo json_encode(['success' => $success]);
?>
