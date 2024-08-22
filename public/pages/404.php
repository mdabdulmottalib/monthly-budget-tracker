<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto h-screen flex justify-center items-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold mb-4">404</h1>
            <p class="text-2xl">Page Not Found</p>
            <a href="<?php echo BASE_URL; ?>" class="text-blue-500 hover:underline mt-4 block">Go back to Home</a>
        </div>
    </div>
</body>
</html>
