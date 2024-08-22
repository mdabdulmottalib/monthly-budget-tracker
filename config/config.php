<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/EnvLoader.php';
EnvLoader::load($_SERVER['DOCUMENT_ROOT'] . '/.env');

if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', getenv('APP_ENV') === 'production' ? 'https://nurulitpoint.xyz/public/' : 'http://localhost/public/');
}
if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST'));
}
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME'));
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER'));
}
if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS'));
}
if (!defined('APP_NAME')) {
    define('APP_NAME', getenv('APP_NAME'));
}

?>
