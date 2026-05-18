<?php
// controllers/AuthController.php — register, login, logout

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // ---------- REGISTER ----------
    public function register() {
        $errors = [];
        $old    = ['name' => '', 'email' => '', 'role' => 'reader'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name']     ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = $_POST['password']      ?? '';
            $role     = $_POST['role']          ?? 'reader';
            $old      = ['name' => $name, 'email' => $email, 'role' => $role];

            // -- Server-side validation --
            if ($name === '')                              $errors['name']     = 'Name is required.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email']    = 'Valid email required.';
            if (strlen($password) < 8)                     $errors['password'] = 'Password must be ≥ 8 chars.';
            if (!in_array($role, ['reader', 'author']))    $errors['role']     = 'Invalid role.';

            if (empty($errors) && $this->userModel->emailExists($email)) {
                $errors['email'] = 'Email is already registered.';
            }

            if (empty($errors)) {
                // PDF rule: Author registrations stored as role='reader' with pending_author=1
                $finalRole     = 'reader';
                $pendingAuthor = ($role === 'author') ? 1 : 0;

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $this->userModel->create($name, $email, $hash, $finalRole, $pendingAuthor);

                set_flash('success',
                    $pendingAuthor
                      ? 'Registered! An admin must promote you before you can write articles.'
                      : 'Registered! Please log in.'
                );
                redirect(base_url('login'));
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    // ---------- LOGIN ----------
    public function login() {
        $errors = [];
        $old    = ['email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = $_POST['password']      ?? '';
            $remember = !empty($_POST['remember']);
            $old      = ['email' => $email];

            if ($email === '' || $password === '') {
                $errors['general'] = 'Email and password are required.';
            } else {
                $user = $this->userModel->findByEmail($email);
                if (!$user || !password_verify($password, $user['password_hash'])) {
                    $errors['general'] = 'Invalid email or password.';
                } else {
                    // -- Create session --
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name']    = $user['name'];
                    $_SESSION['role']    = $user['role'];

                    // -- Remember-me: store hashed token, set 30-day cookie --
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));   // raw token → cookie
                        $hash  = hash('sha256', $token);      // hash → DB
                        $this->userModel->setRememberToken($user['id'], $hash);
                        setcookie(
                            'remember_token',
                            $token,
                            time() + 60 * 60 * 24 * 30,   // 30 days
                            '/',
                            '',
                            false,   // secure: set true if using HTTPS
                            true     // httpOnly
                        );
                    }

                    redirect(base_url(''));   // home
                }
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    // ---------- LOGOUT ----------
    public function logout() {
        if (is_logged_in()) {
            $this->userModel->clearRememberToken(current_user_id());
        }
        // Clear cookie
        setcookie('remember_token', '', time() - 3600, '/');
        // Destroy session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]
            );
        }
        session_destroy();
        redirect(base_url('login'));
    }
}
