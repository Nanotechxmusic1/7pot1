<?php
// config.php - Database connection and session start

session_start();

// Database Configuration for 7potgames.winx8.site
define('DB_HOST', 'localhost');
define('DB_NAME', 'wzoirlup_7potgames');
define('DB_USER', 'wzoirlup_7potgames');
define('DB_PASS', 'Raman@10');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Set secure session cookie parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
ini_set('session.use_strict_mode', 1);
?>
