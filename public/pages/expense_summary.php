<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$expenseModel = new Expense();
$userId = $_SESSION['user']['id'];

// Get current month start and end dates
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');

// Get filter values from GET parameters
$startDate = $_GET['start_date'] ?? $currentMonthStart;
$endDate = $_GET['end_date'] ?? $currentMonthEnd;

// Get all expenses within the date range
$expenses = $expenseModel->getExpensesByDateRange($userId, $startDate, $endDate);

// Group expenses by category
$groupedExpenses = [];
foreach ($expenses as $expense) {
    $groupedExpenses[$expense['category_name']][] = $expense;
}

// Calculate previous and next month values
$prevMonthStart = date('Y-m-01', strtotime('-1 month', strtotime($currentMonthStart)));
$prevMonthEnd = date('Y-m-t', strtotime('-1 month', strtotime($currentMonthEnd)));
$nextMonthStart = date('Y-m-01', strtotime('+1 month', strtotime($currentMonthStart)));
$nextMonthEnd = date('Y-m-t', strtotime('+1 month', strtotime($currentMonthEnd)));

// Calculate total expenses for all categories
$totalBudget = 0;
$totalActual = 0;
foreach ($expenses as $expense) {
    $totalBudget += $expense['budget_amount'];
    $totalActual += $expense['actual_amount'];
}
?>

<h1 class="text-2xl font-semibold mb-6">Expense Summary</h1>

<div class="mb-6">
    <div class="font-semibold">Total Expense Budget: <?php echo htmlspecialchars(number_format($totalBudget, 2)); ?></div>
    <div class="font-semibold">Total Expenses: <?php echo htmlspecialchars(number_format($totalActual, 2)); ?></div>
</div>

<!-- Filter Form -->
<form method="get" action="" class="mb-6">
    <input type="hidden" name="page" value="expense_summary">
    <label for="start_date" class="mr-2">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" class="mr-4 p-2 border rounded">
    <label for="end_date" class="mr-2">End Date:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" class="mr-4 p-2 border rounded">
    <button type="submit" class="p-2 bg-blue-500 text-white rounded">Filter</button>
    <a href="?page=expense_summary&start_date=<?php echo $prevMonthStart; ?>&end_date=<?php echo $prevMonthEnd; ?>" class="p-2 bg-gray-500 text-white rounded ml-2">Previous Month</a>
    <a href="?page=expense_summary&start_date=<?php echo $nextMonthStart; ?>&end_date=<?php echo $nextMonthEnd; ?>" class="p-2 bg-gray-500 text-white rounded ml-2">Next Month</a>
</form>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php if (empty($expenses)): ?>
        <div class="col-span-1 md:col-span-3 text-center text-gray-500">It seems no data found.</div>
    <?php else: ?>
        <?php foreach ($groupedExpenses as $category => $expenses): ?>
        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-xl font-semibold mb-4"><?php echo htmlspecialchars($category ?? ''); ?></h2>
            <div class="overflow-y-auto" style="max-height: 400px;">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-800 text-white sticky top-0">
                        <tr>
                            <th class="py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Date</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Expense Name</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Budget</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Actual</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php
                        $categoryTotalBudget = 0;
                        $categoryTotalActual = 0;
                        $rowCount = 0;
                        ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td class="py-3 px-4 border border-gray-300 whitespace-nowrap"><?php echo htmlspecialchars(date('j M', strtotime($expense['date']))); ?></td>
                                <td class="py-3 px-4 border border-gray-300 whitespace-nowrap"><?php echo htmlspecialchars($expense['description'] ?? ''); ?></td>
                                <td class="py-3 px-4 border border-gray-300"><?php echo htmlspecialchars($expense['budget_amount'] ?? ''); ?></td>
                                <td class="py-3 px-4 border border-gray-300"><?php echo htmlspecialchars($expense['actual_amount'] ?? ''); ?></td>
                                <td class="py-3 px-4 border border-gray-300">
                                    <a href="?page=edit_expense&id=<?php echo $expense['id']; ?>" class="text-blue-500 hover:underline"><i class="fas fa-edit"></i></a>
                                    <a href="?page=delete_expense&id=<?php echo $expense['id']; ?>" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                            $categoryTotalBudget += $expense['budget_amount'];
                            $categoryTotalActual += $expense['actual_amount'];
                            $rowCount++;
                            ?>
                        <?php endforeach; ?>
                        <?php for ($i = $rowCount; $i < 10; $i++): ?>
                            <tr>
                                <td class="py-3 px-4 border border-gray-300">&nbsp;</td>
                                <td class="py-3 px-4 border border-gray-300">&nbsp;</td>
                                <td class="py-3 px-4 border border-gray-300">&nbsp;</td>
                                <td class="py-3 px-4 border border-gray-300">&nbsp;</td>
                                <td class="py-3 px-4 border border-gray-300">&nbsp;</td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                    <tfoot class="bg-gray-800 text-white sticky bottom-0">
                        <tr class="font-semibold">
                            <td class="py-3 px-4 border border-gray-300" colspan="2">Total</td>
                            <td class="py-3 px-4 border border-gray-300"><?php echo htmlspecialchars(number_format($categoryTotalBudget, 2)); ?></td>
                            <td class="py-3 px-4 border border-gray-300"><?php echo htmlspecialchars(number_format($categoryTotalActual, 2)); ?></td>
                            <td class="py-3 px-4 border border-gray-300"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
