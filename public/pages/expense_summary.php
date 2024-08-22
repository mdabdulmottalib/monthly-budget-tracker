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

<div class="mt-10">
    <?php if (empty($expenses)): ?>
        <div class="col-span-1 md:col-span-3 text-center text-gray-500">It seems no data found.</div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($groupedExpenses as $category => $expenses): ?>
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Date</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Expense Name</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Budget</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Actual</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-gray-100 border-t border-gray-100">
                    <?php
                    $categoryTotalBudget = 0;
                    $categoryTotalActual = 0;
                    $rowCount = 0;
                    ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars(date('j M', strtotime($expense['date']))); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($expense['description'] ?? ''); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($expense['budget_amount'] ?? ''); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($expense['actual_amount'] ?? ''); ?></td>
                            <td class="px-6 py-4">
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">&nbsp;</td>
                            <td class="px-6 py-4">&nbsp;</td>
                            <td class="px-6 py-4">&nbsp;</td>
                            <td class="px-6 py-4">&nbsp;</td>
                            <td class="px-6 py-4">&nbsp;</td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr class="font-semibold">
                        <td class="px-6 py-4" colspan="2">Total</td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars(number_format($categoryTotalBudget, 2)); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars(number_format($categoryTotalActual, 2)); ?></td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tfoot>
            </table>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>





<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
