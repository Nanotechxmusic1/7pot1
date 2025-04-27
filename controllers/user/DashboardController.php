<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Game.php';

class DashboardController {
    private $userModel;
    private $gameModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
        $this->gameModel = new Game($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $games = $this->gameModel->getActiveGames();

        return ['user' => $user, 'games' => $games];
    }
}
?>
