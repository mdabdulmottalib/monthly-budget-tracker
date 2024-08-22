<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Income.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

Auth::checkRole([1, 2, 3]);

$incomeModel = new Income();
$expenseModel = new Expense();
$userId = $_SESSION['user']['id'];

$currentMonth = date('Y-m');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : $currentMonth;

try {
    if ($selectedMonth === 'all') {
        $incomeData = $incomeModel->getAllIncomes($userId);
        $expenseData = $expenseModel->getAllExpenses($userId);
    } else {
        $incomeData = $incomeModel->getIncomeDataByMonth($userId, $selectedMonth);
        $expenseData = $expenseModel->getExpenseDataByMonth($userId, $selectedMonth);
    }

    $totalIncomeData = $incomeModel->getTotalIncome($userId);
    $totalExpenseData = $expenseModel->getTotalExpense($userId);

    $totalIncome = $totalIncomeData['total_income'];
    $totalBudgetIncome = $totalIncomeData['total_budget_income'];
    $totalExpenses = $totalExpenseData['total_expense'];
    $totalBudgetExpenses = $totalExpenseData['total_budget_expense'];
    $leftToSpend = $totalIncome - $totalExpenses;
    $leftToBudget = $totalBudgetIncome - $totalBudgetExpenses;

    $expenseCategoryData = $expenseModel->getExpensesByCategory($userId, $selectedMonth);

    $labels = array_unique(array_merge(array_column($incomeData, 'date'), array_column($expenseData, 'date')));
    sort($labels);

    $incomeAmounts = [];
    $expenseAmounts = [];
    $categoryNames = [];
    $categoryBudgetAmounts = [];
    $categoryActualAmounts = [];

    foreach ($labels as $label) {
        $incomeAmounts[] = array_sum(array_column(array_filter($incomeData, function($data) use ($label) { return $data['date'] === $label; }), 'actual_amount'));
        $expenseAmounts[] = array_sum(array_column(array_filter($expenseData, function($data) use ($label) { return $data['date'] === $label; }), 'actual_amount'));
    }

    foreach ($expenseCategoryData as $category) {
        $categoryNames[] = $category['category_name'];
        $categoryBudgetAmounts[] = $category['budget_amount'];
        $categoryActualAmounts[] = $category['actual_amount'];
    }

    $allIncomeMonths = $incomeModel->getAllMonths($userId);
    $allExpenseMonths = $expenseModel->getAllMonths($userId);

    $allMonths = array_unique(array_merge($allIncomeMonths, $allExpenseMonths));
    sort($allMonths);

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
?>

<?php include 'aside.php'; ?>

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 p-5 md:pl-24 gap-5">
    <div class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative">
        <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold">$<?php echo number_format($totalIncome, 2); ?></h2>
            <h2 class="font-semibold text-lg">Total Income</h2>
            <div class="h-1 w-full bg-blue-400"></div>
        </div>
    </div>

    <div class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative">
        <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold">$<?php echo number_format($totalExpenses, 2); ?></h2>
            <h2 class="font-semibold text-lg">Total Expenses</h2>
            <div class="h-1 w-full bg-blue-400"></div>
        </div>
    </div>

    <div class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative">
        <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold"><?php echo $leftToSpend < 0 ? '-$' . number_format(abs($leftToSpend), 2) : '$' . number_format($leftToSpend, 2); ?></h2>
            <h2 class="font-semibold text-lg">Left to Spend</h2>
            <div class="h-1 w-full bg-blue-400"></div>
        </div>
    </div>

    <div class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative">
        <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold"><?php echo $leftToBudget < 0 ? '-$' . number_format(abs($leftToBudget), 2) : '$' . number_format($leftToBudget, 2); ?></h2>
            <h2 class="font-semibold text-lg">Left to Budget</h2>
            <div class="h-1 w-full bg-blue-400"></div>
        </div>
    </div>
</section>

<div class="container mx-auto p-5">
    <!-- Month Selector -->
    <div class="mb-4">
        <label for="monthSelect" class="font-bold">Select Month:</label>
        <select id="monthSelect" class="ml-2 p-2 border rounded">
            <option value="all">All</option>
            <?php foreach ($allMonths as $month): ?>
                <option value="<?php echo $month; ?>" <?php echo $month === $selectedMonth ? 'selected' : ''; ?>>
                    <?php echo date('F Y', strtotime($month)); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Graphs -->
    <div class="grid gap-8 w-full mx-auto justify-between" style="grid-template-columns: 25% 50% 25%; grid-template-rows: 300px">
        <!-- Income Overview Pie Chart -->
        <div class="w-full border rounded-xl p-4">
            <canvas id="incomeOverviewChart" width="auto" height="auto"></canvas>
        </div>
        <!-- Left to Budget vs Actual Expenses Bar Chart -->
        <div class="w-full border rounded-xl p-4">
            <canvas id="budgetVsActualChart" width="auto" height="auto"></canvas>
        </div>
        <!-- Doughnut Chart -->
        <div class="w-full border rounded-xl p-4">
            <canvas id="doughnutChart" width="auto" height="300" style="height: 250px"></canvas>
        </div>
    </div>

    <!-- Notes -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold">Notes</h2>
        <!-- Add Notes here -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('monthSelect').addEventListener('change', function() {
        const selectedMonth = this.value;
        window.location.href = `dashboard.php?month=${selectedMonth}`;
    });

    // Income Overview Pie Chart
    const ctxIncomeOverview = document.getElementById('incomeOverviewChart').getContext('2d');
    const incomeOverviewChart = new Chart(ctxIncomeOverview, {
        type: 'pie',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                data: [<?php echo $totalIncome; ?>, <?php echo $totalExpenses; ?>],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

    // Left to Budget vs Actual Expenses Bar Chart
    const ctxBudgetVsActual = document.getElementById('budgetVsActualChart').getContext('2d');
    const budgetVsActualChart = new Chart(ctxBudgetVsActual, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categoryNames); ?>,
            datasets: [{
                label: 'Budget',
                data: <?php echo json_encode($categoryBudgetAmounts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Actual',
                data: <?php echo json_encode($categoryActualAmounts); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Doughnut Chart
    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    const doughnutChart = new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                data: [<?php echo $totalIncome; ?>, <?php echo $totalExpenses; ?>],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
