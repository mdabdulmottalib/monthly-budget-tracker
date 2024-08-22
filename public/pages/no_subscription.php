<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../public/layouts/header.php';
?>

<h1 class="text-2xl font-semibold mb-6">Subscription Required</h1>
<p>You need an active subscription to access this page. Please subscribe to continue.</p>

<a href="<?php echo BASE_URL; ?>?page=subscription" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Subscribe Now</a>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../public/layouts/footer.php';
?>
