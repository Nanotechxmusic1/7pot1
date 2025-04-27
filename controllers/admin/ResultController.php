<?php
require_once __DIR__ . '/../../models/Result.php';
require_once __DIR__ . '/../../models/Bet.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/csrf.php';

class ResultController {
    private $resultModel;
    private $betModel;
    private $userModel;

    public function __construct($pdo) {
        $this->resultModel = new Result($pdo);
        $this->betModel = new Bet($pdo);
        $this->userModel = new User($pdo);
    }

    // List recent results
    public function listResults() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('admin_login.php');
        }
        return $this->resultModel->getRecentResults(20);
    }

    // Declare result and update bets and user balances
    public function declareResult() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validate_csrf_token($_POST['csrf_token'])) {
                set_flash_message('error', 'Invalid CSRF token.');
                redirect('declare_result.php');
            }

            $game_id = intval($_POST['game_id']);
            $result_number = intval($_POST['result_number']);

            if (!$game_id || $result_number < 0) {
                set_flash_message('error', 'Invalid input.');
                redirect('declare_result.php');
            }

            try {
                $this->resultModel->pdo->beginTransaction();

                // Add result
                $this->resultModel->addResult($game_id, $result_number);

                // Mark bets as won or lost
                $this->betModel->markBetsByGame($game_id, $result_number);

                // Credit winners' wallets
                $stmt = $this->betModel->pdo->prepare("SELECT user_id, amount FROM bets WHERE game_id = ? AND bet_number = ? AND status = 'won'");
                $stmt->execute([$game_id, $result_number]);
                $winners = $stmt->fetchAll();

                // Get game details for payout multiplier
                $stmtGame = $this->betModel->pdo->prepare("SELECT numbers FROM games WHERE id = ?");
                $stmtGame->execute([$game_id]);
                $game = $stmtGame->fetch();
                $payoutMultiplier = ($game['numbers'] == 100) ? 80 : 8;

                foreach ($winners as $winner) {
                    $payout = $winner['amount'] * $payoutMultiplier;
                    $this->userModel->updateBalance($winner['user_id'], $payout);
                }

                $this->resultModel->pdo->commit();

                set_flash_message('success', 'Result declared and payouts processed.');
                redirect('declare_result.php');
            } catch (Exception $e) {
                $this->resultModel->pdo->rollBack();
                set_flash_message('error', 'Failed to declare result: ' . $e->getMessage());
                redirect('declare_result.php');
            }
        }
    }
}
?>
