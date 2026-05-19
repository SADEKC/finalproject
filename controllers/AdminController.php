<?php
// controllers/AdminController.php — admin user list + AJAX promote

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // ---------- USERS LIST PAGE ----------
    public function users() {
        require_admin();
        $users = $this->userModel->all();
        require __DIR__ . '/../views/admin/users.php';
    }

    // ---------- API: PROMOTE TO AUTHOR (AJAX) ----------
    // POST /api/users/promote   body: user_id
    public function promote() {
        require_admin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['error' => 'Method not allowed'], 405);
        }
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            json_response(['error' => 'Invalid user_id'], 400);
        }
        $ok = $this->userModel->promoteToAuthor($userId);
        if (!$ok) {
            json_response(['error' => 'User not found or already promoted'], 404);
        }
        json_response(['success' => true, 'user_id' => $userId, 'new_role' => 'author']);
    }
}
