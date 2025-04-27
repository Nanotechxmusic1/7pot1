<?php
require_once __DIR__ . '/../../models/Withdrawal.php';
require_once __DIR__ . '/../../models/Recharge.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class WalletController {
    private $withdrawalModel;
    private $rechargeModel;
    private $userModel;

    public function __construct($pdo) {
        $this->withdrawalModel = new Withdrawal($pdo);
        $this->rechargeModel = new Recharge($pdo);
        $this->userModel = new User($pdo);
    }

    // Request withdrawal
    public function requestWithdrawal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('withdrawal.php');
            }

            $user_id = $_SESSION['user_id'];
            $amount = floatval($_POST['amount']);
            $upi_id = isset($_POST['upi_id']) ? sanitize_input($_POST['upi_id']) : null;
            $bank_details = isset($_POST['bank_details']) ? sanitize_input($_POST['bank_details']) : null;

            if ($amount < 800) {
                set_flash_message('error', 'Minimum withdrawal amount is ₹800.');
                redirect('withdrawal.php');
            }

            $user = $this->userModel->getUserById($user_id);
            if ($user['balance'] < $amount) {
                set_flash_message('error', 'Insufficient balance.');
                redirect('withdrawal.php');
            }

            // Deduct balance and create withdrawal request in transaction
            try {
                $this->userModel->pdo->beginTransaction();

                $stmt = $this->userModel->pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
                $stmt->execute([$amount, $user_id, $amount]);
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Insufficient balance.');
                }

                $this->withdrawalModel->createWithdrawal($user_id, $amount, $upi_id, $bank_details);

                $this->userModel->pdo->commit();

                set_flash_message('success', 'Withdrawal request submitted.');
                redirect('withdrawal.php');
            } catch (Exception $e) {
                $this->userModel->pdo->rollBack();
                set_flash_message('error', 'Failed to submit withdrawal request: ' . $e->getMessage());
                redirect('withdrawal.php');
            }
        }
    }

    // Request recharge
    public function requestRecharge() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('recharge.php');
            }

            $user_id = $_SESSION['user_id'];
            $amount = floatval($_POST['amount']);
            $utr = sanitize_input($_POST['utr']);
            $user_upi = isset($_POST['user_upi']) ? sanitize_input($_POST['user_upi']) : null;

            if ($amount < 200) {
                set_flash_message('error', 'Minimum recharge amount is ₹200.');
                redirect('recharge.php');
            }

            if (empty($utr)) {
                set_flash_message('error', 'UTR is required.');
                redirect('recharge.php');
            }

            $created = $this->rechargeModel->createRecharge($user_id, $amount, $utr, $user_upi);
            if ($created) {
                set_flash_message('success', 'Recharge request submitted. Awaiting verification.');
                redirect('recharge.php');
            } else {
                set_flash_message('error', 'Failed to submit recharge request.');
                redirect('recharge.php');
            }
        }
    }

    // View withdrawal history
    public function viewWithdrawals() {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }
        return $this->withdrawalModel->getWithdrawalsByUser($_SESSION['user_id']);
    }

    // View recharge history
    public function viewRecharges() {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }
        return $this->rechargeModel->getRechargesByUser($_SESSION['user_id']);
    }
}
?>
