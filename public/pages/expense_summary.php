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


<!-- Main Content -->
<main class="col-start-2 col-end-3 row-start-2 row-end-3 pl-24">
  <div class="h-full w-full p-5">
    <div
      class="flex flex-col gap-5 sm:flex-row w-full sm:justify-between sm:items-center"
    >
    <div>
        <h2 class="text-3xl font-medium">Expense Summary</h2>
        <div class="mb-6">
            <div class="font-semibold">Total Expense Budget: <?php echo htmlspecialchars(number_format($totalBudget, 2)); ?></div>
            <div class="font-semibold">Total Expenses: <?php echo htmlspecialchars(number_format($totalActual, 2)); ?></div>
        </div>
    </div>



      <div class="flex gap-4 flex-col sm:flex-row items-start">
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
      </div>
    </div>
    <div class="mt-10">
      <div>
        <!-- Popup Element -->
        <div
          id="popup"
          class="bg-black bg-opacity-20 backdrop-blur fixed inset-0 z-[9999] flex items-center justify-center hidden"
        >
          <div class="relative">
            <!-- Close Button -->
            <div
              id="onClose"
              class="absolute top-3 text-[10px] right-3 cursor-pointer size-7 border flex items-center justify-center rounded-full bg-black text-white"
            >
              <div><i class="fa-solid fa-x"></i></div>
            </div>
            <!-- Popup Content -->
            <div
              id="popup-content"
              class="sm:w-[500px] bg-white sm:h-[500px] rounded-xl p-5"
            >
              <div class="flex flex-col justify-between h-full">
                <div>
                  <h2 class="text-3xl font-bold">Create an Expense</h2>
                </div>
                <div class="flex flex-col gap-2">
                  <div class="flex flex-col gap-1">
                    <label for="" class="text-lg font-medium"
                      >Expense name</label
                    >
                    <select class="border px-4 py-2 rounded">
                      <option value="--Select Category--" disabled selected>
                        --Select Category--
                      </option>
                      <option value="Salary">Salary</option>
                      <option value="Add New Category">Add New Category</option>
                    </select>
                  </div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-1">
                      <label for="" class="text-lg font-medium"
                        >Budget Amount:</label
                      >
                      <input
                        class="border px-4 py-2 rounded"
                        type="text"
                        placeholder="Amount"
                      />
                    </div>
                    <div class="flex flex-col gap-1">
                      <label for="" class="text-lg font-medium"
                        >Actual Amount:</label
                      >
                      <input
                        class="border px-4 py-2 rounded"
                        type="text"
                        placeholder="Amount"
                      />
                    </div>
                  </div>
                  <div class="flex flex-col gap-1">
                    <label for="" class="text-lg font-medium">Date</label>
                    <input
                      class="border px-4 py-2 rounded"
                      type="date"
                      placeholder="Date"
                    />
                  </div>
                  <div class="flex flex-col gap-1">
                    <label for="" class="text-lg font-medium"
                      >Description:</label
                    >
                    <textarea
                      class="border px-4 py-2 rounded resize-none"
                      placeholder="Description:"
                    ></textarea>
                  </div>
                </div>
                <div class="ml-auto mt-5 sm:mt-0">
                  <button
                    class="bg-black text-white px-6 rounded py-2 font-medium text-lg"
                  >
                    Save
                  </button>
                  <button
                    class="bg-black text-white px-6 rounded py-2 font-medium text-lg"
                  >
                    Save & Add More
                  </button>
                  <?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$categoryModel = new Category();
$expenseModel = new Expense();
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['category_name'];
    $budgetAmount = $_POST['budget_amount'];
    $actualAmount = $_POST['actual_amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Add custom category if needed
    if (isset($_POST['new_category']) && !empty($_POST['new_category'])) {
        $newCategory = $_POST['new_category'];
        if (!$categoryModel->categoryExists($newCategory, 'expense', $userId)) {
            $categoryModel->addCategory($newCategory, 'expense', $userId);
        }
        $categoryName = $newCategory;
    }

    // Get category ID
    $categories = $categoryModel->getCategoriesByType($userId, 'expense');
    $categoryId = null;
    foreach ($categories as $category) {
        if ($category['name'] === $categoryName) {
            $categoryId = $category['id'];
            break;
        }
    }

    if ($categoryId) {
        $expenseModel->addExpense($userId, $categoryId, $budgetAmount, $actualAmount, $date, $description);
        header("Location: " . BASE_URL . "?page=expense_summary");
        exit;
    }
}

