<?php

class AdminModerationController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    private function checkAdmin()
    {
        return isset($_SESSION["user_id"]) && $_SESSION["role"] === "admin";
    }

    public function index()
    {
        if (!$this->checkAdmin()) {
            header("Location: " . BASE_URL . "/login.php");
            exit;
        }

        $articleModel = new Article($this->pdo);
        $reportModel = new Report($this->pdo);

        $totalPublished = $articleModel->countPublishedArticles();
        $totalComments = $articleModel->countComments();
        $totalFlagged = $articleModel->countFlaggedComments();

        $reports = $reportModel->getAllReports();

        require __DIR__ . "/../views/admin/moderation.php";
    }

    public function deleteComment($id)
    {
        if (!$this->checkAdmin()) {
            $this->json([
                "success" => false,
                "message" => "Admin only"
            ], 403);
            return;
        }

        $commentModel = new Comment($this->pdo);
        $comment = $commentModel->getById($id);

        if (!$comment) {
            $this->json([
                "success" => false,
                "message" => "Comment not found"
            ], 404);
            return;
        }

        $commentModel->delete($id);

        $this->json([
            "success" => true,
            "message" => "Comment deleted"
        ]);
    }
}
