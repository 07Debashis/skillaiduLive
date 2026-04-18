<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
check_admin();

$action = $_POST['action'] ?? '';

// CREATE / UPDATE
if ($action === 'save') {
    $id    = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug  = trim($_POST['slug']  ?? '') ?: slugify($title);
    if (!$title) { flash_set('error','Title is required.'); header('Location: /admin/manage-courses.php'); exit; }
    $data = [
        'title' => $title, 'slug' => $slug,
        'short_description' => trim($_POST['short_description'] ?? ''),
        'description'       => trim($_POST['description'] ?? ''),
        'thumbnail_url'     => trim($_POST['thumbnail_url'] ?? ''),
        'video_url'         => trim($_POST['video_url'] ?? ''),
        'category_id'       => $_POST['category_id'] ? (int)$_POST['category_id'] : null,
        'instructor'        => trim($_POST['instructor'] ?? ''),
        'level'             => $_POST['level'] ?? 'Beginner',
        'duration_hours'    => (float)($_POST['duration_hours'] ?? 0),
        'price'             => (float)($_POST['price'] ?? 0),
        'published'         => isset($_POST['published']) ? 1 : 0,
    ];
    if ($id > 0) {
        $set = implode(',', array_map(fn($k)=>"$k=:$k", array_keys($data)));
        $stmt = $pdo->prepare("UPDATE courses SET $set WHERE id=:id");
        $stmt->execute($data + ['id'=>$id]);
        flash_set('success','Course updated.');
    } else {
        $cols = implode(',', array_keys($data)) . ',created_by';
        $vals = ':' . implode(',:', array_keys($data)) . ',:created_by';
        $stmt = $pdo->prepare("INSERT INTO courses ($cols) VALUES ($vals)");
        $stmt->execute($data + ['created_by'=>$_SESSION['user_id']]);
        flash_set('success','Course created.');
    }
    header('Location: /admin/manage-courses.php'); exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $pdo->prepare("DELETE FROM courses WHERE id=?")->execute([$id]);
    flash_set('success','Course deleted.');
    header('Location: /admin/manage-courses.php'); exit;
}

