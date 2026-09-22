<?php

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function current_user(): ?array
{
    static $user = false;

    if ($user !== false) {
        return $user;
    }

    if (empty($_SESSION['user_id'])) {
        $user = null;
        return null;
    }

    $stmt = db()->prepare('SELECT id, name, email FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch() ?: null;

    return $user;
}

function require_auth(): void
{
    if (!current_user()) {
        flash('warning', 'Сначала войдите в систему.');
        redirect('/login');
    }
}

function require_guest(): void
{
    if (current_user()) {
        redirect('/dashboard');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Ошибка проверки формы. Обновите страницу и попробуйте еще раз.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function render(string $view, array $data = [], bool $loadUser = true): void
{
    extract($data);
    $user = $loadUser ? current_user() : null;
    $flash = get_flash();
    require __DIR__ . '/views/layout_top.php';
    require __DIR__ . '/views/' . $view . '.php';
    require __DIR__ . '/views/layout_bottom.php';
}
