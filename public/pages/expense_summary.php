<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$expenseModel = new Expense();
$categoryModel = new Category();
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

// Handle the form submission for adding an expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    // Set the content type to JSON if it's an AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
    }

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
        $lastInsertId = $expenseModel->addExpense($userId, $categoryId, $budgetAmount, $actualAmount, $date, $description);

        if ($lastInsertId) {
            // Return JSON response if it's an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'date' => date('j M', strtotime($date)),
                        'description' => htmlspecialchars($description),
                        'budget_amount' => number_format($budgetAmount, 2),
                        'actual_amount' => number_format($actualAmount, 2),
                        'id' => $lastInsertId
                    ]
                ]);
                exit;
            }
        } else {
            // Handle error (e.g., database insert failed)
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode(['success' => false, 'error' => 'Failed to insert expense']);
                exit;
            }
        }
    }
}

?>


<!-- Main Content -->
<main class="col-start-2 col-end-3 row-start-2 row-end-3 pl-24">
  <div class="h-full w-full p-5">
    <div class="flex flex-col gap-5 sm:flex-row w-full sm:justify-between sm:items-center">
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
        <div id="popup" class="bg-black bg-opacity-20 backdrop-blur fixed inset-0 z-[9999] flex items-center justify-center hidden">
          <div class="relative">
            <!-- Close Button -->
            <div id="onClose" class="absolute top-3 text-[10px] right-3 cursor-pointer size-7 border flex items-center justify-center rounded-full bg-black text-white">
              <div><i class="fa-solid fa-x"></i></div>
            </div>
            <!-- Popup Content -->
            <div id="popup-content" class="sm:w-[500px] bg-white sm:h-[500px] rounded-xl p-5">
              <div class="flex flex-col justify-between h-full">
                <div>
                  <h2 class="text-3xl font-bold">Create an Expense</h2>
                </div>
                <form id="expense-form">
                  <div class="flex flex-col gap-2">
                    <div class="flex flex-col gap-1">
                      <label for="category_name" class="text-lg font-medium">Expense Category</label>
                      <select id="category_name" name="category_name" class="border px-4 py-2 rounded" required>
                        <option value="" disabled selected>--Select Category--</option>
                        <?php foreach ($categoryModel->getCategoriesByType($userId, 'expense') as $category): ?>
                          <option value="<?php echo htmlspecialchars($category['name']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                        <option value="Add New Category">Add New Category</option>
                      </select>
                    </div>
                    <div id="new_category_container" class="flex flex-col gap-1 hidden">
                      <label for="new_category" class="text-lg font-medium">New Category</label>
                      <input type="text" id="new_category" name="new_category" class="border px-4 py-2 rounded">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                      <div class="flex flex-col gap-1">
                        <label for="budget_amount" class="text-lg font-medium">Budget Amount</label>
                        <input type="text" id="budget_amount" name="budget_amount" class="border px-4 py-2 rounded" required>
                      </div>
                      <div class="flex flex-col gap-1">
                        <label for="actual_amount" class="text-lg font-medium">Actual Amount</label>
                        <input type="text" id="actual_amount" name="actual_amount" class="border px-4 py-2 rounded" required>
                      </div>
                    </div>
                    <div class="flex flex-col gap-1">
                      <label for="date" class="text-lg font-medium">Date</label>
                      <input type="date" id="date" name="date" class="border px-4 py-2 rounded" required>
                    </div>
                    <div class="flex flex-col gap-1">
                      <label for="description" class="text-lg font-medium">Description</label>
                      <textarea id="description" name="description" class="border px-4 py-2 rounded resize-none" required></textarea>
                    </div>
                  </div>
                  <div class="ml-auto mt-5 sm:mt-0 flex gap-3">
                    <button type="button" id="save-expense" class="bg-black text-white px-6 rounded py-2 font-medium text-lg">Save</button>
                    <button type="button" id="save-add-more" class="bg-black text-white px-6 rounded py-2 font-medium text-lg">Save & Add More</button>
                  </div>
                  <input type="hidden" name="add_expense" value="1">
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="grid">
          <!-- Add Expense Button -->
          <div id="add-expense" class="md:w-96 border h-32 bg-white rounded-xl flex flex-col items-center justify-center text-[17px] font-medium cursor-pointer">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#category_name').on('change', function() {
        if ($(this).val() === 'Add New Category') {
            $('#new_category_container').show();
        } else {
            $('#new_category_container').hide();
        }
    });

    // Open popup
    $('#add-expense').on('click', function() {
        $('#popup').removeClass('hidden');
    });

    // Close popup
    $('#onClose').on('click', function() {
        $('#popup').addClass('hidden');
    });

    // Save and close popup
    $('#save-expense').on('click', function() {
        submitForm(false);
    });

    // Save and add more
    $('#save-add-more').on('click', function() {
        submitForm(true);
    });

    function submitForm(addMore) {
        $.ajax({
            url: '', // The same page will handle the request
            type: 'POST',
            data: $('#expense-form').serialize(),
            success: function(response) {
                if (response.success) {
                    // Update table with the new expense
                    let newRow = `<tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">${response.data.date}</td>
                        <td class="px-6 py-4">${response.data.description}</td>
                        <td class="px-6 py-4">${response.data.budget_amount}</td>
                        <td class="px-6 py-4">${response.data.actual_amount}</td>
                        <td class="px-6 py-4">
                            <a href="?page=edit_expense&id=${response.data.id}" class="text-blue-500 hover:underline"><i class="fas fa-edit"></i></a>
                            <a href="?page=delete_expense&id=${response.data.id}" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>`;
                    $('tbody').prepend(newRow);

                    if (addMore) {
                        // Clear form fields
                        $('#expense-form')[0].reset();
                        $('#new_category_container').hide();
                    } else {
                        $('#popup').addClass('hidden');
                    }
                } else {
                    alert('Error saving expense. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX Error: ' + error);
            }
        });
    }
});
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>