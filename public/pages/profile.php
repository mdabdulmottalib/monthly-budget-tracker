<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$userModel = new User();
$userId = $_SESSION['user']['id'];
$user = $userModel->getUserById($userId);
$userMeta = $userModel->getUserMetaById($userId);

$defaultImage = "https://static.vecteezy.com/system/resources/thumbnails/004/899/680/small/beautiful-blonde-woman-with-makeup-avatar-for-a-beauty-salon-illustration-in-the-cartoon-style-vector.jpg";
?>

<h1 class="text-2xl font-semibold mb-6">Profile</h1>

<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden md:max-w-2xl">
    <div class="md:flex">
        <div class="w-full p-4">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <img id="profileImage" class="w-full h-full object-cover rounded-full border-2 border-gray-300" src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>" alt="User Photo">
            </div>
            <div class="mb-4">
                <span class="block text-gray-700 text-sm font-bold mb-2">Name:</span>
                <p class="text-gray-700"><?php echo $user['name']; ?></p>
            </div>
            <div class="mb-4">
                <span class="block text-gray-700 text-sm font-bold mb-2">Email:</span>
                <p class="text-gray-700"><?php echo $user['email']; ?></p>
            </div>
            <div class="mb-4">
                <span class="block text-gray-700 text-sm font-bold mb-2">Phone:</span>
                <p class="text-gray-700"><?php echo $userMeta['phone'] ?? ''; ?></p>
            </div>
            <div class="mb-4">
                <span class="block text-gray-700 text-sm font-bold mb-2">Address:</span>
                <p class="text-gray-700"><?php echo nl2br($userMeta['address'] ?? ''); ?></p>
            </div>
            <div class="text-right">
                <a href="<?php echo BASE_URL; ?>?page=edit_profile" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