// Get default categories and user's custom categories
$defaultCategories = $categoryModel->getCategoriesByType(1, 'expense'); // Default categories by user_id = 1
$userCategories = $categoryModel->getCategoriesByType($userId, 'expense'); // Custom categories by the current user
?>

<h1 class="text-2xl font-semibold mb-6">Add Expense</h1>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="category_name" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
    <select id="category_name" name="category_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <option value="">--Select Category--</option>
        <?php foreach ($defaultCategories as $category): ?>
            <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
        <?php foreach ($userCategories as $category): ?>
            <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
        <option value="Add New Category">Add New Category</option>
    </select>
    <div id="new_category_container" class="hidden">
        <label for="new_category" class="block text-gray-700 text-sm font-bold mb-2">New Category:</label>
        <input type="text" name="new_category" id="new_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <label for="budget_amount" class="block text-gray-700 text-sm font-bold mb-2">Budget Amount:</label>
    <input type="text" name="budget_amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="actual_amount" class="block text-gray-700 text-sm font-bold mb-2">Actual Amount:</label>
    <input type="text" name="actual_amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
    <input type="date" name="date" id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
</form>

<script>
document.getElementById('category_name').addEventListener('change', function() {
    if (this.value === 'Add New Category') {
        document.getElementById('new_category_container').style.display = 'block';
    } else {
        document.getElementById('new_category_container').style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();
    var dateInput = document.getElementById('date');
    dateInput.value = today.toISOString().split('T')[0];
});
</script>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid">
          <!-- Add Expense Button -->
          <div
            id="add-expense"
            class="md:w-96 border h-32 bg-white rounded-xl flex flex-col items-center justify-center text-[17px] font-medium cursor-pointer"
          >
            <div>
              <i class="fa-solid fa-plus text-xl"></i>
            </div>
            <div>Add new Expense</div>
          </div>
        </div>

        <div class="mt-10">
          <?php if (empty($expenses)): ?>
          <div class="col-span-1 md:col-span-3 text-center text-gray-500">
            It seems no data found.
          </div>
          <?php else: ?>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($groupedExpenses as $category =>
            $expenses): ?>
            <table
              class="w-full border-collapse bg-white text-left text-sm text-gray-500"
            >
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Date
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Expense Name
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Budget
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Actual
                  </th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900">
                    Actions
                  </th>
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
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars(date('j M', strtotime($expense['date']))); ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars($expense['description'] ?? ''); ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars($expense['budget_amount'] ?? ''); ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars($expense['actual_amount'] ?? ''); ?>
                  </td>
                  <td class="px-6 py-4">
                    <a
                      href="?page=edit_expense&id=<?php echo $expense['id']; ?>"
                      class="text-blue-500 hover:underline"
                      ><i class="fas fa-edit"></i
                    ></a>
                    <a
                      href="?page=delete_expense&id=<?php echo $expense['id']; ?>"
                      class="text-red-500 hover:underline"
                      ><i class="fas fa-trash"></i
                    ></a>
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
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars(number_format($categoryTotalBudget, 2)); ?>
                  </td>
                  <td class="px-6 py-4">
                    <?php echo htmlspecialchars(number_format($categoryTotalActual, 2)); ?>
                  </td>
                  <td class="px-6 py-4"></td>
                </tr>
              </tfoot>
            </table>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  const addExpense = document.getElementById("add-expense");
  const popup = document.getElementById("popup");
  const onClose = document.getElementById("onClose");
  const popupContent = document.getElementById("popup-content");

  // Show popup when "Add new Expense" is clicked
  addExpense.addEventListener("click", () => {
    popup.classList.remove("hidden");
  });

  // Close popup when close button is clicked
  onClose.addEventListener("click", () => {
    popup.classList.add("hidden");
  });

  // Close popup when clicking outside the popup content
  popup.addEventListener("click", (e) => {
    if (!popupContent.contains(e.target)) {
      popup.classList.add("hidden");
    }
  });
</script>






<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
