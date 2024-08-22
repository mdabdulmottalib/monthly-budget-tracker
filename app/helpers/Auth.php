<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/helpers/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public static function getUserRole() {
        return $_SESSION['user']['role_id'] ?? null;
    }

    public static function checkRole($roles = []) {
        if (!self::isLoggedIn() && !self::isPublicPage()) {
            header("Location: " . BASE_URL . "?page=login");
            exit;
        }

        $userRole = self::getUserRole();
        if (!in_array($userRole, $roles) && !self::isPublicPage()) {
            header("Location: " . BASE_URL . "?page=403");
            exit;
        }
    }

    public static function hasActiveSubscription() {
        if (!self::isLoggedIn()) return false;
        $userId = $_SESSION['user']['id'];
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status = 'active' AND end_date >= CURDATE()");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public static function checkSubscription() {
        if (!self::hasActiveSubscription() && !self::isPublicPage()) {
            header("Location: " . BASE_URL . "no_subscription.php");
            exit;
        }
    }

    public static function loginAsUser($userId) {
        // Save the current admin user ID to return back later
        $_SESSION['original_user_id'] = $_SESSION['user']['id'];
        // Load the new user data
        $userModel = new User();
        $user = $userModel->getUserById($userId);
        $_SESSION['user'] = $user;
    }

    public static function returnToAdmin() {
        if (isset($_SESSION['original_user_id'])) {
            $userModel = new User();
            $user = $userModel->getUserById($_SESSION['original_user_id']);
            $_SESSION['user'] = $user;
            unset($_SESSION['original_user_id']);
        }
    }

    public static function isPublicPage() {
        $publicPages = ['login', 'register', 'verify_email', '404', 'no_subscription'];
        $currentPage = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
        return in_array($currentPage, $publicPages);
    }
}
?>
