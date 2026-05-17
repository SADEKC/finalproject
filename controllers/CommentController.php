<?php

class CommentController
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

        $articleId = $_POST["article_id"] ?? "";
        $body = trim($_POST["body"] ?? "");

        if ($articleId == "" || !is_numeric($articleId)) {
            $this->json([
                "success" => false,
                "message" => "Invalid article"
            ], 422);
            return;
        }

        if (strlen($body) < 5) {
            $this->json([
                "success" => false,
                "message" => "Comment must be at least 5 characters"
            ], 422);
            return;
        }

        $articleModel = new Article($this->pdo);
        $article = $articleModel->getArticle($articleId);

        if (!$article) {
            $this->json([
                "success" => false,
                "message" => "Article not found"
            ], 404);
            return;
        }

        $commentModel = new Comment($this->pdo);

        $commentId = $commentModel->create(
            $articleId,
            $_SESSION["user_id"],
            $body
        );

        $comment = $commentModel->getById($commentId);

        $this->json([
            "success" => true,
            "comment" => [
                "id" => $comment["id"],
                "name" => $comment["name"],
                "body" => $comment["body"],
                "created_at" => $comment["created_at"],
                "can_delete" => true
            ]
        ]);
    }

    public function destroy($id)
    {
        if (!isset($_SESSION["user_id"])) {
            $this->json([
                "success" => false,
                "message" => "Please login first"
            ], 401);
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

        $currentUserId = $_SESSION["user_id"];
        $currentRole = $_SESSION["role"] ?? "";

        $canDelete = false;

        if ($comment["user_id"] == $currentUserId) {
            $canDelete = true;
        }

        if ($comment["author_id"] == $currentUserId) {
            $canDelete = true;
        }

        if ($currentRole === "admin") {
            $canDelete = true;
        }

        if (!$canDelete) {
            $this->json([
                "success" => false,
                "message" => "You cannot delete this comment"
            ], 403);
            return;
        }

        $commentModel->delete($id);

        $this->json([
            "success" => true,
            "message" => "Comment deleted"
        ]);
    }
}
