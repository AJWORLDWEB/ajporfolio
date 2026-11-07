<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();
$types = ['service' => 'Services', 'product' => 'Products', 'project' => 'Projects', 'article' => 'Articles'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
    $type = (string)($_POST['type'] ?? '');
    if (!array_key_exists($type, $types)) { http_response_code(422); exit('Invalid content type'); }
    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $summary = trim((string)($_POST['summary'] ?? ''));
    $body = trim((string)($_POST['body'] ?? ''));
    $status = ($_POST['status'] ?? '') === 'published' ? 'published' : 'draft';
    if ($title === '' || $slug === '') { flash('error', 'Title and slug are required.'); redirect('admin/content.php'); }
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $slug), '-'));
    if ($id) {
        db()->prepare('UPDATE content SET type=?, title=?, slug=?, summary=?, body=?, status=?, updated_at=NOW() WHERE id=?')->execute([$type,$title,$slug,$summary,$body,$status,$id]);
    } else {
        db()->prepare('INSERT INTO content (type,title,slug,summary,body,status) VALUES (?,?,?,?,?,?)')->execute([$type,$title,$slug,$summary,$body,$status]);
    }
    flash('success', 'Content saved.');
    redirect('admin/content.php');
}
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) db()->prepare('DELETE FROM content WHERE id=?')->execute([$id]);
    redirect('admin/content.php');
}
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT * FROM content WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch() ?: null;
}
$items = db()->query('SELECT id,type,title,slug,status,updated_at FROM content ORDER BY updated_at DESC')->fetchAll();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Website content | AJWorldTech</title><link rel="stylesheet" href="assets/admin.css"></head><body><div class="admin-shell"><aside class="sidebar"><a class="brand" href="../">AJWorldTech<span>.</span></a><nav><a href="dashboard.php">Dashboard</a><a class="active" href="content.php">Website content</a><a href="messages.php">Enquiries</a></nav><form method="post" action="logout.php"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><button class="secondary" type="submit">Sign out</button></form></aside><main class="main"><div class="topbar"><div><p class="eyebrow">CMS</p><h1>Website content</h1></div></div>
<?php foreach (['success','error'] as $k): if ($msg=flash($k)): ?><div class="notice"><?= e($msg) ?></div><?php endif; endforeach; ?>
<section class="panel"><h2><?= $edit ? 'Edit item' : 'Add content' ?></h2><form method="post" class="content-form"><input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e((string)($edit['id'] ?? '')) ?>"><label>Content type<select name="type"><?php foreach ($types as $key=>$label): ?><option value="<?= e($key) ?>" <?= (($edit['type'] ?? 'service')===$key)?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select></label><label>Title<input name="title" required value="<?= e($edit['title'] ?? '') ?>"></label><label>Slug<input name="slug" required value="<?= e($edit['slug'] ?? '') ?>" placeholder="my-service"></label><label>Short summary<textarea name="summary" rows="2"><?= e($edit['summary'] ?? '') ?></textarea></label><label>Full description<textarea name="body" rows="5"><?= e($edit['body'] ?? '') ?></textarea></label><label>Status<select name="status"><option value="draft" <?= (($edit['status'] ?? '')==='draft')?'selected':'' ?>>Draft</option><option value="published" <?= (($edit['status'] ?? '')==='published')?'selected':'' ?>>Published</option></select></label><button type="submit">Save content</button></form></section>
<section class="panel"><h2>Existing content</h2><div class="table-wrap"><table><thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['title']) ?></td><td><?= e($item['type']) ?></td><td><?= e($item['status']) ?></td><td><?= e($item['updated_at']) ?></td><td><a href="?edit=<?= (int)$item['id'] ?>">Edit</a> · <a href="?delete=<?= (int)$item['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a></td></tr><?php endforeach; ?></tbody></table></div></section>
</main></div></body></html>
