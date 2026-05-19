<?php
// config/helpers.php — session, auth, remember-me, redirect, json

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// ---------- Base URL ----------
function base_url($path = '') {
    // Adjust if your folder name differs from 'blog-platform'
    $base = '/blog-platform';
    return $base . '/' . ltrim($path, '/');
}

// ---------- Remember-me cookie auto-login ----------
// Runs on every page. If session is empty but cookie exists, reinstate session.
function try_remember_login() {
    global $pdo;
    if (isset($_SESSION['user_id'])) return;
    if (empty($_COOKIE['remember_token'])) return;

    $token = $_COOKIE['remember_token'];
    $hash  = hash('sha256', $token);

    $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE remember_token = ? LIMIT 1");
    $stmt->execute([$hash]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
    } else {
        // Invalid/expired token — delete cookie
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
try_remember_login();

// ---------- Auth checks ----------
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function current_role() {
    return $_SESSION['role'] ?? null;
}

function require_login() {
    if (!is_logged_in()) {
        redirect(base_url('login'));
    }
}

function require_admin() {
    if (!is_logged_in() || current_role() !== 'admin') {
        http_response_code(403);
        die('Forbidden — admin only.');
    }
}

// ---------- Redirect ----------
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// ---------- JSON response (for AJAX endpoints) ----------
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ---------- Escape output ----------
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// ---------- Flash messages ----------
function set_flash($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}
function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}
