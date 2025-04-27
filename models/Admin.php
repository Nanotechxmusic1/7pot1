<?php
require_once __DIR__ . '/../config/config.php';

class Admin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get admin by username
    public function getAdminByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // Verify admin password
    public function verifyPassword($username, $password) {
        $admin = $this->getAdminByUsername($username);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }
}
?>
