<?php
declare(strict_types=1);

// Run from CLI only: php scripts/create-admin.php "Admin Name" admin@example.com "StrongPassword"
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../config/database.php';
$name = $argv[1] ?? '';
$email = strtolower(trim($argv[2] ?? ''));
$password = $argv[3] ?? '';
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 14) {
    fwrite(STDERR, "Usage: php scripts/create-admin.php \"Admin Name\" admin@example.com \"Password-at-least-14-chars\"\n");
    exit(1);
}
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = db()->prepare('INSERT INTO admins (name,email,password_hash,role) VALUES (?,?,?,?)');
$stmt->execute([$name,$email,$hash,'super_admin']);
fwrite(STDOUT, "Administrator created: {$email}\n");
