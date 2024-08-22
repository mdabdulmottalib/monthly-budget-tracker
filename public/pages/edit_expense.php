<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Expense.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';

Auth::checkRole([1, 2]); // Allow Admin and Manager roles
Auth::checkSubscription(); // Check if user has an active subscription

$expenseModel = new Expense();
$categoryModel = new Category();

$expenseId = $_GET['id'];
$expense = $expenseModel->getExpenseById($expenseId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $budget_amount = $_POST['budget_amount'];
    $actual_amount = $_POST['actual_amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $expenseModel->updateExpense($expenseId, $category_id, $budget_amount, $actual_amount, $date, $description);
    header("Location: " . BASE_URL . "?page=expense_summary");
    exit;
}

$userId = $_SESSION['user']['id'];
$defaultCategories = $categoryModel->getCategoriesByType(1, 'expense'); // Get default categories (user ID 1)
$userCategories = $categoryModel->getCategoriesByType($userId, 'expense'); // Get categories created by the current user
?>

<h1 class="text-2xl font-semibold mb-6">Edit Expense</h1>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
    <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <?php foreach ($defaultCategories as $category): ?>
            <option value="<?php echo $category['id']; ?>" <?php echo $expense['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
        <?php foreach ($userCategories as $category): ?>
            <option value="<?php echo $category['id']; ?>" <?php echo $expense['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="budget_amount" class="block text-gray-700 text-sm font-bold mb-2">Budget Amount:</label>
    <input type="text" name="budget_amount" value="<?php echo $expense['budget_amount']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="actual_amount" class="block text-gray-700 text-sm font-bold mb-2">Actual Amount:</label>
    <input type="text" name="actual_amount" value="<?php echo $expense['actual_amount']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
    <input type="date" name="date" value="<?php echo $expense['date']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo $expense['description']; ?></textarea>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
