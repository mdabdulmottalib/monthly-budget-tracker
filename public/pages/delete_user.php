<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Auth.php';

// Ensure only admins can access this page
Auth::checkRole([1]);

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $userModel = new User();

    try {
        // Soft delete related records
        $userModel->softDeleteRelatedRecords($userId);

        // Soft delete the user
        $userModel->softDeleteUser($userId);

        // Redirect back to the users management page
        header('Location: ' . BASE_URL . '?page=users');
        exit;
    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
} else {
    // If no user ID is provided, redirect back to the users management page
    header('Location: ' . BASE_URL . '?page=users');
    exit;
}
?>
