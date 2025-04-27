<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // Registration
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('register.php');
            }

            $name = sanitize_input($_POST['name']);
            $mobile = sanitize_input($_POST['mobile']);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $referral_code = isset($_POST['referral_code']) ? sanitize_input($_POST['referral_code']) : null;

            if (!$name || !$mobile || !$email || !$password || !$confirm_password) {
                set_flash_message('error', 'All fields are required.');
                redirect('register.php');
            }

            if ($password !== $confirm_password) {
                set_flash_message('error', 'Passwords do not match.');
                redirect('register.php');
            }

            // Check if email or mobile already exists
            if ($this->userModel->getUserByEmail($email)) {
                set_flash_message('error', 'Email already registered.');
                redirect('register.php');
            }
            if ($this->userModel->getUserByMobile($mobile)) {
                set_flash_message('error', 'Mobile number already registered.');
                redirect('register.php');
            }

            $registered = $this->userModel->register($name, $mobile, $email, $password, $referral_code);
            if ($registered) {
                set_flash_message('success', 'Registration successful. Please login.');
                redirect('login.php');
            } else {
                set_flash_message('error', 'Registration failed. Please try again.');
                redirect('register.php');
            }
        }
    }

    // Login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('login.php');
            }

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            if (!$email || !$password) {
                set_flash_message('error', 'Email and password are required.');
                redirect('login.php');
            }

            $user = $this->userModel->verifyPassword($email, $password);
            if ($user) {
                if ($user['status'] !== 'active') {
                    set_flash_message('error', 'Your account is blocked.');
                    redirect('login.php');
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                set_flash_message('success', 'Login successful.');
                redirect('dashboard.php');
            } else {
                set_flash_message('error', 'Invalid email or password.');
                redirect('login.php');
            }
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        redirect('login.php');
    }
}
?>
