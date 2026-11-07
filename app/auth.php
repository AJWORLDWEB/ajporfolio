<?php
declare(strict_types=1);

function admin_user(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_admin(): void
{
    if (!admin_user()) redirect('admin/login.php');
}

function attempt_login(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([strtolower(trim($email))]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        password_verify($password, '$2y$10$usesomesillystringforsalt$');
        return false;
    }

    session_regenerate_id(true);
    unset($user['password_hash']);
    $_SESSION['admin'] = $user;
    db()->prepare('UPDATE admins SET last_login_at = NOW() WHERE id = ?')->execute([$user['id']]);
    return true;
}

function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
