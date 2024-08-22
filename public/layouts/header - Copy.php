<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

// Include the page titles configuration file
$pageTitles = include $_SERVER['DOCUMENT_ROOT'] . '/config/page_titles.php';

$role = Auth::getUserRole();
$userModel = new User();
$userId = $_SESSION['user']['id'];
$user = $userModel->getUserById($userId);
$userMeta = $userModel->getUserMetaById($userId);
$defaultImage = "https://static.vecteezy.com/system/resources/thumbnails/004/899/680/small/beautiful-blonde-woman-with-makeup-avatar-for-a-beauty-salon-illustration-in-the-cartoon-style-vector.jpg";

// Correctly get the page name from URL
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$pageTitle = isset($pageTitles[$currentPage]) ? $pageTitles[$currentPage] : 'Page';

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .sidebar-expanded {
            width: 16rem;
        }
        .sidebar-collapsed {
            width: 4rem;
        }
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        .submenu {
            display: none;
        }
        .submenu-open {
            display: block;
        }
        .active {
            background-color: #4A5568;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    var submenu = this.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        submenu.classList.toggle('submenu-open');
                        menuItems.forEach(function (el) {
                            if (el !== item) {
                                var otherSubmenu = el.nextElementSibling;
                                if (otherSubmenu && otherSubmenu.classList.contains('submenu')) {
                                    otherSubmenu.classList.remove('submenu-open');
                                }
                            }
                        });
                    }
                });
            });

            // Highlight the active menu item
            var activePage = '<?php echo $currentPage; ?>';
            var links = document.querySelectorAll('nav a');
            links.forEach(function (link) {
                if (link.href.includes(activePage)) {
                    link.classList.add('active');
                }
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-expanded');
            sidebar.classList.toggle('sidebar-collapsed');
        }
    </script>
</head>
<body class="bg-gray-100 flex h-screen">
    <aside id="sidebar" class="sidebar-expanded bg-gray-800 text-white h-full transition-all duration-300">
        <div class="p-4 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>" class="text-lg font-bold sidebar-text"><?php echo APP_NAME; ?></a>
            <button onclick="toggleSidebar()" class="text-white focus:outline-none">
                <i class="fas fa-arrow-left" id="toggleIcon"></i>
            </button>
        </div>
        <nav class="mt-10">
            <a href="<?php echo BASE_URL; ?>?page=dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                <i class="fas fa-tachometer-alt"></i>
                <span class="sidebar-text ml-3">Dashboard</span>
            </a>
            <?php if (in_array($role, [1, 2, 3])): ?>
                <div class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span class="sidebar-text ml-3">Income</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </div>
                <div id="income-submenu" class="submenu ml-10 mt-2">
                    <a href="<?php echo BASE_URL; ?>?page=add_income" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Add Income</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=income_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Income History</span>
                    </a>
                </div>
                <div class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item">
                    <i class="fas fa-receipt"></i>
                    <span class="sidebar-text ml-3">Expenses</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </div>
                <div id="expenses-submenu" class="submenu ml-10 mt-2">
                    <a href="<?php echo BASE_URL; ?>?page=add_expense" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Add Expenses</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=expense_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Expenses History</span>
                    </a>
                </div>
                <div class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item">
                    <i class="fas fa-sticky-note"></i>
                    <span class="sidebar-text ml-3">Notes</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </div>
                <div id="notes-submenu" class="submenu ml-10 mt-2">
                    <a href="<?php echo BASE_URL; ?>?page=add_note" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Add Note</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=note_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Note History</span>
                    </a>
                </div>
                <div class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item">
                    <i class="fas fa-calculator"></i>
                    <span class="sidebar-text ml-3">Debt</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </div>
                <div id="debt-submenu" class="submenu ml-10 mt-2">
                    <a href="<?php echo BASE_URL; ?>?page=add_debt" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Add Debt</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=debt_snowball_summary" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Debt Snowball Summary</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=debt_snowball_calculator" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">
                        <span class="sidebar-text">Debt Snowball Calculator</span>
                    </a>
                </div>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>?page=profile" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                <i class="fas fa-user"></i>
                <span class="sidebar-text ml-3">Profile</span>
            </a>
            <?php if ($role == 1): ?>
                <a href="<?php echo BASE_URL; ?>?page=users" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                    <i class="fas fa-users"></i>
                    <span class="sidebar-text ml-3">Users</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?page=settings" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                    <i class="fas fa-cogs"></i>
                    <span class="sidebar-text ml-3">Settings</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?page=admin_dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                    <i class="fas fa-shield-alt"></i>
                    <span class="sidebar-text ml-3">Admin Dashboard</span>
                </a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>?page=logout" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text ml-3">Logout</span>
            </a>
        </nav>
        <div class="absolute bottom-0 p-4 flex items-center space-x-2">
            <img class="w-10 h-10 rounded-full" src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>" alt="Profile Image">
            <div class="sidebar-text">
                <span>Hello!</span><br>
                <span><?php echo $user['name']; ?></span>
            </div>
        </div>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold"><?php echo $pageTitle; ?></h1>
            </div>
            <?php if ($user): ?>
                <div class="flex items-center">
                    <img class="w-10 h-10 rounded-full" src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>" alt="Profile Image">
                    <span class="ml-4"><?php echo $user['name']; ?></span>
                    <a href="<?php echo BASE_URL; ?>?page=logout" class="ml-4 text-red-600">Logout</a>
                </div>
            <?php endif; ?>
        </header>
        <main class="p-4 flex-1 overflow-y-auto">
