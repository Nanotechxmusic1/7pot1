<?php
// functions.php - Common utility functions

// Sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Password hashing using bcrypt
function password_hash_secure($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Password verify
function password_verify_secure($password, $hash) {
    return password_verify($password, $hash);
}

// Flash messages for user feedback
function set_flash_message($type, $message) {
    $_SESSION['flash'][$type][] = $message;
}

function display_flash_messages() {
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $type => $messages) {
            foreach ($messages as $message) {
                echo "<div class='flash-message {$type}'>{$message}</div>";
            }
        }
        unset($_SESSION['flash']);
    }
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}
?>
