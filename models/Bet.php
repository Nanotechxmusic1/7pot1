<?php
require_once __DIR__ . '/../config/config.php';

class Bet {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Place a new bet
    public function placeBet($user_id, $game_id, $bet_number, $amount) {
        $stmt = $this->pdo->prepare("INSERT INTO bets (user_id, game_id, bet_number, amount) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $game_id, $bet_number, $amount]);
    }

    // Get bets by user
    public function getBetsByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT b.*, g.name as game_name FROM bets b JOIN games g ON b.game_id = g.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Get bets by game and status
    public function getBetsByGameAndStatus($game_id, $status = 'pending') {
        $stmt = $this->pdo->prepare("SELECT * FROM bets WHERE game_id = ? AND status = ?");
        $stmt->execute([$game_id, $status]);
        return $stmt->fetchAll();
    }

    // Update bet status
    public function updateBetStatus($bet_id, $status) {
        $stmt = $this->pdo->prepare("UPDATE bets SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $bet_id]);
    }

    // Mark all bets for a game as won or lost
    public function markBetsByGame($game_id, $bet_number, $winning = true) {
        // Mark bets with winning number as won, others as lost
        $this->pdo->beginTransaction();
        try {
            $stmtWin = $this->pdo->prepare("UPDATE bets SET status = 'won' WHERE game_id = ? AND bet_number = ? AND status = 'pending'");
            $stmtWin->execute([$game_id, $bet_number]);

            $stmtLost = $this->pdo->prepare("UPDATE bets SET status = 'lost' WHERE game_id = ? AND bet_number != ? AND status = 'pending'");
            $stmtLost->execute([$game_id, $bet_number]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>
