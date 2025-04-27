<?php
require_once __DIR__ . '/../../models/Admin.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class AuthController {
    private $adminModel;

    public function __construct($pdo) {
        $this->adminModel = new Admin($pdo);
    }

    // Admin login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('admin_login.php');
            }

            $username = sanitize_input($_POST['username']);
            $password = $_POST['password'];

            if (!$username || !$password) {
                set_flash_message('error', 'Username and password are required.');
                redirect('admin_login.php');
            }

            $admin = $this->adminModel->verifyPassword($username, $password);
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                set_flash_message('success', 'Admin login successful.');
                redirect('admin_dashboard.php');
            } else {
                set_flash_message('error', 'Invalid username or password.');
                redirect('admin_login.php');
            }
        }
    }

    // Admin logout
    public function logout() {
        session_destroy();
        redirect('admin_login.php');
    }
}
?>
