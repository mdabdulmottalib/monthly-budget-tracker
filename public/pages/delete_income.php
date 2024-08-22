<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Income.php';

Auth::checkRole([1]); // Allow Admin role only
Auth::checkSubscription(); // Check if user has an active subscription

$incomeModel = new Income();
$incomeId = $_GET['id'];
$incomeModel->deleteIncome($incomeId);
header("Location: " . BASE_URL . "?page=income_summary");
exit;
?>
