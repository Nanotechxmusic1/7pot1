<?php
require_once __DIR__ . '/../config/config.php';

class Recharge {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new recharge request
    public function createRecharge($user_id, $amount, $utr, $user_upi = null) {
        $stmt = $this->pdo->prepare("INSERT INTO recharges (user_id, amount, utr, user_upi) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $amount, $utr, $user_upi]);
    }

    // Get recharges by user
    public function getRechargesByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM recharges WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Get all recharges (for admin)
    public function getAllRecharges() {
        $stmt = $this->pdo->query("SELECT r.*, u.name as user_name FROM recharges r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
        return $stmt->fetchAll();
    }

    // Update recharge status
    public function updateStatus($recharge_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE recharges SET status = ?, processed_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $recharge_id]);
    }
}
?>
