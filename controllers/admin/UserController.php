<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // List all users
    public function listUsers() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin_login.php');
        }
        return $this->userModel->getAllUsers();
    }

    // Block or unblock user
    public function updateUserStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('users.php');
            }

            $user_id = intval($_POST['user_id']);
            $status = in_array($_POST['status'], ['active', 'blocked']) ? $_POST['status'] : 'active';

            if (!$user_id) {
                set_flash_message('error', 'Invalid user ID.');
                redirect('users.php');
            }

            $updated = $this->userModel->updateStatus($user_id, $status);
            if ($updated) {
                set_flash_message('success', 'User status updated.');
            } else {
                set_flash_message('error', 'Failed to update user status.');
            }
            redirect('users.php');
        }
    }

    // Adjust user balance
    public function adjustBalance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('users.php');
            }

            $user_id = intval($_POST['user_id']);
            $amount = floatval($_POST['amount']);

            if (!$user_id || $amount == 0) {
                set_flash_message('error', 'Invalid input.');
                redirect('users.php');
            }

            $updated = $this->userModel->updateBalance($user_id, $amount);
            if ($updated) {
                set_flash_message('success', 'User balance adjusted.');
            } else {
                set_flash_message('error', 'Failed to adjust balance.');
            }
            redirect('users.php');
        }
    }
}
?>
