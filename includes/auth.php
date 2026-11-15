<?php
/**
 * THE LOVE SHACK — Admin Authentication Helper
 * Protected Sessions & BCRYPT Password Verification
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

function require_admin_login(): void {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function admin_login(string $username, string $password): array {
    $username = trim($username);
    if (empty($username) || empty($password)) {
        return ['success' => false, 'error' => 'Please enter both username and password.'];
    }

    $pdo = get_db_connection();
    if (!$pdo) {
        // Fallback for initial setup before MySQL is configured
        if ($username === 'admin' && $password === 'LoveShack2026!') {
            $_SESSION['admin_user_id'] = 1;
            $_SESSION['admin_username'] = 'admin';
            $_SESSION['admin_name'] = 'Administrator (Local Demo)';
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Database connection unavailable. Please check database configuration.'];
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_name'] = $user['full_name'];

            // Update last login
            $upd = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $upd->execute([$user['id']]);

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Invalid username or password.'];
    } catch (Exception $e) {
        error_log("Admin login error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Authentication system error.'];
    }
}

function admin_logout(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
