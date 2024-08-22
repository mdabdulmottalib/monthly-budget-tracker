<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/DebtSnowball.php';

$debts = $_POST['debts'];
$debtSnowballModel = new DebtSnowball();

foreach ($debts as $debt) {
    if (empty($debt['id'])) {
        $debtSnowballModel->addDebt($debt['user_id'], $debt['debt_name'], $debt['balance'], $debt['interest_rate'], $debt['min_payment']);
    } else {
        $debtSnowballModel->updateDebt($debt['id'], $debt['debt_name'], $debt['balance'], $debt['interest_rate'], $debt['min_payment']);
    }
}
?>
