<?php
require_once __DIR__ . '/../../models/Bet.php';
require_once __DIR__ . '/../../models/Game.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class BetController {
    private $betModel;
    private $gameModel;
    private $userModel;

    public function __construct($pdo) {
        $this->betModel = new Bet($pdo);
        $this->gameModel = new Game($pdo);
        $this->userModel = new User($pdo);
    }

    // Show betting page with game and user balance
    public function showBetPage($game_id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }

        $game = $this->gameModel->getGameById($game_id);
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        return ['game' => $game, 'user' => $user];
    }

    // Place bet
    public function placeBet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('bet.php?game_id=' . $_POST['game_id']);
            }

            $user_id = $_SESSION['user_id'];
            $game_id = intval($_POST['game_id']);
            $bet_number = intval($_POST['bet_number']);
            $amount = floatval($_POST['amount']);

            $game = $this->gameModel->getGameById($game_id);
            $user = $this->userModel->getUserById($user_id);

            if (!$game || $game['status'] !== 'active') {
                set_flash_message('error', 'Invalid or inactive game.');
                redirect('bet.php?game_id=' . $game_id);
            }

            if ($amount < $game['min_bet']) {
                set_flash_message('error', 'Bet amount is less than minimum bet.');
                redirect('bet.php?game_id=' . $game_id);
            }

            if ($bet_number < 0 || $bet_number >= $game['numbers']) {
                set_flash_message('error', 'Invalid bet number.');
                redirect('bet.php?game_id=' . $game_id);
            }

            if ($user['balance'] < $amount) {
                set_flash_message('error', 'Insufficient balance.');
                redirect('bet.php?game_id=' . $game_id);
            }

            // Deduct wallet balance and place bet in transaction
            try {
                $this->betModel->pdo->beginTransaction();

                // Deduct balance
                $stmt = $this->betModel->pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
                $stmt->execute([$amount, $user_id, $amount]);
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Insufficient balance.');
                }

                // Place bet
                $this->betModel->placeBet($user_id, $game_id, $bet_number, $amount);

                $this->betModel->pdo->commit();

                set_flash_message('success', 'Bet placed successfully.');
                redirect('bet.php?game_id=' . $game_id);
            } catch (Exception $e) {
                $this->betModel->pdo->rollBack();
                set_flash_message('error', 'Failed to place bet: ' . $e->getMessage());
                redirect('bet.php?game_id=' . $game_id);
            }
        }
    }

    // View bet history
    public function viewBetHistory() {
        if (!isset($_SESSION['user_id'])) {
            redirect('login.php');
        }
        $bets = $this->betModel->getBetsByUser($_SESSION['user_id']);
        return $bets;
    }
}
?>
