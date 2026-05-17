<?php

class Report
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function alreadyReported($commentId, $userId)
    {
        $sql = "
            SELECT id 
            FROM reported_comments
            WHERE comment_id = ? AND reported_by = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$commentId, $userId]);

        return $stmt->fetch();
    }

    public function create($commentId, $userId, $reason)
    {
        $sql = "
            INSERT INTO reported_comments
            (comment_id, reported_by, reason, created_at)
            VALUES (?, ?, ?, NOW())
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$commentId, $userId, $reason]);
    }

    public function getAllReports()
    {
        $sql = "
            SELECT 
                reported_comments.id AS report_id,
                reported_comments.reason,
                reported_comments.created_at AS reported_at,
                comments.id AS comment_id,
                comments.body AS comment_body,
                articles.title AS article_title,
                users.name AS reporter_name
            FROM reported_comments
            JOIN comments ON reported_comments.comment_id = comments.id
            JOIN articles ON comments.article_id = articles.id
            JOIN users ON reported_comments.reported_by = users.id
            ORDER BY reported_comments.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function clear($reportId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM reported_comments 
            WHERE id = ?
        ");

        return $stmt->execute([$reportId]);
    }
}
