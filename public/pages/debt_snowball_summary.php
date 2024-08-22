<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/DebtSnowball.php';

$debtSnowballModel = new DebtSnowball();
$userId = $_SESSION['user']['id'];
$debts = $debtSnowballModel->getAllDebts($userId);

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $debtSnowballModel->deleteDebt($_GET['id']);
    echo "<script>window.location.href='" . BASE_URL . "?page=debt_snowball_summary';</script>";
    exit;
}
?>

<h1 class="text-2xl font-semibold mb-6">Debt Snowball Summary</h1>

<table class="min-w-full bg-white shadow-md rounded my-6">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-3 px-4 uppercase font-semibold text-sm">Debt Name</th>
            <th class="py-3 px-4 uppercase font-semibold text-sm">Balance</th>
            <th class="py-3 px-4 uppercase font-semibold text-sm">Interest Rate</th>
            <th class="py-3 px-4 uppercase font-semibold text-sm">Min Payment</th>
            <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
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
                    <a href="<?php echo BASE_URL; ?>?page=edit_debt&id=<?php echo $debt['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                    <a href="<?php echo BASE_URL; ?>?page=debt_snowball_summary&action=delete&id=<?php echo $debt['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this debt?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="<?php echo BASE_URL; ?>?page=add_debt" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Add New Debt</a>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
