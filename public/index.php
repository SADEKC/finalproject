<?php

session_start();

$baseUrl = rtrim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

if ($baseUrl === "/") {
    $baseUrl = "";
}

define("BASE_URL", $baseUrl);

$pdo = require __DIR__ . "/../config/database.php";

require_once __DIR__ . "/../models/Article.php";
require_once __DIR__ . "/../models/Comment.php";
require_once __DIR__ . "/../models/Report.php";

require_once __DIR__ . "/../controllers/ArticleController.php";
require_once __DIR__ . "/../controllers/CommentController.php";
require_once __DIR__ . "/../controllers/ReportController.php";
require_once __DIR__ . "/../controllers/AdminModerationController.php";

$method = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (BASE_URL !== "" && strpos($path, BASE_URL) === 0) {
    $path = substr($path, strlen(BASE_URL));
}

$path = "/" . trim($path, "/");

if ($path === "//") {
    $path = "/";
}

// Article reading page
if ($method === "GET" && preg_match("#^/articles/([0-9]+)$#", $path, $matches)) {
    $controller = new ArticleController($pdo);
    $controller->show($matches[1]);
    exit;
}

// Post comment
if ($method === "POST" && $path === "/api/comments") {
    $controller = new CommentController($pdo);
    $controller->store();
    exit;
}

// Delete comment
if ($method === "DELETE" && preg_match("#^/api/comments/([0-9]+)$#", $path, $matches)) {
    $controller = new CommentController($pdo);
    $controller->destroy($matches[1]);
    exit;
}

// Report comment
if ($method === "POST" && $path === "/api/reports") {
    $controller = new ReportController($pdo);
    $controller->store();
    exit;
}

// Clear report flag
if ($method === "DELETE" && preg_match("#^/api/reports/([0-9]+)$#", $path, $matches)) {
    $controller = new ReportController($pdo);
    $controller->destroy($matches[1]);
    exit;
}

// Admin moderation page
if ($method === "GET" && $path === "/admin/moderation") {
    $controller = new AdminModerationController($pdo);
    $controller->index();
    exit;
}

// Admin delete comment
if ($method === "DELETE" && preg_match("#^/api/admin/comments/([0-9]+)$#", $path, $matches)) {
    $controller = new AdminModerationController($pdo);
    $controller->deleteComment($matches[1]);
    exit;
}

http_response_code(404);
echo "404 - Page Not Found";
