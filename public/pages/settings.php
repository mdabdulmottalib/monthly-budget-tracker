<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Auth.php';

// Restrict access to Admin only
Auth::checkRole([1]);

// Page content for settings
?>
<h1>Settings</h1>
<p>Only admins can see this page.</p>
