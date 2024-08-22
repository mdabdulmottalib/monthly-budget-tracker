<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Income.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';

Auth::checkRole([1, 2, 3]);
// Auth::checkSubscription();

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

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
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
<!-- New cards -->
<section
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 p-5 md:pl-24 gap-5"
      >
        <div
          class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative"
        >
          <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
          </div>
          <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold">$<?php echo number_format($totalIncome, 2); ?></h2>
            <h2 class="font-semibold text-lg">Total</h2>
            <div class="h-1 w-full bg-blue-400"></div>
          </div>
        </div>

        <div
          class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative"
        >
          <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
          </div>
          <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold"><?php echo $leftToBudget < 0 ? '-$' . number_format(abs($leftToBudget), 2) : '$' . number_format($leftToBudget, 2); ?></h2>
            <h2 class="font-semibold text-lg">Left To Budget</h2>
            <div class="h-1 w-full bg-blue-400"></div>
          </div>
        </div>

        <div
          class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative"
        >
          <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
          </div>
          <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold">$<?php echo number_format($totalExpenses, 2); ?></h2>
            <h2 class="font-semibold text-lg">Expenses</h2>
            <div class="h-1 w-full bg-blue-400"></div>
          </div>
        </div>

        <div
          class="bg-white border shadow-sm h-40 rounded-xl flex flex-col justify-end p-8 relative"
        >
          <div class="absolute top-3 right-7 text-6xl text-gray-400 opacity-40">
            <i class="fa-solid fa-money-check-dollar"></i>
          </div>
          <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold"><?php echo $leftToSpend < 0 ? '-$' . number_format(abs($leftToSpend), 2) : '$' . number_format($leftToSpend, 2); ?></h2>
            <h2 class="font-semibold text-lg">Left to Spend</h2>
            <div class="h-1 w-full bg-blue-400"></div>
          </div>
        </div>
      </section>

 <!-- Main Content -->
 <main class="p-5 md:pl-24">
        <div
          class="w-full h-full grid grid-cols-1 md:grid-cols-2 lg:h-[100vh] lg:grid-rows-[350px_1fr_1fr] lg:grid-cols-4 xl:grid-cols-[350px_1fr_1fr_350px] gap-5"
        >
          <section
            class="bg-white flex lg:row-start-1 lg:row-end-2 lg:col-start-1 lg:col-end-2 items-center justify-center p-5 rounded-xl border border-[#EFEFF4] shadow-sm"
          >
            <canvas id="myPieChart1"></canvas>
          </section>

          <section
            class="bg-white p-5 h-full rounded-xl border border-[#EFEFF4] shadow-sm md:col-span-full lg:col-start-2 lg:row-start-1 lg:row-end-2 lg:col-end-4"
          >
            <div id="chart" class="w-full"></div>
          </section>

          <section
            class="bg-white flex md:row-start-1 md:row-end-2 md:col-start-2 md:col-end-3 lg:row-start-1 lg:row-end-2 lg:col-start-4 lg:col-end-5 items-center justify-center p-5 rounded-xl border border-[#EFEFF4] shadow-sm"
          >
            <canvas id="myPieChart2"></canvas>
          </section>

          <section
            class="bg-white flex md:col-span-full w-full overflow-hidden p-5 rounded-xl border border-[#EFEFF4] shadow-sm lg:row-start-2 lg:row-end-4 lg:col-start-1 lg:col-end-2"
          >
            <table class="w-full">
              <thead>
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-start text-xs font-medium uppercase dark:text-neutral-500"
                  >
                    note
                  </th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-start text-xs font-medium uppercase dark:text-neutral-500"
                  >
                    budget
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y">
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    Salary
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">20000</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">19000</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">1000</td>
                </tr>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    Salary
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">20000</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">19000</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">1000</td>
                </tr>
              </tbody>
            </table>
          </section>

          <section
            class="bg-white rounded-xl md:col-span-full lg:row-start-2 lg:row-end-4 lg:col-start-2 lg:col-end-4 border border-[#EFEFF4] shadow-sm"
          >
            <h2 class="block text-center py-2 font-bold text-2xl">All</h2>
            <table
              class="w-full border-collapse bg-white text-left text-sm text-gray-500"
            >
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Name
                  </th>
                </tr>
              </thead>
              <tbody class="divide-gray-100 border-t border-gray-100">
                <tr class="hover:bg-gray-50">
                  <!--  -->

                  <td class="px-6 py-4">Product Designer</td>
                </tr>
              </tbody>
            </table>
          </section>

          <section
            class="bg-white rounded-xl md:col-span-full border border-[#EFEFF4] shadow-sm lg:col-start-4 lg:col-end-5"
          >
            <table
              class="w-full border-collapse bg-white text-left text-sm text-gray-500"
            >
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    State
                  </th>
                </tr>
              </thead>
              <tbody class="divide-gray-100 border-t border-gray-100">
                <tr class="hover:bg-gray-50">
                  <!--  -->
                  <td class="px-6 py-4">
                    <span
                      class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600"
                    >
                      <span
                        class="h-1.5 w-1.5 rounded-full bg-green-600"
                      ></span>
                      Active
                    </span>
                  </td>
                  <td class="px-6 py-4">Product Designer</td>
                </tr>
              </tbody>
            </table>
          </section>
          <section
            class="bg-white rounded-xl md:col-span-full border border-[#EFEFF4] shadow-sm lg:col-start-4 lg:col-end-5 lg:row-start-3 lg:row-end-4"
          >
            <table
              class="w-full border-collapse bg-white text-left text-sm text-gray-500"
            >
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    State
                  </th>
                </tr>
              </thead>
              <tbody class="divide-gray-100 border-t border-gray-100">
                <tr class="hover:bg-gray-50">
                  <!--  -->
                  <td class="px-6 py-4">
                    <span
                      class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600"
                    >
                      <span
                        class="h-1.5 w-1.5 rounded-full bg-green-600"
                      ></span>
                      Active
                    </span>
                  </td>
                  <td class="px-6 py-4">Product Designer</td>
                </tr>
              </tbody>
            </table>
          </section>
        </div>
      </main>

      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  // ApexCharts options and rendering
  var options = {
    series: [
      {
        name: "series1",
        data: [31, 40, 28, 51, 42, 109, 100],
      },
      {
        name: "series2",
        data: [11, 32, 45, 32, 34, 52, 41],
      },
    ],
    chart: {
      height: 350,
      type: "area",
      toolbar: {
        show: false, // Hides the chart's toolbar (top bar)
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: "smooth",
    },
    xaxis: {
      type: "datetime",
      categories: [
        "2018-09-19T00:00:00.000Z",
        "2018-09-19T01:30:00.000Z",
        "2018-09-19T02:30:00.000Z",
        "2018-09-19T03:30:00.000Z",
        "2018-09-19T04:30:00.000Z",
        "2018-09-19T05:30:00.000Z",
        "2018-09-19T06:30:00.000Z",
      ],
    },
    tooltip: {
      x: {
        format: "dd/MM/yy HH:mm",
      },
    },
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();

  // Chart.js Pie Charts Configuration
  const data1 = {
    labels: ["Red", "Blue", "Yellow"],
    datasets: [
      {
        label: "My First Dataset",
        data: [300, 50, 100],
        backgroundColor: [
          "rgb(255, 99, 132)",
          "rgb(54, 162, 235)",
          "rgb(255, 205, 86)",
        ],
        hoverOffset: 4,
      },
    ],
  };

  const data2 = {
    labels: ["Green", "Purple", "Orange"],
    datasets: [
      {
        label: "My Second Dataset",
        data: [200, 150, 250],
        backgroundColor: [
          "rgb(75, 192, 192)",
          "rgb(153, 102, 255)",
          "rgb(255, 159, 64)",
        ],
        hoverOffset: 4,
      },
    ],
  };

  const config1 = {
    type: "pie",
    data: data1,
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false, // Hide the legend
        },
        tooltip: {
          enabled: true,
        },
      },
    },
  };

  const config2 = {
    type: "pie",
    data: data2,
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false, // Hide the legend
        },
        tooltip: {
          enabled: true,
        },
      },
    },
  };

  // Render the first pie chart
  const myPieChart1 = new Chart(
    document.getElementById("myPieChart1"),
    config1
  );

  // Render the second pie chart
  const myPieChart2 = new Chart(
    document.getElementById("myPieChart2"),
    config2
  );

  // Handle other functionalities (e.g., sidebar menu toggle)
  const menuButton = document.querySelectorAll(".menu-button");
  const sideBarMenu = document.querySelector(".side-bar-menu");

  menuButton.forEach((button) => {
    button.addEventListener("click", () => {
      sideBarMenu.classList.toggle("translate-x-0");
    });
  });
</script>



<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
