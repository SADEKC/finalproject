<?php

class ArticleController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function show($id)
    {
        $articleModel = new Article($this->pdo);
        $commentModel = new Comment($this->pdo);

        $article = $articleModel->getArticle($id);

        if (!$article) {
            http_response_code(404);
            echo "Article not found";
            return;
        }

        $articleModel->increaseViewCount($id);

        $tags = $articleModel->getTags($id);
        $comments = $commentModel->getByArticle($id);

        require __DIR__ . "/../views/articles/show.php";
    }
}
