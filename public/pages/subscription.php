<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/SubscriptionPackage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Subscription.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Payment.php';

$packageModel = new SubscriptionPackage();
$subscriptionModel = new Subscription();
$paymentModel = new Payment();

$packages = $packageModel->getAllPackages();
$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = $_POST['package_id'];
    $paymentMethod = $_POST['payment_method'];
    $package = $packageModel->getPackageById($packageId);

    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime("+{$package['duration']} days"));

    if ($subscriptionModel->addSubscription($userId, $package['name'], $startDate, $endDate)) {
        $subscriptionId = $subscriptionModel->getActiveSubscription($userId)['id'];
        $paymentModel->addPayment($userId, $subscriptionId, $package['price'], $paymentMethod, 'completed');
        $message = "Subscription successful!";
    } else {
        $error = "Failed to subscribe. Please try again.";
    }
}
?>

<h1 class="text-2xl font-semibold mb-6">Subscription Packages</h1>

<?php if (isset($message)): ?>
    <p class="text-green-500 text-center mb-4"><?php echo $message; ?></p>
<?php elseif (isset($error)): ?>
    <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
        <label for="package_id" class="block text-gray-700 text-sm font-bold mb-2">Select Package:</label>
        <select name="package_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <?php foreach ($packages as $package): ?>
                <option value="<?php echo $package['id']; ?>"><?php echo $package['name'] . ' - $' . $package['price']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-4">
        <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
        <select name="payment_method" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select>
    </div>
    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Subscribe</button>
    </div>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
