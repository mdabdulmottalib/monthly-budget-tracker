<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Database.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Auth.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/web.php';

    $page = $_GET['page'] ?? 'dashboard';
    if (!isset($routes[$page])) {
        $page = '404';
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/public/pages/' . $routes[$page];
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
