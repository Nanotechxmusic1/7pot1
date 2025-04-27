<?php
require_once __DIR__ . '/../../models/Withdrawal.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class WithdrawalController {
    private $withdrawalModel;

    public function __construct($pdo) {
        $this->withdrawalModel = new Withdrawal($pdo);
    }

    // List all withdrawals
    public function listWithdrawals() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin_login.php');
        }
        return $this->withdrawalModel->getAllWithdrawals();
    }

    // Approve or reject withdrawal
    public function updateWithdrawalStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('withdrawals.php');
            }

            $withdrawal_id = intval($_POST['withdrawal_id']);
            $status = in_array($_POST['status'], ['approved', 'rejected']) ? $_POST['status'] : 'pending';

            if (!$withdrawal_id) {
                set_flash_message('error', 'Invalid withdrawal ID.');
                redirect('withdrawals.php');
            }

            $updated = $this->withdrawalModel->updateStatus($withdrawal_id, $status);
            if ($updated) {
                set_flash_message('success', 'Withdrawal status updated.');
            } else {
                set_flash_message('error', 'Failed to update withdrawal status.');
            }
            redirect('withdrawals.php');
        }
    }
}
?>
