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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Handle file upload
    $photoPath = $userMeta['photo_path'] ?? '';
    if (isset($_POST['cropped_image']) && !empty($_POST['cropped_image'])) {
        $croppedImage = $_POST['cropped_image'];
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
        $imageName = $user['username'] . date('dmYHis') . '.jpg';
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
        $targetFile = $targetDir . $imageName;
        file_put_contents($targetFile, $decodedImage);
        $photoPath = $imageName;
    }

    $userModel->updateUser($userId, $name);
    if ($userMeta) {
        $userModel->updateUserMeta($userId, $phone, $address, $photoPath);
    } else {
        $userModel->addUserMeta($userId, $phone, $address, $photoPath);
    }
    
    header("Location: " . BASE_URL . "?page=profile");
    exit;
}
?>

<h1 class="text-2xl font-semibold mb-6">Profile</h1>

<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden md:max-w-2xl">
    <div class="md:flex">
        <div class="w-full p-4">
            <div class="relative w-32 h-32 mx-auto mb-4">
                <img id="profileImage" class="w-full h-full object-cover rounded-full border-2 border-gray-300" src="<?php echo !empty($userMeta['photo_path']) ? '/uploads/' . $userMeta['photo_path'] : $defaultImage; ?>" alt="User Photo">
                <label for="photo" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-1 cursor-pointer">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <form method="POST" enctype="multipart/form-data" class="px-8 pt-6 pb-8 mb-4">
                <input type="file" name="photo" id="photo" class="hidden" accept="image/jpeg, image/png" onchange="loadImage(event)">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                        Phone
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="text" value="<?php echo $userMeta['phone'] ?? ''; ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Address
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" name="address"><?php echo $userMeta['address'] ?? ''; ?></textarea>
                </div>
                <input type="hidden" name="cropped_image" id="cropped_image">
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for cropping image -->
<div id="cropModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Crop Image</h2>
            <button onclick="closeCropModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <div class="w-96 h-96">
            <img id="cropperImage" class="max-w-full max-h-full" src="">
        </div>
        <div class="flex justify-end mt-4">
            <button onclick="cropImage()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Crop</button>
        </div>
    </div>
</div>

<script>
let cropper;
const loadImage = (event) => {
    const image = document.getElementById('cropperImage');
    image.src = URL.createObjectURL(event.target.files[0]);
    document.getElementById('cropModal').classList.remove('hidden');
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 2,
        autoCropArea: 1,
        responsive: true,
        zoomable: true,
    });
};

const closeCropModal = () => {
    cropper.destroy();
    document.getElementById('cropModal').classList.add('hidden');
};

const cropImage = () => {
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
    });
    const croppedImageInput = document.getElementById('cropped_image');
    canvas.toBlob((blob) => {
        const reader = new FileReader();
        reader.readAsDataURL(blob); 
        reader.onloadend = function() {
            const base64data = reader.result;                
            croppedImageInput.value = base64data;
            document.getElementById('profileImage').src = base64data;
            closeCropModal();
        }
    }, 'image/jpeg', 0.9);
};

document.getElementById('profileImage').onclick = function() {
    document.getElementById('photo').click();
};
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>

<!-- In your header.php or profile.php -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
