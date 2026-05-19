<?php
// index.php — front controller / router

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$base    = '/blog-platform';
$uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path    = trim(str_replace($base, '', $uri), '/');
$segments = $path === '' ? [] : explode('/', $path);

$auth    = new AuthController($pdo);
$profile = new ProfileController($pdo);
$admin   = new AdminController($pdo);

switch (true) {
    case $path === '':
        $pageTitle = 'Home';
        include __DIR__ . '/views/layout/header.php';
        ?>
        <div class="hero">
          <h1>Stories worth reading.<br>Voices worth following.</h1>
          <p>A multi-author publishing platform for writers and readers — clean, fast, and free.</p>
          <?php if (!is_logged_in()): ?>
            <a href="<?= base_url('register') ?>" class="btn">Get started →</a>
          <?php else: ?>
            <a href="<?= base_url('profile/edit') ?>" class="btn">Edit your profile →</a>
          <?php endif; ?>
        </div>

        <h2>About this build</h2>
        <p style="color:#4b5563;font-size:14px;">.</p>

        <?php if (is_logged_in()): ?>
          <div class="article-card" style="margin-top:18px;">
            ✓ You're signed in as <strong><?= e($_SESSION['name']) ?></strong>
            <span class="badge badge-<?= e(current_role()) ?>" style="margin-left:6px;"><?= e(current_role()) ?></span>
          </div>
        <?php endif; ?>
        <?php
        include __DIR__ . '/views/layout/footer.php';
        break;

    case $path === 'register':           $auth->register();    break;
    case $path === 'login':              $auth->login();       break;
    case $path === 'logout':             $auth->logout();      break;

    case $path === 'profile/edit':       $profile->edit();     break;
    case $segments[0] === 'authors' && isset($segments[1]):
        $profile->publicProfile($segments[1]);
        break;

    case $path === 'admin/users':        $admin->users();      break;

    case $path === 'api/users/promote':  $admin->promote();    break;

    default:
        http_response_code(404);
        $pageTitle = 'Not Found';
        include __DIR__ . '/views/layout/header.php';
        echo '<h1>404</h1><p class="subtitle">Page not found: <code>' . e($path) . '</code></p>';
        echo '<a href="' . base_url('') . '" class="btn">← Back home</a>';
        include __DIR__ . '/views/layout/footer.php';
}
