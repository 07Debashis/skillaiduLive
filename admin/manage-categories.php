<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
check_admin();

$action = $_POST['action'] ?? '';

// CREATE / UPDATE
if ($action === 'save') {
    $id          = (int)($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $slug        = trim($_POST['slug'] ?? '') ?: slugify($name);
    $description = trim($_POST['description'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');

    if (!$name) { flash_set('error','Category name is required.'); header('Location: /admin/manage-categories.php'); exit; }

    $data = [
        'name'        => $name,
        'slug'        => $slug,
        'description' => $description,
        'image_url'   => $image_url,
    ];

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE categories SET name=:name, slug=:slug, description=:description, image_url=:image_url WHERE id=:id");
            $stmt->execute($data + ['id' => $id]);
            flash_set('success','Category updated.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, image_url) VALUES (:name, :slug, :description, :image_url)");
            $stmt->execute($data);
            flash_set('success','Category created.');
        }
    } catch (PDOException $e) {
        flash_set('error', 'Could not save category — slug may already be in use.');
    }

    header('Location: /admin/manage-categories.php'); exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    // Detach courses first so FK / app logic stays clean
    $pdo->prepare("UPDATE courses SET category_id = NULL WHERE category_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    flash_set('success','Category deleted.');
    header('Location: /admin/manage-categories.php'); exit;
}

$page_title = 'Manage Categories';

// Categories with course counts
$categories = $pdo->query("
    SELECT c.*, (SELECT COUNT(*) FROM courses WHERE category_id = c.id) AS course_count
    FROM categories c
    ORDER BY c.name
")->fetchAll();

$edit_id  = (int)($_GET['edit'] ?? 0);
$showForm = isset($_GET['new']) || $edit_id > 0;
$editing  = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch();
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="md:flex">
  <?php require __DIR__ . '/../includes/sidebar-admin.php'; ?>
  <div class="flex-1 p-6 md:p-10">
    <button id="sidebarToggle" class="md:hidden btn-ghost mb-4">☰ Menu</button>

    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold">Manage Categories</h1>
        <p class="text-white/60 text-sm mt-1">
          <?= count($categories) ?> total categor<?= count($categories) === 1 ? 'y' : 'ies' ?>
        </p>
      </div>
      <a href="/admin/manage-categories.php?new=1" class="btn-brand">+ New category</a>
    </div>

    <?php if ($msg = flash_get('success')): ?>
      <div class="mt-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($err = flash_get('error')): ?>
      <div class="mt-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm"><?= e($err) ?></div>
    <?php endif; ?>

    <?php if ($showForm): ?>
      <div class="card mt-6 p-6">
        <h2 class="text-xl font-bold mb-4"><?= $editing ? 'Edit category' : 'New category' ?></h2>
        <form method="POST" class="grid md:grid-cols-2 gap-4">
          <input type="hidden" name="action" value="save">
          <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

          <div>
            <label class="text-sm text-white/70">Name *</label>
            <input class="input mt-1" name="name" required value="<?= e($editing['name'] ?? '') ?>" placeholder="e.g. Web Development">
          </div>
          <div>
            <label class="text-sm text-white/70">Slug (URL)</label>
            <input class="input mt-1" name="slug" placeholder="auto from name" value="<?= e($editing['slug'] ?? '') ?>">
          </div>

          <div class="md:col-span-2">
            <label class="text-sm text-white/70">Description</label>
            <textarea class="input mt-1" name="description" rows="3" placeholder="What this category covers…"><?= e($editing['description'] ?? '') ?></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="text-sm text-white/70">Image URL</label>
            <input class="input mt-1" name="image_url" placeholder="https://… (optional cover image)" value="<?= e($editing['image_url'] ?? '') ?>">
          </div>

          <div class="md:col-span-2 flex gap-3 mt-2">
            <button class="btn-brand" type="submit"><?= $editing ? 'Save changes' : 'Create category' ?></button>
            <a href="/admin/manage-categories.php" class="btn-ghost">Cancel</a>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <div class="card mt-8 overflow-hidden">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Category</th>
            <th class="hidden md:table-cell">Slug</th>
            <th>Courses</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$categories): ?>
            <tr>
              <td colspan="4" class="text-center text-white/50 py-10">
                No categories yet. <a href="?new=1" class="text-cyan-400 hover:underline">Create the first one</a>.
              </td>
            </tr>
          <?php endif; ?>

          <?php foreach ($categories as $cat): ?>
            <tr>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-white/5 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center text-cyan-400">
                    <?php if (!empty($cat['image_url'])): ?>
                      <img src="<?= e($cat['image_url']) ?>" class="w-full h-full object-cover" alt="">
                    <?php else: ?>
                      🏷️
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="font-semibold"><?= e($cat['name']) ?></div>
                    <?php if (!empty($cat['description'])): ?>
                      <div class="text-xs text-white/40 truncate max-w-xs"><?= e($cat['description']) ?></div>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
              <td class="hidden md:table-cell text-white/60 text-sm">/<?= e($cat['slug']) ?></td>
              <td>
                <span class="text-xs px-2 py-1 rounded-full bg-cyan-500/15 text-cyan-300">
                  <?= (int)$cat['course_count'] ?> course<?= $cat['course_count'] == 1 ? '' : 's' ?>
                </span>
              </td>
              <td>
                <div class="flex gap-2">
                  <a href="?edit=<?= (int)$cat['id'] ?>" class="btn-ghost text-xs">Edit</a>
                  <form method="POST" onsubmit="return confirm('Delete this category? Courses in it will become uncategorized.')" class="inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
                    <button class="btn-ghost text-xs text-red-400 hover:text-red-300" type="submit">Delete</button>
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
