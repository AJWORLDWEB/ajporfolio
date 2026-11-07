<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = ($_POST['status'] ?? '') === 'read' ? 'read' : 'new';
    if ($id) db()->prepare('UPDATE messages SET status=? WHERE id=?')->execute([$status,$id]);
    redirect('admin/messages.php');
}
$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Enquiries | AJWorldTech</title><link rel="stylesheet" href="assets/admin.css"></head><body><div class="admin-shell"><aside class="sidebar"><a class="brand" href="../">AJWorldTech<span>.</span></a><nav><a href="dashboard.php">Dashboard</a><a href="content.php">Website content</a><a class="active" href="messages.php">Enquiries</a></nav><form method="post" action="logout.php"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="secondary" type="submit">Sign out</button></form></aside><main class="main"><div class="topbar"><div><p class="eyebrow">INBOX</p><h1>Customer enquiries</h1></div></div><section class="panel"><div class="table-wrap"><table><thead><tr><th>Name</th><th>Contact</th><th>Subject / message</th><th>Date</th><th>Status</th></tr></thead><tbody><?php foreach ($messages as $m): ?><tr><td><?= e($m['name']) ?></td><td><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a><br><?= e($m['phone']) ?></td><td><strong><?= e($m['subject']) ?></strong><br><?= nl2br(e($m['message'])) ?></td><td><?= e($m['created_at']) ?></td><td><form method="post" class="inline-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><select name="status"><option value="new" <?= $m['status']==='new'?'selected':'' ?>>New</option><option value="read" <?= $m['status']==='read'?'selected':'' ?>>Read</option></select><button class="small" type="submit">Update</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php if (!$messages): ?><p class="muted">No enquiries yet.</p><?php endif; ?></section></main></div></body></html>
