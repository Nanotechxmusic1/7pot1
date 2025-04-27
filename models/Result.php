<?php
require_once __DIR__ . '/../config/config.php';

class Result {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add a new result
    public function addResult($game_id, $result_number) {
        $stmt = $this->pdo->prepare("INSERT INTO results (game_id, result_number) VALUES (?, ?)");
        return $stmt->execute([$game_id, $result_number]);
    }

    // Get recent results
    public function getRecentResults($limit = 10) {
        $stmt = $this->pdo->prepare("SELECT r.*, g.name as game_name FROM results r JOIN games g ON r.game_id = g.id ORDER BY r.result_time DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get latest result for a game
    public function getLatestResultByGame($game_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM results WHERE game_id = ? ORDER BY result_time DESC LIMIT 1");
        $stmt->execute([$game_id]);
        return $stmt->fetch();
    }
}
?>
