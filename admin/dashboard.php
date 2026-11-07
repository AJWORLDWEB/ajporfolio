<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();
$counts = [];
foreach (['content' => 'Content items', 'messages' => 'Enquiries', 'admins' => 'Admin users'] as $table => $label) {
    $counts[$label] = (int)db()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}
$recent = db()->query('SELECT id, name, email, subject, created_at, status FROM messages ORDER BY created_at DESC LIMIT 8')->fetchAll();
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard | AJWorldTech</title><link rel="stylesheet" href="assets/admin.css"></head>
<body><div class="admin-shell"><aside class="sidebar"><a class="brand" href="../">AJWorldTech<span>.</span></a><nav><a class="active" href="dashboard.php">Dashboard</a><a href="content.php">Website content</a><a href="messages.php">Enquiries</a></nav><form method="post" action="logout.php"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="secondary" type="submit">Sign out</button></form></aside>
<main class="main"><div class="topbar"><div><p class="eyebrow">ADMINISTRATION</p><h1>Welcome, <?= e(admin_user()['name']) ?></h1></div><a class="button-link" href="../" target="_blank" rel="noopener">View website ↗</a></div>
<section class="stats"><?php foreach ($counts as $label => $value): ?><article class="stat"><span><?= e($label) ?></span><strong><?= $value ?></strong></article><?php endforeach; ?></section>
<section class="panel"><div class="panel-head"><h2>Recent enquiries</h2><a href="messages.php">View all</a></div><?php if (!$recent): ?><p class="muted">No enquiries received yet.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th></tr></thead><tbody><?php foreach ($recent as $m): ?><tr><td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['subject']) ?></td><td><?= e($m['created_at']) ?></td><td><span class="status"><?= e($m['status']) ?></span></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
</main></div></body></html>
