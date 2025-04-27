<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/user/AuthController.php';
require_once __DIR__ . '/../controllers/user/DashboardController.php';
require_once __DIR__ . '/../controllers/user/BetController.php';
require_once __DIR__ . '/../controllers/user/ResultController.php';
require_once __DIR__ . '/../controllers/user/WalletController.php';

session_start();

$action = $_GET['action'] ?? 'dashboard';

$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);
$betController = new BetController($pdo);
$resultController = new ResultController($pdo);
$walletController = new WalletController($pdo);

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            include __DIR__ . '/../views/user/auth/login.php';
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            include __DIR__ . '/../views/user/auth/register.php';
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        $data = $dashboardController->index();
        $user = $data['user'];
        $games = $data['games'];
        include __DIR__ . '/../views/user/dashboard.php';
        break;

    case 'bet':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $betController->placeBet();
        } else {
            $game_id = intval($_GET['game_id'] ?? 0);
            $data = $betController->showBetPage($game_id);
            $game = $data['game'];
            $user = $data['user'];
            include __DIR__ . '/../views/user/bet.php';
        }
        break;

    case 'results':
        $recentResults = $resultController->getRecentResults();
        include __DIR__ . '/../views/user/results.php';
        break;

    case 'withdrawal':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $walletController->requestWithdrawal();
        } else {
            $withdrawals = $walletController->viewWithdrawals();
            include __DIR__ . '/../views/user/withdrawal.php';
        }
        break;

    case 'recharge':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $walletController->requestRecharge();
        } else {
            $recharges = $walletController->viewRecharges();
            include __DIR__ . '/../views/user/recharge.php';
        }
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found';
        break;
}
?>
