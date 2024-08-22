<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/DebtSnowball.php';

$debtSnowballModel = new DebtSnowball();
$userId = $_SESSION['user']['id'];
$debts = $debtSnowballModel->getAllDebts($userId);
?>

<h1 class="text-2xl font-semibold mb-6">Debt Snowball Calculator</h1>

<form method="POST" action="<?php echo BASE_URL; ?>?page=calculate_debt_snowball">
    <table class="min-w-full bg-white shadow-md rounded my-6">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Debt Name</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Balance</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Interest Rate</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Min Payment</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Extra Payment</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($debts as $debt): ?>
                <tr>
                    <td class="py-3 px-4"><?php echo $debt['debt_name']; ?></td>
                    <td class="py-3 px-4"><?php echo $debt['balance']; ?></td>
                    <td class="py-3 px-4"><?php echo $debt['interest_rate']; ?>%</td>
                    <td class="py-3 px-4"><?php echo $debt['min_payment']; ?></td>
                    <td class="py-3 px-4">
                        <input type="number" name="extra_payment[<?php echo $debt['id']; ?>]" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Calculate</button>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
