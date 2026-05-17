<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article["title"]) ?></title>
</head>
<body>

<h1><?= htmlspecialchars($article["title"]) ?></h1>

<p>
    Author:
    <strong><?= htmlspecialchars($article["author_name"]) ?></strong>
</p>

<p>
    Category:
    <strong><?= htmlspecialchars($article["category_name"] ?? "No Category") ?></strong>
</p>

<?php if (!empty($article["featured_image_path"])): ?>
    <img 
        src="<?= BASE_URL . "/" . htmlspecialchars($article["featured_image_path"]) ?>" 
        alt="Article Image" 
        style="max-width:400px;"
    >
<?php endif; ?>

<div>
    <?= nl2br(htmlspecialchars($article["body"])) ?>
</div>

<hr>

<h3>Tags</h3>

<?php if (!empty($tags)): ?>
    <?php foreach ($tags as $tag): ?>
        <span style="border:1px solid #ddd; padding:5px;">
            <?= htmlspecialchars($tag["name"]) ?>
        </span>
    <?php endforeach; ?>
<?php else: ?>
    <p>No tags</p>
<?php endif; ?>

<hr>

<h2>Comments</h2>

<?php if (isset($_SESSION["user_id"])): ?>
    <div>
        <textarea 
            id="commentBody" 
            placeholder="Write your comment" 
            rows="4" 
            cols="50"
        ></textarea>
        <br><br>
        <button id="postCommentBtn">Post Comment</button>
    </div>
<?php else: ?>
    <p>
        <a href="<?= BASE_URL ?>/login.php">Login to comment</a>
    </p>
<?php endif; ?>

<br>

<div id="commentsList">

    <?php foreach ($comments as $comment): ?>

        <?php
        $canDelete = false;

        if (isset($_SESSION["user_id"])) {
            if ($comment["user_id"] == $_SESSION["user_id"]) {
                $canDelete = true;
            }

            if ($article["author_id"] == $_SESSION["user_id"]) {
                $canDelete = true;
            }

            if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
                $canDelete = true;
            }
        }
        ?>

        <div 
            id="comment-<?= $comment["id"] ?>" 
            class="comment-box"
            style="border:1px solid #ddd; padding:10px; margin-bottom:10px;"
        >
            <strong><?= htmlspecialchars($comment["name"]) ?></strong>

            <p><?= nl2br(htmlspecialchars($comment["body"])) ?></p>

            <small><?= htmlspecialchars($comment["created_at"]) ?></small>

            <br><br>

            <?php if (isset($_SESSION["user_id"])): ?>
                <button 
                    class="report-btn" 
                    data-comment-id="<?= $comment["id"] ?>"
                >
                    🚩 Report
                </button>
            <?php endif; ?>

            <?php if ($canDelete): ?>
                <button 
                    class="delete-comment-btn" 
                    data-comment-id="<?= $comment["id"] ?>"
                >
                    Delete
                </button>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

</div>

<script>
    window.BASE_URL = "<?= BASE_URL ?>";
    window.articleId = <?= (int) $article["id"] ?>;
</script>

<script src="<?= BASE_URL ?>/js/comments.js"></script>

</body>
</html>
