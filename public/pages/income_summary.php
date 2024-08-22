<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Income.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$incomeModel = new Income();
$userId = $_SESSION['user']['id'];

// Get all distinct months from incomes
$allMonths = $incomeModel->getAllMonths($userId);

// Get selected month from request, default to current month
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

$incomes = $incomeModel->getAllIncomes($userId, $selectedMonth);

// Calculate totals
$totalBudgetAmount = 0;
$totalActualAmount = 0;

foreach ($incomes as $income) {
    $totalBudgetAmount += $income['budget_amount'];
    $totalActualAmount += $income['actual_amount'];
}
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-4">Income Summary</h1>
    <!-- Month Selector -->
    <div class="mb-4">
        <label for="monthSelect" class="font-bold">Select Month:</label>
        <select id="monthSelect" class="ml-2 p-2 border rounded">
            <option value="all" <?php echo $selectedMonth === 'all' ? 'selected' : ''; ?>>All</option>
            <?php foreach ($allMonths as $month): ?>
                <option value="<?php echo $month; ?>" <?php echo $month === $selectedMonth ? 'selected' : ''; ?>>
                    <?php echo date('F Y', strtotime($month)); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <table class="min-w-full bg-white shadow-md rounded my-6">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">#</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Category</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Budget Amount</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Actual Amount</th>
                <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Date</th>
                <th class="w-3/12 py-3 px-4 uppercase font-semibold text-sm">Description</th>
                <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
            </tr>
        </thead>
        <tbody id="incomeTableBody" class="text-gray-700">
            <?php $serial = 1; ?>
            <?php foreach ($incomes as $income): ?>
                <tr>
                    <td class="py-3 px-4"><?php echo $serial++; ?></td>
                    <td class="py-3 px-4"><?php echo $income['category_name']; ?></td>
                    <td class="py-3 px-4"><?php echo $income['budget_amount']; ?></td>
                    <td class="py-3 px-4"><?php echo $income['actual_amount']; ?></td>
                    <td class="py-3 px-4"><?php echo $income['date']; ?></td>
                    <td class="py-3 px-4"><?php echo $income['description']; ?></td>
                    <td class="py-3 px-4">
                        <a href="<?php echo BASE_URL; ?>?page=edit_income&id=<?php echo $income['id']; ?>" class="text-blue-500 hover:underline"><i class="fas fa-edit"></i></a>
                        <a href="<?php echo BASE_URL; ?>?page=delete_income&id=<?php echo $income['id']; ?>" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-gray-200 text-gray-700">
            <tr>
                <td colspan="2" class="py-3 px-4 text-right font-semibold">Total</td>
                <td id="totalBudgetAmount" class="py-3 px-4"><?php echo $totalBudgetAmount; ?></td>
                <td id="totalActualAmount" class="py-3 px-4"><?php echo $totalActualAmount; ?></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
document.getElementById('monthSelect').addEventListener('change', function() {
    const selectedMonth = this.value;
    fetch(`fetch_incomes.php?month=${selectedMonth}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('incomeTableBody');
            tbody.innerHTML = '';
            let totalBudget = 0;
            let totalActual = 0;
            data.incomes.forEach((income, index) => {
                totalBudget += parseFloat(income.budget_amount);
                totalActual += parseFloat(income.actual_amount);
                tbody.innerHTML += `
                    <tr>
                        <td class="py-3 px-4">${index + 1}</td>
                        <td class="py-3 px-4">${income.category_name}</td>
                        <td class="py-3 px-4">${income.budget_amount}</td>
                        <td class="py-3 px-4">${income.actual_amount}</td>
                        <td class="py-3 px-4">${income.date}</td>
                        <td class="py-3 px-4">${income.description}</td>
                        <td class="py-3 px-4">
                            <a href="${BASE_URL}?page=edit_income&id=${income.id}" class="text-blue-500 hover:underline"><i class="fas fa-edit"></i></a>
                            <a href="${BASE_URL}?page=delete_income&id=${income.id}" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('totalBudgetAmount').innerText = totalBudget;
            document.getElementById('totalActualAmount').innerText = totalActual;
        });
});
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
