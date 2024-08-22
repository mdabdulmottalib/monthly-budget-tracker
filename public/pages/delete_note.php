<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/public/layouts/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/Note.php';

Auth::checkRole([1, 2]); // Allow Admin and Manager roles
Auth::checkSubscription(); // Check if user has an active subscription

$noteModel = new Note();
$noteId = $_GET['id'];

$noteModel->deleteNote($noteId);
header("Location: " . BASE_URL . "?page=note_summary");
exit;
?>
