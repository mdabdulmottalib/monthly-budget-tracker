<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Auth.php';

// Ensure only admins can access this page
Auth::checkRole([1]);

$userModel = new User();

// Handle actions
if (isset($_GET['action'])) {
    $userId = $_GET['id'];
    switch ($_GET['action']) {
        case 'edit':
            // Redirect to edit user page
            header("Location: " . BASE_URL . "?page=edit_user&id=$userId");
            exit;
        case 'delete':
            header("Location: " . BASE_URL . "?page=delete_user&id=$userId");
            exit;
        case 'verify':
            $userModel->verifyUser($userId);
            break;
        case 'resend':
            $user = $userModel->getUserById($userId);
            $userModel->sendVerificationEmail($user['email'], $user['verification_token']);
            break;
        case 'login_as':
            Auth::loginAsUser($userId);
            header("Location: " . BASE_URL . "?page=dashboard");
            exit;
        case 'return_to_admin':
            Auth::returnToAdmin();
            header("Location: " . BASE_URL . "?page=users");
            exit;
    }
}

$users = $userModel->getAllUsers();
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php'; ?>

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-6">Manage Users</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Role</th>
                <th class="py-2 px-4 border-b">Verified</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?php echo $user['id']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $user['name']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $user['email']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $user['role_id']; ?></td>
                    <td class="py-2 px-4 border-b"><?php echo $user['email_verified'] ? 'Yes' : 'No'; ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="<?php echo BASE_URL; ?>?page=edit_user&id=<?php echo $user['id']; ?>" class="text-blue-500 hover:underline">Edit</a> |
                        <a href="<?php echo BASE_URL; ?>?page=delete_user&id=<?php echo $user['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</a> |
                        <?php if (!$user['email_verified']): ?>
                            <a href="<?php echo BASE_URL; ?>?page=users&action=verify&id=<?php echo $user['id']; ?>" class="text-green-500 hover:underline">Verify</a> |
                            <a href="<?php echo BASE_URL; ?>?page=users&action=resend&id=<?php echo $user['id']; ?>" class="text-orange-500 hover:underline">Resend Verification</a> |
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>?page=users&action=login_as&id=<?php echo $user['id']; ?>" class="text-purple-500 hover:underline">Login As</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (isset($_SESSION['original_user_id'])): ?>
        <a href="<?php echo BASE_URL; ?>?page=users&action=return_to_admin" class="text-red-500 hover:underline mt-4 inline-block">Return to Admin</a>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php'; ?>
