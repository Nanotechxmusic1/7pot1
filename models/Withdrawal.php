<?php
require_once __DIR__ . '/../config/config.php';

class Withdrawal {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new withdrawal request
    public function createWithdrawal($user_id, $amount, $upi_id = null, $bank_details = null) {
        $stmt = $this->pdo->prepare("INSERT INTO withdrawals (user_id, amount, upi_id, bank_details) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $amount, $upi_id, $bank_details]);
    }

    // Get withdrawals by user
    public function getWithdrawalsByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Get all withdrawals (for admin)
    public function getAllWithdrawals() {
        $stmt = $this->pdo->query("SELECT w.*, u.name as user_name FROM withdrawals w JOIN users u ON w.user_id = u.id ORDER BY w.created_at DESC");
        return $stmt->fetchAll();
    }

    // Update withdrawal status
    public function updateStatus($withdrawal_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE withdrawals SET status = ?, processed_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $withdrawal_id]);
    }
}
?>