$page_title = 'Manage Courses';
$courses    = $pdo->query("SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN categories cat ON cat.id=c.category_id ORDER BY c.created_at DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$edit_id    = (int)($_GET['edit'] ?? 0);
$showForm   = isset($_GET['new']) || $edit_id > 0;
$editing    = $edit_id ? $pdo->prepare("SELECT * FROM courses WHERE id=?") : null;
if ($editing) { $editing->execute([$edit_id]); $editing = $editing->fetch(); }

require_once __DIR__ . '/../includes/header.php';
?>
<div class="md:flex">
  <?php require __DIR__ . '/../includes/sidebar-admin.php'; ?>
  <div class="flex-1 p-6 md:p-10">
    <button id="sidebarToggle" class="md:hidden btn-ghost mb-4">☰ Menu</button>
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold">Manage Courses</h1>
        <p class="text-white/60 text-sm mt-1"><?= count($courses) ?> total course<?= count($courses)===1?'':'s' ?></p>
      </div>
      <a href="/admin/manage-courses.php?new=1" class="btn-brand">+ New course</a>
    </div>

    <?php if ($msg = flash_get('success')): ?><div class="mt-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($err = flash_get('error')):   ?><div class="mt-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm"><?= e($err) ?></div><?php endif; ?>

    <?php if ($showForm): ?>
      <div class="card mt-6 p-6">
        <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit course' : 'New course' ?></h2>
        <form method="POST" class="grid md:grid-cols-2 gap-4">
          <input type="hidden" name="action" value="save">
          <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
          <div><label class="text-sm text-white/70">Title *</label><input class="input mt-1" name="title" required value="<?= e($editing['title'] ?? '') ?>"></div>
          <div><label class="text-sm text-white/70">Slug (URL)</label><input class="input mt-1" name="slug" placeholder="auto from title" value="<?= e($editing['slug'] ?? '') ?>"></div>
          <div class="md:col-span-2"><label class="text-sm text-white/70">Short description</label><input class="input mt-1" name="short_description" value="<?= e($editing['short_description'] ?? '') ?>"></div>
          <div class="md:col-span-2"><label class="text-sm text-white/70">Full description</label><textarea class="input mt-1" name="description" rows="5"><?= e($editing['description'] ?? '') ?></textarea></div>
          <div><label class="text-sm text-white/70">Thumbnail URL</label><input class="input mt-1" name="thumbnail_url" placeholder="https://…" value="<?= e($editing['thumbnail_url'] ?? '') ?>"></div>
          <div><label class="text-sm text-white/70">Video URL (YouTube or .mp4)</label><input class="input mt-1" name="video_url" placeholder="https://youtube.com/watch?v=…" value="<?= e($editing['video_url'] ?? '') ?>"></div>
          <div>
            <label class="text-sm text-white/70">Category</label>
            <select class="input mt-1" name="category_id">
              <option value="">— None —</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= ($editing['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="text-sm text-white/70">Level</label>
            <select class="input mt-1" name="level">
              <?php foreach (['Beginner','Intermediate','Advanced'] as $lv): ?>
                <option <?= ($editing['level'] ?? 'Beginner')===$lv?'selected':'' ?>><?= $lv ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div><label class="text-sm text-white/70">Instructor</label><input class="input mt-1" name="instructor" value="<?= e($editing['instructor'] ?? '') ?>"></div>
          <div><label class="text-sm text-white/70">Duration (hours)</label><input class="input mt-1" type="number" step="0.1" min="0" name="duration_hours" value="<?= e($editing['duration_hours'] ?? '0') ?>"></div>
          <div><label class="text-sm text-white/70">Price (USD)</label><input class="input mt-1" type="number" step="0.01" min="0" name="price" value="<?= e($editing['price'] ?? '0') ?>"></div>
          <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" id="published" name="published" <?= ($editing['published'] ?? 1) ? 'checked' : '' ?>>
            <label for="published" class="text-sm">Published</label>
          </div>
          <div class="md:col-span-2 flex gap-3 mt-2">
            <button class="btn-brand" type="submit"><?= $editing ? 'Save changes' : 'Create course' ?></button>
            <a href="/admin/manage-courses.php" class="btn-ghost">Cancel</a>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <div class="card mt-8 overflow-hidden">
      <table class="admin-table">
        <thead><tr><th>Course</th><th class="hidden md:table-cell">Category</th><th class="hidden md:table-cell">Level</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php if (!$courses): ?><tr><td colspan="5" class="text-center text-white/50 py-10">No courses yet. <a href="?new=1" class="text-cyan-400 hover:underline">Create the first one</a>.</td></tr><?php endif; ?>
          <?php foreach ($courses as $c): ?>
            <tr>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-14 h-10 bg-white/5 rounded overflow-hidden flex-shrink-0">
                    <?php if ($c['thumbnail_url']): ?><img src="<?= e($c['thumbnail_url']) ?>" class="w-full h-full object-cover" alt=""><?php endif; ?>
                  </div>
                  <div>
                    <div class="font-semibold"><?= e($c['title']) ?></div>
                    <div class="text-xs text-white/40">/<?= e($c['slug']) ?></div>
                  </div>
                </div>
              </td>
              <td class="hidden md:table-cell text-white/60"><?= e($c['category_name'] ?? '—') ?></td>
              <td class="hidden md:table-cell text-white/60"><?= e($c['level']) ?></td>
              <td>
                <span class="text-xs px-2 py-1 rounded-full <?= $c['published'] ? 'bg-emerald-500/15 text-emerald-300' : 'bg-white/10 text-white/60' ?>">
                  <?= $c['published'] ? 'Published' : 'Draft' ?>
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <a href="?edit=<?= (int)$c['id'] ?>" class="btn-ghost text-xs">Edit</a>
                  <form method="POST" onsubmit="return confirm('Delete this course?')" class="inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                    <button class="btn-ghost text-xs" style="color:#fca5a5">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
