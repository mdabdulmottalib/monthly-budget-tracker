<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/helpers/Auth.php';

session_start();

$role = Auth::getUserRole();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <aside class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4">
                <a href="<?php echo BASE_URL; ?>" class="text-lg font-bold"><?php echo APP_NAME; ?></a>
            </div>
            <nav class="mt-10">
                <a href="<?php echo BASE_URL; ?>?page=dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <?php if (in_array($role, [1, 2, 3])): // Admin, Manager, and User ?>
                    <a href="<?php echo BASE_URL; ?>?page=income_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-dollar-sign"></i> Income Summary</a>
                    <a href="<?php echo BASE_URL; ?>?page=add_income" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-plus-circle"></i> Add Income</a>
                    <a href="<?php echo BASE_URL; ?>?page=expense_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-receipt"></i> Expenses</a>
                    <a href="<?php echo BASE_URL; ?>?page=add_expense" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-plus-circle"></i> Add Expense</a>
                    <a href="<?php echo BASE_URL; ?>?page=manage_categories" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-tags"></i> Manage Categories</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>?page=note" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-sticky-note"></i> Notes</a>
                <a href="<?php echo BASE_URL; ?>?page=profile" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-user"></i> Profile</a>
                <?php if ($role == 1): // Admin ?>
                    <a href="<?php echo BASE_URL; ?>?page=settings" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-cog"></i> Settings</a>
                    <a href="<?php echo BASE_URL; ?>?page=admin_dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-user-shield"></i> Admin Dashboard</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>?page=logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>
        <div class="flex-1 min-h-screen p-8">
