<?php
// controllers/ProfileController.php — edit profile + public author page

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController {
    private $userModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo       = $pdo;
        $this->userModel = new User($pdo);
    }

    // ---------- EDIT PROFILE (logged-in user) ----------
    public function edit() {
        require_login();
        $userId = current_user_id();
        $errors = [];
        $user   = $this->userModel->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bio     = trim($_POST['bio']     ?? '');
            $twitter = trim($_POST['twitter'] ?? '');
            $github  = trim($_POST['github']  ?? '');

            // --- Validate URLs (allow empty) ---
            if ($twitter !== '' && !filter_var($twitter, FILTER_VALIDATE_URL)) {
                $errors['twitter'] = 'Twitter must be a valid URL.';
            }
            if ($github !== '' && !filter_var($github, FILTER_VALIDATE_URL)) {
                $errors['github'] = 'GitHub must be a valid URL.';
            }

            // --- Avatar upload (optional) ---
            $picPath = null;
            if (!empty($_FILES['avatar']['name'])) {
                $picPath = $this->handleAvatarUpload($_FILES['avatar'], $errors);
            }

            if (empty($errors)) {
                $socialJson = json_encode([
                    'twitter' => $twitter,
                    'github'  => $github
                ]);
                $this->userModel->updateProfile($userId, $bio, $picPath, $socialJson);
                set_flash('success', 'Profile updated.');
                redirect(base_url('profile/edit'));
            }
        }

        // Refresh user data for display
        $user = $this->userModel->findById($userId);
        require __DIR__ . '/../views/profile/edit.php';
    }

    // ---------- AVATAR UPLOAD HELPER ----------
    // Validates MIME + size and moves file to public/uploads/avatars/
    private function handleAvatarUpload($file, &$errors) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Upload failed.';
            return null;
        }
        // -- Size: ≤ 1 MB --
        if ($file['size'] > 1024 * 1024) {
            $errors['avatar'] = 'Avatar must be ≤ 1 MB.';
            return null;
        }
        // -- MIME: JPEG or PNG (server-side check, not just extension) --
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        if (!isset($allowed[$mime])) {
            $errors['avatar'] = 'Avatar must be JPEG or PNG.';
            return null;
        }
        // -- Build safe filename --
        $ext      = $allowed[$mime];
        $filename = 'avatar_' . current_user_id() . '_' . time() . '.' . $ext;
        $destDir  = __DIR__ . '/../public/uploads/avatars/';
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);

        if (!move_uploaded_file($file['tmp_name'], $destDir . $filename)) {
            $errors['avatar'] = 'Could not save file.';
            return null;
        }
        // Return relative path stored in DB
        return 'public/uploads/avatars/' . $filename;
    }

    // ---------- PUBLIC AUTHOR PROFILE (/authors/{id}) ----------
    public function publicProfile($id) {
        $author = $this->userModel->findById((int)$id);
        if (!$author) {
            http_response_code(404);
            die('Author not found.');
        }
        $articles = $this->userModel->publishedArticles($author['id']);
        $social   = $author['social_links'] ? json_decode($author['social_links'], true) : [];
        require __DIR__ . '/../views/profile/public.php';
    }
}
