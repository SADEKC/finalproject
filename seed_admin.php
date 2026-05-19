<?php
// seed_admin.php — RUN ONCE then DELETE this file.
// Visit http://localhost/blog-platform/seed_admin.php in your browser.

require_once __DIR__ . '/config/db.php';

$email = 'admin@blog.local';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    $upd = $pdo->prepare("UPDATE users SET password_hash = ?, role = 'admin' WHERE email = ?");
    $upd->execute([$hash, $email]);
    echo "Admin password updated. Email: $email | Password: $password";
} else {
    $ins = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
    $ins->execute(['Admin', $email, $hash]);
    echo "Admin created. Email: $email | Password: $password";
}
echo "<br><br><strong>DELETE this file (seed_admin.php) now for security.</strong>";
