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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo APP_NAME; ?> - <?php echo $pageTitle; ?></title>
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn-uicons.flaticon.com/2.5.1/uicons-bold-rounded/css/uicons-bold-rounded.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .sidebar-expanded {
        width: 16rem;
        padding: 0 15px;
      }
      .sidebar-collapsed {
        width: 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        /* padding: 0 !important; */
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
        background-color: #4a5568; /* gray-600 */
        color: #ffffff !important; /* Force text to be white */
      }
      .MainNav {
        display: flex;
        align-items: center;
      }
    </style>
  </head>
  <body class="bg-gray-100 flex h-screen">
    <aside
      id="sidebar"
      class="sidebar-expanded bg-white border-r text-white h-full transition-all duration-300"
    >
      <div class="flex justify-between items-center p-4">
        <a
          href="<?php echo BASE_URL; ?>"
          class="text-lg font-bold sidebar-text text-gray-600 navListHeading"
          ><?php echo APP_NAME; ?></a
        >
        <button
          onclick="toggleSidebar()"
          class="text-white focus:outline-none bg-gray-700 size-8 flex items-center justify-center rounded-full"
        >
          <i class="fas fa-arrow-left" id="toggleIcon"></i>
        </button>
      </div>
      <nav class="mt-10 flex flex-col main-nav-menu">
        <a
          href="<?php echo BASE_URL; ?>?page=dashboard"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center text-gray-600 <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>"
        >
          <i class="fas fa-tachometer-alt"></i>
          <span class="sidebar-text ml-3 listen">Dashboard</span>
        </a>
        <div
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item text-gray-600"
        >
          <i class="fas fa-dollar-sign"></i>
          <span class="sidebar-text ml-3 listen">Income</span>
          <i class="fas fa-chevron-down ml-auto listen"></i>
        </div>
        <div id="income-submenu" class="submenu ml-10 mt-2">
          <a
            href="<?php echo BASE_URL; ?>?page=add_income"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'add_income' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Add Income</span>
          </a>
          <a
            href="<?php echo BASE_URL; ?>?page=income_summary"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'income_summary' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Income History</span>
          </a>
        </div>
        <div
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item text-gray-600"
        >
          <i class="fas fa-receipt"></i>
          <span class="sidebar-text ml-3 listen">Expenses</span>
          <i class="fas fa-chevron-down ml-auto listen"></i>
        </div>
        <div id="expenses-submenu" class="submenu ml-10 mt-2">
          <a
            href="<?php echo BASE_URL; ?>?page=add_expense"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'add_expense' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Add Expenses</span>
          </a>
          <a
            href="<?php echo BASE_URL; ?>?page=expense_summary"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'expense_summary' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Expenses History</span>
          </a>
        </div>
        <div
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item text-gray-600"
        >
          <i class="fas fa-sticky-note"></i>
          <span class="sidebar-text ml-3 listen">Notes</span>
          <i class="fas fa-chevron-down ml-auto listen"></i>
        </div>
        <div id="notes-submenu" class="submenu ml-10 mt-2">
          <a
            href="<?php echo BASE_URL; ?>?page=add_note"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'add_note' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Add Note</span>
          </a>
          <a
            href="<?php echo BASE_URL; ?>?page=note_summary"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'note_summary' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Note History</span>
          </a>
        </div>
        <div
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center cursor-pointer menu-item text-gray-600"
        >
          <i class="fas fa-calculator"></i>
          <span class="sidebar-text ml-3 listen">Debt</span>
          <i class="fas fa-chevron-down ml-auto listen"></i>
        </div>
        <div id="debt-submenu" class="submenu ml-10 mt-2">
          <a
            href="<?php echo BASE_URL; ?>?page=add_debt"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'add_debt' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Add Debt</span>
          </a>
          <a
            href="<?php echo BASE_URL; ?>?page=debt_snowball_summary"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'debt_snowball_summary' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Debt Snowball Summary</span>
          </a>
          <a
            href="<?php echo BASE_URL; ?>?page=debt_snowball_calculator"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white text-gray-600 <?php echo $currentPage == 'debt_snowball_calculator' ? 'active' : ''; ?>"
          >
            <span class="sidebar-text listen">Debt Snowball Calculator</span>
          </a>
        </div>
        <a
          href="<?php echo BASE_URL; ?>?page=profile"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center text-gray-600 <?php echo $currentPage == 'profile' ? 'active' : ''; ?>"
        >
          <i class="fas fa-user"></i>
          <span class="sidebar-text ml-3 listen">Profile</span>
        </a>
        <?php if ($role == 1): ?>
        <a
          href="<?php echo BASE_URL; ?>?page=users"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center text-gray-600 <?php echo $currentPage == 'users' ? 'active' : ''; ?>"
        >
          <i class="fas fa-users"></i>
          <span class="sidebar-text ml-3 listen">Users</span>
        </a>
        <a
          href="<?php echo BASE_URL; ?>?page=settings"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center text-gray-600 <?php echo $currentPage == 'settings' ? 'active' : ''; ?>"
        >
          <i class="fas fa-cogs"></i>
          <span class="sidebar-text ml-3 listen">Settings</span>
        </a>
        <?php endif; ?>
        <a
          href="<?php echo BASE_URL; ?>?page=logout"
          class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white flex items-center text-gray-600"
        >
          <i class="fas fa-sign-out-alt"></i>
          <span class="sidebar-text ml-3 listen">Logout</span>
        </a>
      </nav>
      <div class="absolute bottom-0 py-4 flex items-center space-x-2">
        <img
          class="w-10 h-10 rounded-full border"
          src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>"
          alt="Profile Image"
        />
        <div class="sidebar-text text-gray-600">
          <span>Hello!</span><br />
          <span><?php echo $user['name']; ?></span>
        </div>
      </div>
    </aside>
    <div class="flex-1 flex flex-col">
      <!-- header.php -->
<header class="sticky top-0 left-0 right-0 h-16 z-50 bg-white shadow-md">
    <div class="w-full py-2 border-b-2 h-full flex items-center">
        <div class="w-11/12 mx-auto flex justify-between">
            <div class="relative flex items-center">
                <div class="md:hidden">
                    <button class="border text-2xl px-2 rounded font-bold menu-button">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="absolute top-2.5 left-12 text-[#666666]">
                    <h2 id="show-month"></h2>
                </div>
            </div>
            <div class="flex items-center gap-5">
                <button aria-label="Notifications" class="text-gray-600">
                    <i class="fa-solid fa-bell"></i>
                </button>
                <button aria-label="Settings" class="text-gray-600">
                    <i class="fa-solid fa-gear"></i>
                </button>
                <div>
                    <img class="w-10 h-10 rounded-full object-cover object-center"
                         src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>"
                         alt="Profile Image"/>
                </div>
            </div>
        </div>
    </div>
</header>

      <main class="p-4 flex-1 overflow-y-auto">