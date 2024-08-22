<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: " . BASE_URL . "?page=login");
exit;
?>
