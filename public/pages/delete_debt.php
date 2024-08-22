<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/DebtSnowball.php';

$debtSnowballModel = new DebtSnowball();
$userId = $_SESSION['user']['id'];

if (isset($_GET['id'])) {
    $debtId = $_GET['id'];
    $debtSnowballModel->deleteDebt($debtId);
    echo "<script>window.location.href='" . BASE_URL . "?page=debt_snowball_summary';</script>";
    exit;
}
?>
