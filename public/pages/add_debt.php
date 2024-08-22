<?php
ob_start(); // Start output buffering
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/DebtSnowball.php';

$debtSnowballModel = new DebtSnowball();
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $debtName = $_POST['debt_name'] ?? 'N/A';
    $balance = $_POST['balance'] ?? 0;
    $interestRate = $_POST['interest_rate'] ?? 0;
    $minPayment = $_POST['min_payment'] ?? 0;
    $startingDate = $_POST['starting_date'] ?? date('Y-m-d');
    $extraPaymentBeginning = $_POST['extra_payment_beginning'] ?? 0;
    $extraPaymentMonthly = $_POST['extra_payment_monthly'] ?? 0;

    $debtSnowballModel->addDebt($userId, $debtName, $balance, $interestRate, $minPayment, $startingDate, $extraPaymentBeginning, $extraPaymentMonthly);
    header("Location: " . BASE_URL . "?page=debt_snowball_summary");
    exit;
}
?>

<h1 class="text-2xl font-semibold mb-6">Add Debt</h1>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="debt_name" class="block text-gray-700 text-sm font-bold mb-2">Debt Name:</label>
    <input type="text" name="debt_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="balance" class="block text-gray-700 text-sm font-bold mb-2">Balance:</label>
    <input type="number" name="balance" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="interest_rate" class="block text-gray-700 text-sm font-bold mb-2">Interest Rate (%):</label>
    <input type="number" name="interest_rate" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="min_payment" class="block text-gray-700 text-sm font-bold mb-2">Minimum Payment:</label>
    <input type="number" name="min_payment" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="starting_date" class="block text-gray-700 text-sm font-bold mb-2">Starting Date:</label>
    <input type="date" name="starting_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="extra_payment_beginning" class="block text-gray-700 text-sm font-bold mb-2">Extra Payment in the Beginning:</label>
    <input type="number" name="extra_payment_beginning" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

    <label for="extra_payment_monthly" class="block text-gray-700 text-sm font-bold mb-2">Extra Payment Monthly:</label>
    <input type="number" name="extra_payment_monthly" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Add Debt</button>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
