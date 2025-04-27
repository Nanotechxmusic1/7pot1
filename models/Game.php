<?php
require_once __DIR__ . '/../config/config.php';

class Game {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get all active games
    public function getActiveGames() {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE status = 'active' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get game by ID
    public function getGameById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Add new game
    public function addGame($name, $min_bet, $numbers, $status = 'active') {
        $stmt = $this->pdo->prepare("INSERT INTO games (name, min_bet, numbers, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $min_bet, $numbers, $status]);
    }

    // Update game
    public function updateGame($id, $name, $min_bet, $numbers, $status) {
        $stmt = $this->pdo->prepare("UPDATE games SET name = ?, min_bet = ?, numbers = ?, status = ? WHERE id = ?");
        return $stmt->execute([$name, $min_bet, $numbers, $status, $id]);
    }

    // Enable or disable game
    public function setGameStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE games SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Get all games (for admin)
    public function getAllGames() {
        $stmt = $this->pdo->query("SELECT * FROM games ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
?>
