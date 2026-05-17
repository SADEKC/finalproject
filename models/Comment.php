<?php

class Comment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByArticle($articleId)
    {
        $sql = "
            SELECT 
                comments.*,
                users.name
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.article_id = ?
            ORDER BY comments.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId]);

        return $stmt->fetchAll();
    }

    public function create($articleId, $userId, $body)
    {
        $sql = "
            INSERT INTO comments 
            (article_id, user_id, body, created_at)
            VALUES (?, ?, ?, NOW())
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId, $userId, $body]);

        return $this->pdo->lastInsertId();
    }

    public function getById($commentId)
    {
        $sql = "
            SELECT 
                comments.*,
                users.name,
                articles.author_id,
                articles.title AS article_title
            FROM comments
            JOIN users ON comments.user_id = users.id
            JOIN articles ON comments.article_id = articles.id
            WHERE comments.id = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$commentId]);

        return $stmt->fetch();
    }

    public function delete($commentId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM reported_comments 
            WHERE comment_id = ?
        ");
        $stmt->execute([$commentId]);

        $stmt = $this->pdo->prepare("
            DELETE FROM comments 
            WHERE id = ?
        ");
        $stmt->execute([$commentId]);
    }
}
