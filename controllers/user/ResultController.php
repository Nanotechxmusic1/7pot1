<?php
require_once __DIR__ . '/../../models/Result.php';

class ResultController {
    private $resultModel;

    public function __construct($pdo) {
        $this->resultModel = new Result($pdo);
    }

    // Get recent results for ticker or history
    public function getRecentResults($limit = 10) {
        return $this->resultModel->getRecentResults($limit);
    }
}
?>
