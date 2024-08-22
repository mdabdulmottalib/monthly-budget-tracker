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
    <title><?php echo $pageTitle; ?>  - <?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <style>
      @media only screen and (min-width: 768px) {
        aside h2 {
          display: none;
          opacity: 0;
          transition: opacity 0.3s ease;
        }

        aside:hover {
          width: 14rem;
        }

        aside:hover h2 {
          display: block;
          opacity: 1;
        }
        aside:hover .nav-ber {
          justify-content: start;
        }
        aside:hover .curryency {
          display: flex;
        }
      }
    </style>
  </head>
  <body class="bg-[#F2F2F2]">
    <div class="w-full flex flex-col">
      <!-- Header -->
      <header class="sticky top-0 left-0 right-0 h-16 z-50 bg-white shadow-md">
        <div class="w-full py-2 border-b-2 h-full flex items-center">
          <div class="w-11/12 mx-auto flex justify-between">
            <div class="relative flex items-center">
              <div class="md:hidden">
                <button
                  class="border text-2xl px-2 rounded font-bold menu-button"
                >
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
                <img
                  class="w-10 h-10 rounded-full object-cover object-center"
                  src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>"
                  alt="Profile Image"
                />
              </div>
            </div>
          </div>
        </div>
      </header>
      <?php
      include 'aside.php';
      ?>
      
