<?php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getUserByUsernameOrEmail($usernameOrEmail) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND deleted = 0");
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function userExists($username, $email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND deleted = 0");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function addUser($username, $email, $password, $name, $phone, $roleId = 3) {
        $verificationToken = $this->generateVerificationToken();
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role_id, email_verified, verification_token, created_at, updated_at, name, phone) VALUES (?, ?, ?, ?, 0, ?, NOW(), NOW(), ?, ?)");
        $stmt->bind_param("sssssss", $username, $email, $password, $roleId, $verificationToken, $name, $phone);
        if ($stmt->execute()) {
            $this->sendVerificationEmail($email, $verificationToken);
            return true;
        } else {
            return false;
        }
    }

    public function generateVerificationToken() {
        return bin2hex(random_bytes(16));
    }

    public function sendVerificationEmail($email, $token) {
        $subject = "Verify your email address";
        $message = "Please click the link below to verify your email address: " . BASE_URL . "?page=verify_email&token=" . $token;
        $headers = "From: Graphic Surface <no-reply@graphicsurface.com>\r\n";
        $headers .= "Reply-To: no-reply@graphicsurface.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($email, $subject, $message, $headers);
    }

    public function verifyEmail($token) {
        $stmt = $this->db->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateUser($userId, $data) {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, role_id = ?, email_verified = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $data['name'], $data['email'], $data['role_id'], $data['email_verified'], $userId);
        return $stmt->execute();
    }

    public function softDeleteUser($userId) {
        $stmt = $this->db->prepare("UPDATE users SET deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function softDeleteRelatedRecords($userId) {
        $tables = ['debt_snowball', 'categories', 'incomes', 'expenses', 'notes'];

        foreach ($tables as $table) {
            $stmt = $this->db->prepare("UPDATE $table SET deleted = 1, deleted_at = NOW() WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
    }

    public function getAllUsers() {
        $result = $this->db->query("SELECT * FROM users WHERE deleted = 0");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserMetaById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user_meta WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateUserMeta($userId, $phone, $address, $photoPath) {
        $stmt = $this->db->prepare("UPDATE user_meta SET phone = ?, address = ?, photo_path = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $phone, $address, $photoPath, $userId);
        return $stmt->execute();
    }

    public function addUserMeta($userId, $phone, $address, $photoPath) {
        $stmt = $this->db->prepare("INSERT INTO user_meta (user_id, phone, address, photo_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $phone, $address, $photoPath);
        return $stmt->execute();
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        return $stmt->execute();
    }
}
?>
