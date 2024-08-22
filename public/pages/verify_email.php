<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

$token = $_GET['token'] ?? '';
$userModel = new User();

if ($userModel->verifyEmail($token)) {
    $message = "Your email has been verified successfully!";
} else {
    $error = "Invalid or expired verification link.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto h-screen flex justify-center items-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold mb-4 text-center">Email Verification</h1>
            <?php if (isset($message)): ?>
                <p class="text-green-500 text-center mb-4"><?php echo $message; ?></p>
            <?php elseif (isset($error)): ?>
                <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <p class="text-center">
                <a href="<?php echo BASE_URL; ?>?page=login" class="text-blue-500 hover:underline">Go to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
