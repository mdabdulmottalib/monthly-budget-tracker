<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Category.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$categoryModel = new Category();
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        if (!$categoryModel->categoryExists($name, $type, $userId)) {
            $categoryModel->addCategory($name, $type, $userId);
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $categoryModel->updateCategory($id, $name, $type);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $categoryModel->deleteCategory($id);
    }
    header("Location: " . BASE_URL . "?page=manage_categories");
    exit;
}

$categories = $categoryModel->getCategoriesByUser($userId);
?>

<h1 class="text-2xl font-semibold mb-6">Manage Categories</h1>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <input type="hidden" name="id" id="category_id">
    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name:</label>
    <input type="text" name="name" id="category_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>

    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Category Type:</label>
    <select name="type" id="category_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select>

    <button type="submit" name="add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Category</button>
</form>

<table class="min-w-full bg-white shadow-md rounded my-6">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">#</th>
            <th class="w-4/12 py-3 px-4 uppercase font-semibold text-sm">Category Name</th>
            <th class="w-3/12 py-3 px-4 uppercase font-semibold text-sm">Type</th>
            <th class="w-4/12 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
        </tr>
    </thead>
    <tbody class="text-gray-700">
        <?php $serial = 1; ?>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td class="py-3 px-4"><?php echo $serial++; ?></td>
                <td class="py-3 px-4"><?php echo $category['name']; ?></td>
                <td class="py-3 px-4"><?php echo ucfirst($category['type']); ?></td>
                <td class="py-3 px-4">
                    <button class="text-blue-500 hover:underline" onclick="editCategory(<?php echo $category['id']; ?>, '<?php echo $category['name']; ?>', '<?php echo $category['type']; ?>')"><i class="fas fa-edit"></i></button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                        <button type="submit" name="delete" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
function editCategory(id, name, type) {
    document.getElementById('category_id').value = id;
    document.getElementById('category_name').value = name;
    document.getElementById('category_type').value = type;
    document.querySelector('[name="add"]').innerText = 'Update Category';
    document.querySelector('[name="add"]').name = 'edit';
}
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
