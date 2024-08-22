<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/EnvLoader.php';
EnvLoader::load($_SERVER['DOCUMENT_ROOT'] . '/.env');

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

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}
?>
