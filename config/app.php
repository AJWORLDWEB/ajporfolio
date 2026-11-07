<?php
declare(strict_types=1);

define('APP_NAME', 'AJWorldTech');
define('BASE_URL', rtrim(getenv('APP_URL') ?: '', '/'));
define('APP_ENV', getenv('APP_ENV') ?: 'production');
date_default_timezone_set('Africa/Lagos');

if (APP_ENV !== 'production') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secure,
        'samesite' => 'Lax',
    ]);
    session_start();
}
