<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Income.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$categoryModel = new Category();
$incomeModel = new Income();
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
        if (!$categoryModel->categoryExists($newCategory, 'income', $userId)) {
            $categoryModel->addCategory($newCategory, 'income', $userId);
        }
        $categoryName = $newCategory;
    }

    // Get category ID
    $categories = $categoryModel->getCategoriesByType($userId, 'income');
    $categoryId = null;
    foreach ($categories as $category) {
        if ($category['name'] === $categoryName) {
            $categoryId = $category['id'];
            break;
        }
    }

    if ($categoryId) {
        $incomeModel->addIncome($userId, $categoryId, $budgetAmount, $actualAmount, $date, $description);
        header("Location: " . BASE_URL . "?page=income_summary");
        exit;
    }
}

// Get default categories and user's custom categories
$defaultCategories = $categoryModel->getCategoriesByType(1, 'income'); // Default categories by user_id = 1
$userCategories = $categoryModel->getCategoriesByType($userId, 'income'); // Custom categories by the current user
?>

<h1 class="text-2xl font-semibold mb-6">Add Income</h1>

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

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
