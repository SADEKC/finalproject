<?php

class ReportController
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

    public function store()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->json([
                "success" => false,
                "message" => "Please login first"
            ], 401);
            return;
        }

        $commentId = $_POST["comment_id"] ?? "";
        $reason = trim($_POST["reason"] ?? "");

        if ($commentId == "" || !is_numeric($commentId)) {
            $this->json([
                "success" => false,
                "message" => "Invalid comment"
            ], 422);
            return;
        }

        if ($reason == "") {
            $this->json([
                "success" => false,
                "message" => "Reason is required"
            ], 422);
            return;
        }

        $commentModel = new Comment($this->pdo);
        $comment = $commentModel->getById($commentId);

        if (!$comment) {
            $this->json([
                "success" => false,
                "message" => "Comment not found"
            ], 404);
            return;
        }

        $reportModel = new Report($this->pdo);

        $alreadyReported = $reportModel->alreadyReported(
            $commentId,
            $_SESSION["user_id"]
        );

        if ($alreadyReported) {
            $this->json([
                "success" => false,
                "message" => "You already reported this comment"
            ], 409);
            return;
        }

        $reportModel->create(
            $commentId,
            $_SESSION["user_id"],
            $reason
        );

        $this->json([
            "success" => true,
            "message" => "Reported successfully"
        ]);
    }

    public function destroy($id)
    {
        if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
            $this->json([
                "success" => false,
                "message" => "Admin only"
            ], 403);
            return;
        }

        $reportModel = new Report($this->pdo);
        $reportModel->clear($id);

        $this->json([
            "success" => true,
            "message" => "Flag cleared"
        ]);
    }
}
