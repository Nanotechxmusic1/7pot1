<?php
require_once __DIR__ . '/../../models/Recharge.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class RechargeController {
    private $rechargeModel;

    public function __construct($pdo) {
        $this->rechargeModel = new Recharge($pdo);
    }

    // List all recharges
    public function listRecharges() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin_login.php');
        }
        return $this->rechargeModel->getAllRecharges();
    }

    // Approve or reject recharge
    public function updateRechargeStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('recharges.php');
            }

            $recharge_id = intval($_POST['recharge_id']);
            $status = in_array($_POST['status'], ['approved', 'rejected']) ? $_POST['status'] : 'pending';

            if (!$recharge_id) {
                set_flash_message('error', 'Invalid recharge ID.');
                redirect('recharges.php');
            }

            $updated = $this->rechargeModel->updateStatus($recharge_id, $status);
            if ($updated) {
                set_flash_message('success', 'Recharge status updated.');
            } else {
                set_flash_message('error', 'Failed to update recharge status.');
            }
            redirect('recharges.php');
        }
    }
}
?>
