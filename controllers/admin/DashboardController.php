<?php
require_once __DIR__ . '/../../models/Bet.php';
require_once __DIR__ . '/../../models/User.php';

class DashboardController {
    private $betModel;
    private $userModel;

    public function __construct($pdo) {
        $this->betModel = new Bet($pdo);
        $this->userModel = new User($pdo);
    }

    public function index() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: admin_login.php');
            exit;
        }

        // Total bets count
        $stmt = $this->betModel->pdo->query("SELECT COUNT(*) as total_bets FROM bets");
        $totalBets = $stmt->fetch()['total_bets'];

        // Total payouts (sum of won bets * payout multiplier)
        // For simplicity, sum of won bets amount * 80 (assuming 80x payout for 100-number games)
        $stmt = $this->betModel->pdo->query("SELECT SUM(amount) as total_won_amount FROM bets WHERE status = 'won'");
        $totalWonAmount = $stmt->fetch()['total_won_amount'] ?? 0;
        $totalPayouts = $totalWonAmount * 80; // This can be refined based on game type

        return ['totalBets' => $totalBets, 'totalPayouts' => $totalPayouts];
    }
}
?>
