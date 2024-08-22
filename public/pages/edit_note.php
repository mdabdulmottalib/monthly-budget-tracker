<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Note.php';

Auth::checkRole([1, 2]); // Allow Admin and Manager roles
Auth::checkSubscription(); // Check if user has an active subscription

$noteModel = new Note();
$noteId = $_GET['id'];
$note = $noteModel->getNoteById($noteId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $noteModel->updateNote($noteId, $content);
    header("Location: " . BASE_URL . "?page=note_summary");
    exit;
}
?>

<h1 class="text-2xl font-semibold mb-6">Edit Note</h1>

<form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
    <textarea name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo $note['content']; ?></textarea>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Update Note</button>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/footer.php';
?>
