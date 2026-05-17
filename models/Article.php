<?php

class Article
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getArticle($id)
    {
        $sql = "
            SELECT 
                articles.*,
                users.name AS author_name,
                users.profile_pic_path,
                categories.name AS category_name
            FROM articles
            JOIN users ON articles.author_id = users.id
            LEFT JOIN categories ON articles.category_id = categories.id
            WHERE articles.id = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getTags($articleId)
    {
        $sql = "
            SELECT tags.name
            FROM article_tags
            JOIN tags ON article_tags.tag_id = tags.id
            WHERE article_tags.article_id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$articleId]);

        return $stmt->fetchAll();
    }

    public function increaseViewCount($articleId)
    {
        $stmt = $this->pdo->prepare("
            UPDATE articles 
            SET view_count = view_count + 1 
            WHERE id = ?
        ");

        $stmt->execute([$articleId]);
    }

    public function countPublishedArticles()
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) 
            FROM articles 
            WHERE status = 'published'
        ");

        return $stmt->fetchColumn();
    }

    public function countComments()
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) 
            FROM comments
        ");

        return $stmt->fetchColumn();
    }

    public function countFlaggedComments()
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) 
            FROM reported_comments
        ");

        return $stmt->fetchColumn();
    }
}
