<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $model;
    private $pdo;

    public function __construct() {
        global $pdo;
        if (!$pdo) {
            throw new RuntimeException("Database connection failed");
        }
        $this->pdo = $pdo;
        $this->model = new UserModel($pdo);
    }

    public function login() {
        $this->startSecureSession();
        
        // Redirect if already logged in
        if (!empty($_SESSION['user_id'])) {
            header('Location: ?page=dashboard');
            exit();
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';

                // Validation
                if (empty($email) || empty($password)) {
                    throw new RuntimeException("Email and password are required");
                }

                $user = $this->model->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // Security measures
                    session_regenerate_id(true);
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['last_activity'] = time();
                    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];

                    header('Location: ?page=dashboard');
                    exit();
                } else {
                    throw new RuntimeException("Invalid credentials");
                }
            }
        } catch (RuntimeException $e) {
            $_SESSION['login_error'] = $e->getMessage();
            header('Location: ?page=login');
            exit();
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        $this->startSecureSession();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitization
                $username = trim(htmlspecialchars($_POST['username'] ?? ''));
                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';

                // Validation
                $errors = [];
                if (empty($username)) $errors[] = "Username required";
                if (empty($email)) $errors[] = "Email required";
                if (empty($password)) $errors[] = "Password required";
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
                if (strlen($password) < 8) $errors[] = "Password must be 8+ characters";

                if (!empty($errors)) {
                    throw new RuntimeException(implode("<br>", $errors));
                }

                // Database operation
                $result = $this->model->createUser($username, $email, $password);
                
                if ($result !== true) {
                    throw new RuntimeException($result === false ? "Registration failed" : $result);
                }

                $_SESSION['registration_success'] = true;
                header('Location: ?page=login&success=1');
                exit();
            }
        } catch (RuntimeException $e) {
            $_SESSION['register_error'] = $e->getMessage();
            $_SESSION['old_input'] = [
                'username' => $username ?? '',
                'email' => $email ?? ''
            ];
            header('Location: ?page=register');
            exit();
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        $this->startSecureSession();
        
        // Unset all session values
        $_SESSION = [];

        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header('Location: ?page=login');
        exit();
    }

    private function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            ini_set('session.use_strict_mode', 1);
            session_start([
                'name' => 'SECURE_SESSID',
                'cookie_lifetime' => 86400,
                'cookie_samesite' => 'Strict'
            ]);
        }
    }
}