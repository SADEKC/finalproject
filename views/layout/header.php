<?php require_once __DIR__ . '/../../config/helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle) : 'BlogPlatform' ?></title>
  <link rel="stylesheet" href="<?= base_url('public/css/style.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="topbar">
  <a href="<?= base_url('') ?>" class="brand"><span class="brand-mark">✦</span> BlogPlatform</a>
  <?php if (is_logged_in()): ?>
    <a href="<?= base_url('authors/' . current_user_id()) ?>">My Profile</a>
    <a href="<?= base_url('profile/edit') ?>">Edit</a>
    <?php if (current_role() === 'admin'): ?>
      <a href="<?= base_url('admin/users') ?>">Admin</a>
    <?php endif; ?>
  <?php endif; ?>
  <span class="spacer"></span>
  <?php if (is_logged_in()): ?>
    <span class="user-chip"><?= e($_SESSION['name']) ?> · <?= e(current_role()) ?></span>
    <a href="<?= base_url('logout') ?>">Logout</a>
  <?php else: ?>
    <a href="<?= base_url('login') ?>">Login</a>
    <a href="<?= base_url('register') ?>">Register</a>
  <?php endif; ?>
</nav>
<?php if (empty($skipContainer)): ?>
<div class="container<?= !empty($wideContainer) ? ' wide' : '' ?>">
<?php if ($msg = get_flash('success')): ?>
  <div class="flash"><?= e($msg) ?></div>
<?php endif; ?>
<?php endif; ?>
