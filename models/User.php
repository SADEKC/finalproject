<?php
// models/User.php — all user-related database queries

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ---------- Create ----------
    public function create($name, $email, $passwordHash, $role, $pendingAuthor) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password_hash, role, pending_author)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $email, $passwordHash, $role, $pendingAuthor]);
        return $this->pdo->lastInsertId();
    }

    // ---------- Find ----------
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }

    // ---------- Remember-me token ----------
    public function setRememberToken($userId, $tokenHash) {
        $stmt = $this->pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$tokenHash, $userId]);
    }

    public function clearRememberToken($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // ---------- Profile update ----------
    public function updateProfile($userId, $bio, $picPath, $socialLinksJson) {
        // Build dynamic SQL so we only update the avatar if a new one was uploaded
        if ($picPath !== null) {
            $stmt = $this->pdo->prepare(
                "UPDATE users SET bio = ?, profile_pic_path = ?, social_links = ? WHERE id = ?"
            );
            $stmt->execute([$bio, $picPath, $socialLinksJson, $userId]);
        } else {
            $stmt = $this->pdo->prepare(
                "UPDATE users SET bio = ?, social_links = ? WHERE id = ?"
            );
            $stmt->execute([$bio, $socialLinksJson, $userId]);
        }
    }

    // ---------- Admin: list all users ----------
    public function all() {
        $stmt = $this->pdo->query("SELECT id, name, email, role, pending_author, created_at
                                   FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // ---------- Admin: promote to author ----------
    public function promoteToAuthor($userId) {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET role = 'author', pending_author = 0 WHERE id = ?"
        );
        $stmt->execute([$userId]);
        return $stmt->rowCount() > 0;
    }

    // ---------- Public profile: author's published articles ----------
    public function publishedArticles($userId) {
        // articles table is owned by Task 2; we just read from it.
        // If the table is empty during Task 1 testing, this just returns [].
        try {
            $stmt = $this->pdo->prepare(
                "SELECT id, title, featured_image_path, created_at
                 FROM articles
                 WHERE author_id = ? AND status = 'published'
                 ORDER BY created_at DESC"
            );
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
