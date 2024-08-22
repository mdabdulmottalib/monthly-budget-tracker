<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    $userModel = new User();
    $user = $userModel->getUserByUsernameOrEmail($usernameOrEmail);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['email_verified']) {
            $_SESSION['user'] = $user;
            header("Location: " . BASE_URL . "?page=dashboard");
            exit;
        } else {
            $error = "Please verify your email address to log in.";
        }
    } else {
        $error = "Invalid login credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto h-screen flex justify-center items-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Login</h1>
            <?php if (isset($error)): ?>
                <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label for="username_or_email" class="block text-gray-700 text-sm font-bold mb-2">Username or Email:</label>
                    <input type="text" name="username_or_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                </div>
            </form>
            <p class="text-center">
                Don't have an account? <a href="<?php echo BASE_URL; ?>?page=register" class="text-blue-500 hover:underline">Register here</a>.
            </p>
        </div>
    </div>
</body>
</html>
