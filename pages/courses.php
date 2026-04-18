<?php
require_once __DIR__ . '/../config/db.php';
$page_title = 'Courses';
require_once __DIR__ . '/../includes/header.php';

$q   = trim($_GET['q']   ?? '');
$cat = (int)($_GET['cat'] ?? 0);

$sql = "SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN categories cat ON cat.id=c.category_id WHERE c.published=1";
$args = [];
if ($q)   { $sql .= " AND (c.title LIKE ? OR c.short_description LIKE ?)"; $args[] = "%$q%"; $args[] = "%$q%"; }
if ($cat) { $sql .= " AND c.category_id = ?"; $args[] = $cat; }
$sql .= " ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($args); $courses = $stmt->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<section class="container-x py-12">
  <h1 class="text-4xl font-extrabold">All courses</h1>
  <p class="text-white/60 mt-1">Browse our complete catalog.</p>

  <form method="GET" class="mt-6 grid md:grid-cols-[1fr_220px_auto] gap-3">
    <input class="input" type="text" name="q" placeholder="Search courses…" value="<?= e($q) ?>">
    <select class="input" name="cat">
      <option value="0">All categories</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= $cat === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn-brand" type="submit">Search</button>
  </form>

  <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (!$courses): ?>
      <div class="col-span-full text-center text-white/50 py-16">No courses found.</div>
    <?php endif; ?>
    <?php foreach ($courses as $c): ?>
      <a href="/pages/course-view.php?id=<?= (int)$c['id'] ?>" class="card block">
        <div class="aspect-video bg-white/5 overflow-hidden">
          <?php if ($c['thumbnail_url']): ?><img src="<?= e($c['thumbnail_url']) ?>" alt="<?= e($c['title']) ?>" class="w-full h-full object-cover"><?php endif; ?>
        </div>
        <div class="p-5">
          <div class="flex items-center gap-2 text-xs text-white/50 mb-2">
            <?php if ($c['category_name']): ?><span class="badge"><?= e($c['category_name']) ?></span><?php endif; ?>
            <span><?= e($c['level']) ?> • <?= (float)$c['duration_hours'] ?>h</span>
          </div>
          <h3 class="font-bold text-lg"><?= e($c['title']) ?></h3>
          <p class="text-sm text-white/60 mt-2 line-clamp-2"><?= e($c['short_description']) ?></p>
          <div class="flex items-center justify-between mt-4">
            <span class="text-white/50 text-sm">by <?= e($c['instructor'] ?: 'Skillaidu') ?></span>
            <span class="font-bold text-cyan-400">$<?= number_format((float)$c['price'], 2) ?></span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
