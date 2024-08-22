<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Auth.php';

Auth::checkRole([1]);

$userModel = new User();
$userId = $_GET['id'];
$user = $userModel->getUserById($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'role_id' => $_POST['role'],
        'email_verified' => isset($_POST['email_verified']) ? 1 : 0
    ];
    $userModel->updateUser($userId, $data);
    header('Location: ' . BASE_URL . '?page=users');
    exit;
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php'; ?>

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-6">Edit User</h1>
    <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
            <select name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" <?php if ($user['role_id'] == 1) echo 'selected'; ?>>Admin</option>
                <option value="2" <?php if ($user['role_id'] == 2) echo 'selected'; ?>>Editor</option>
                <option value="3" <?php if ($user['role_id'] == 3) echo 'selected'; ?>>User</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="email_verified" class="block text-gray-700 text-sm font-bold mb-2">
                <input type="checkbox" name="email_verified" <?php if ($user['email_verified']) echo 'checked'; ?>> Email Verified
            </label>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php'; ?>
