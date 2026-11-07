<?php
require_once __DIR__ . '/../app/bootstrap.php';
if (admin_user()) redirect('admin/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (attempt_login((string)($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''))) {
        redirect('admin/dashboard.php');
    }
    $error = 'Email or password is incorrect.';
}
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin login | AJWorldTech</title><link rel="stylesheet" href="assets/admin.css"></head>
<body class="auth-page"><main class="auth-card"><a class="brand" href="../">AJWorldTech<span>.</span></a><h1>Admin sign in</h1><p>Manage your company website.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><label>Email address<input type="email" name="email" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button type="submit">Sign in</button></form>
</main></body></html>
