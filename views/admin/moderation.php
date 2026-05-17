<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Moderation</title>
</head>
<body>

<h1>Admin Moderation Dashboard</h1>

<div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">
    <p>Total Published Articles: <strong><?= $totalPublished ?></strong></p>
    <p>Total Comments: <strong><?= $totalComments ?></strong></p>
    <p>Total Flagged Comments: <strong><?= $totalFlagged ?></strong></p>
</div>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Comment</th>
            <th>Article</th>
            <th>Reason</th>
            <th>Reporter</th>
            <th>Reported At</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody id="reportsTable">

        <?php if (empty($reports)): ?>

            <tr>
                <td colspan="6">No reported comments found</td>
            </tr>

        <?php endif; ?>

        <?php foreach ($reports as $report): ?>

            <tr id="report-<?= $report["report_id"] ?>">
                <td><?= htmlspecialchars($report["comment_body"]) ?></td>
                <td><?= htmlspecialchars($report["article_title"]) ?></td>
                <td><?= htmlspecialchars($report["reason"]) ?></td>
                <td><?= htmlspecialchars($report["reporter_name"]) ?></td>
                <td><?= htmlspecialchars($report["reported_at"]) ?></td>
                <td>
                    <button 
                        class="clear-flag-btn" 
                        data-report-id="<?= $report["report_id"] ?>"
                    >
                        Clear Flag
                    </button>

                    <button 
                        class="admin-delete-comment-btn"
                        data-comment-id="<?= $report["comment_id"] ?>"
                        data-report-id="<?= $report["report_id"] ?>"
                    >
                        Delete Comment
                    </button>
                </td>
            </tr>

        <?php endforeach; ?>

    </tbody>
</table>

<script>
    window.BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL ?>/js/moderation.js"></script>

</body>
</html>
