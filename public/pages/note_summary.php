<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Note.php';

Auth::checkRole([1, 2, 3]); // Allow Admin, Manager, and User roles
Auth::checkSubscription(); // Check if user has an active subscription

$noteModel = new Note();
$userId = $_SESSION['user']['id'];
$notes = $noteModel->getAllNotes($userId);
?>

<h1 class="text-2xl font-semibold mb-6">Notes</h1>

<a href="<?php echo BASE_URL; ?>?page=add_note" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-6 inline-block">Add Note</a>

<table class="min-w-full bg-white shadow-md rounded my-6">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">#</th>
            <th class="w-6/12 py-3 px-4 uppercase font-semibold text-sm">Content</th>
            <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Created At</th>
            <th class="w-2/12 py-3 px-4 uppercase font-semibold text-sm">Updated At</th>
            <th class="w-1/12 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
        </tr>
    </thead>
    <tbody class="text-gray-700">
        <?php $serial = 1; ?>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td class="py-3 px-4"><?php echo $serial++; ?></td>
                <td class="py-3 px-4"><?php echo $note['content']; ?></td>
                <td class="py-3 px-4"><?php echo date('Y-m-d H:i:s', strtotime($note['created_at'])); ?></td>
                <td class="py-3 px-4"><?php echo date('Y-m-d H:i:s', strtotime($note['updated_at'])); ?></td>
                <td class="py-3 px-4">
                    <a href="<?php echo BASE_URL; ?>?page=edit_note&id=<?php echo $note['id']; ?>" class="text-blue-500 hover:underline"><i class="fas fa-edit"></i></a>
                    <a href="<?php echo BASE_URL; ?>?page=delete_note&id=<?php echo $note['id']; ?>" class="text-red-500 hover:underline"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
