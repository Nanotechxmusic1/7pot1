<?php
require_once __DIR__ . '/../../models/Game.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class GameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new Game($pdo);
    }

    // List all games
    public function listGames() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin_login.php');
        }
        return $this->gameModel->getAllGames();
    }

    // Add new game
    public function addGame() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('games.php');
            }

            $name = sanitize_input($_POST['name']);
            $min_bet = floatval($_POST['min_bet']);
            $numbers = intval($_POST['numbers']);
            $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'inactive';

            if (!$name || $min_bet <= 0 || !in_array($numbers, [10, 100])) {
                set_flash_message('error', 'Invalid input.');
                redirect('games.php');
            }

            $added = $this->gameModel->addGame($name, $min_bet, $numbers, $status);
            if ($added) {
                set_flash_message('success', 'Game added successfully.');
            } else {
                set_flash_message('error', 'Failed to add game.');
            }
            redirect('games.php');
        }
    }

    // Edit game
    public function editGame() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('games.php');
            }

            $id = intval($_POST['id']);
            $name = sanitize_input($_POST['name']);
            $min_bet = floatval($_POST['min_bet']);
            $numbers = intval($_POST['numbers']);
            $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'inactive';

            if (!$id || !$name || $min_bet <= 0 || !in_array($numbers, [10, 100])) {
                set_flash_message('error', 'Invalid input.');
                redirect('games.php');
            }

            $updated = $this->gameModel->updateGame($id, $name, $min_bet, $numbers, $status);
            if ($updated) {
                set_flash_message('success', 'Game updated successfully.');
            } else {
                set_flash_message('error', 'Failed to update game.');
            }
            redirect('games.php');
        }
    }

    // Enable or disable game
    public function setGameStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('games.php');
            }

            $id = intval($_POST['id']);
            $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'inactive';

            if (!$id) {
                set_flash_message('error', 'Invalid game ID.');
                redirect('games.php');
            }

            $updated = $this->gameModel->setGameStatus($id, $status);
            if ($updated) {
                set_flash_message('success', 'Game status updated.');
            } else {
                set_flash_message('error', 'Failed to update game status.');
            }
            redirect('games.php');
        }
    }
}
?>
