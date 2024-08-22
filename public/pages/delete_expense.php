<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

Auth::checkRole([1]); // Allow Admin role only
Auth::checkSubscription(); // Check if user has an active subscription

$expenseModel = new Expense();
$expenseId = $_GET['id'];
$expenseModel->deleteExpense($expenseId);
header("Location: " . BASE_URL . "?page=expense_summary");
exit;
?>
