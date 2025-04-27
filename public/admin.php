<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/admin/AuthController.php';
require_once __DIR__ . '/../controllers/admin/DashboardController.php';
require_once __DIR__ . '/../controllers/admin/GameController.php';
require_once __DIR__ . '/../controllers/admin/ResultController.php';
require_once __DIR__ . '/../controllers/admin/UserController.php';
require_once __DIR__ . '/../controllers/admin/WithdrawalController.php';
require_once __DIR__ . '/../controllers/admin/RechargeController.php';

session_start();

$action = $_GET['action'] ?? 'dashboard';

$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);
$gameController = new GameController($pdo);
$resultController = new ResultController($pdo);
$userController = new UserController($pdo);
$withdrawalController = new WithdrawalController($pdo);
$rechargeController = new RechargeController($pdo);

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            include __DIR__ . '/../views/admin/auth/login.php';
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        $data = $dashboardController->index();
        $totalBets = $data['totalBets'];
        $totalPayouts = $data['totalPayouts'];
        include __DIR__ . '/../views/admin/dashboard.php';
        break;

    case 'games':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $gameController->addGame();
            } elseif (isset($_POST['edit'])) {
                $gameController->editGame();
            } elseif (isset($_POST['set_status'])) {
                $gameController->setGameStatus();
            }
        } else {
            $games = $gameController->listGames();
            include __DIR__ . '/../views/admin/games.php';
        }
        break;

    case 'declare_result':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultController->declareResult();
        } else {
            $results = $resultController->listResults();
            include __DIR__ . '/../views/admin/declare_result.php';
        }
        break;

    case 'users':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_status'])) {
                $userController->updateUserStatus();
            } elseif (isset($_POST['adjust_balance'])) {
                $userController->adjustBalance();
            }
        } else {
            $users = $userController->listUsers();
            include __DIR__ . '/../views/admin/users.php';
        }
        break;

    case 'withdrawals':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $withdrawalController->updateWithdrawalStatus();
        } else {
            $withdrawals = $withdrawalController->listWithdrawals();
            include __DIR__ . '/../views/admin/withdrawals.php';
        }
        break;

    case 'recharges':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rechargeController->updateRechargeStatus();
        } else {
            $recharges = $rechargeController->listRecharges();
            include __DIR__ . '/../views/admin/recharges.php';
        }
        break;

    case 'reports':
        // Reports implementation can be added here
        include __DIR__ . '/../views/admin/reports.php';
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found';
        break;
}
?>
