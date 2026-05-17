<?php
session_start();

$as = $_GET['as'] ?? 'reader';

if ($as === 'admin') {
    $_SESSION['user_id'] = 1;
    $_SESSION['name'] = 'Admin User';
    $_SESSION['role'] = 'admin';
} elseif ($as === 'author') {
    $_SESSION['user_id'] = 2;
    $_SESSION['name'] = 'Author User';
    $_SESSION['role'] = 'author';
} else {
    $_SESSION['user_id'] = 3;
    $_SESSION['name'] = 'Reader User';
    $_SESSION['role'] = 'reader';
}
?>

<h2>Logged in as <?= htmlspecialchars($_SESSION['role']) ?></h2>

<p><a href="articles/1">Open Article Page</a></p>
<p><a href="admin/moderation">Open Admin Moderation</a></p>

<hr>

<p>Login as:</p>
<a href="test_login.php?as=reader">Reader</a> |
<a href="test_login.php?as=author">Author</a> |
<a href="test_login.php?as=admin">Admin</a>