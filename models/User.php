<?php
require_once __DIR__ . '/../config/config.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Register new user
    public function register($name, $mobile, $email, $password, $referral_code = null) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $referrer_id = null;

        if ($referral_code) {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE referral_code = ?");
            $stmt->execute([$referral_code]);
            $referrer = $stmt->fetch();
            if ($referrer) {
                $referrer_id = $referrer['id'];
            }
        }

        // Generate unique referral code for new user
        $new_referral_code = $this->generateReferralCode();

        $stmt = $this->pdo->prepare("INSERT INTO users (name, mobile, email, password, referral_code, referrer_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $mobile, $email, $hashed_password, $new_referral_code, $referrer_id]);
    }

    // Generate unique referral code
    private function generateReferralCode($length = 8) {
        do {
            $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE referral_code = ?");
            $stmt->execute([$code]);
            $exists = $stmt->fetch();
        } while ($exists);
        return $code;
    }

    // Get user by email
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Get user by mobile
    public function getUserByMobile($mobile) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE mobile = ?");
        $stmt->execute([$mobile]);
        return $stmt->fetch();
    }

    // Get user by ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update user balance (increment or decrement)
    public function updateBalance($user_id, $amount) {
        $stmt = $this->pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        return $stmt->execute([$amount, $user_id]);
    }

    // Verify password
    public function verifyPassword($email, $password) {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Block or unblock user
    public function updateStatus($user_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $user_id]);
    }

    // Get all users (for admin)
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
?>
