<?php
require_once __DIR__ . '/config/db.php';
$page_title = 'Home';
$page_desc  = 'Skillaid — modern e-learning platform with curated courses.';
require_once __DIR__ . '/includes/header.php';

$courses = $pdo->query("SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN categories cat ON cat.id=c.category_id WHERE c.published=1 ORDER BY c.created_at DESC LIMIT 6")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<section class="hero-gradient">
  <div class="container-x py-20 md:py-28 grid md:grid-cols-2 gap-10 items-center">
    <div class="fade-up">
      <span class="badge">🚀 New courses every week</span>
      <h1 class="text-4xl md:text-6xl font-extrabold mt-4 leading-[1.05]">
        Learn skills that <span style="background:linear-gradient(135deg,#06b6d4,#3b82f6);-webkit-background-clip:text;color:transparent">matter today</span>
      </h1>
      <p class="text-white/70 mt-5 max-w-xl">Curated, expert-led courses on web development, data science, design and more. Learn at your own pace, on any device.</p>
      <div class="flex gap-3 mt-7">
        <a href="/pages/courses.php" class="btn-brand">Browse courses</a>
        <a href="/signup.php" class="btn-ghost">Get started — Free</a>
      </div>
      <div class="flex gap-8 mt-8 text-white/60 text-sm">
        <div><div class="text-2xl font-bold text-white"><?= (int)$pdo->query("SELECT COUNT(*) FROM courses WHERE published=1")->fetchColumn() ?>+</div>Courses</div>
        <div><div class="text-2xl font-bold text-white"><?= (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn() ?></div>Categories</div>
        <div><div class="text-2xl font-bold text-white">24/7</div>Access</div>
      </div>
    </div>
    <div class="card p-6 fade-up">
      <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-white/60">Your progress</span>
        <span class="text-xs text-cyan-400 font-semibold">This week</span>
      </div>
      <div class="space-y-4">
        <?php foreach (array_slice($courses, 0, 3) as $i => $c): $pct = [72, 45, 28][$i] ?? 50; ?>
          <div>
            <div class="flex justify-between text-sm mb-1"><span class="truncate"><?= e($c['title']) ?></span><span class="text-white/50"><?= $pct ?>%</span></div>
            <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div class="h-full" style="width: <?= $pct ?>%; background: linear-gradient(90deg,#06b6d4,#3b82f6);"></div></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="container-x py-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <h2 class="text-3xl font-bold">Explore by category</h2>
      <p class="text-white/60 mt-1 text-sm">Pick a topic and start learning.</p>
    </div>
  </div>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    <?php foreach ($categories as $cat): ?>
      <a href="/pages/courses.php?cat=<?= (int)$cat['id'] ?>" class="card p-5 block">
        <div class="w-10 h-10 rounded-xl mb-3" style="background: linear-gradient(135deg, rgba(6,182,212,.4), rgba(59,130,246,.4));"></div>
        <div class="font-semibold"><?= e($cat['name']) ?></div>
        <div class="text-xs text-white/50 mt-1 line-clamp-2"><?= e($cat['description']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="container-x py-16">
  <div class="flex items-end justify-between mb-8">
    <div>
      <h2 class="text-3xl font-bold">Latest courses</h2>
      <p class="text-white/60 mt-1 text-sm">Fresh content, hand-picked.</p>
    </div>
    <a href="/pages/courses.php" class="text-cyan-400 hover:underline text-sm">View all →</a>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($courses as $c): ?>
      <a href="/pages/course-view.php?id=<?= (int)$c['id'] ?>" class="card block">
        <div class="aspect-video bg-white/5 overflow-hidden">
          <?php if ($c['thumbnail_url']): ?>
            <img src="<?= e($c['thumbnail_url']) ?>" alt="<?= e($c['title']) ?>" class="w-full h-full object-cover">
          <?php endif; ?>
        </div>
        <div class="p-5">
          <div class="flex items-center gap-2 text-xs text-white/50 mb-2">
            <?php if ($c['category_name']): ?><span class="badge"><?= e($c['category_name']) ?></span><?php endif; ?>
            <span><?= e($c['level']) ?> • <?= (float)$c['duration_hours'] ?>h</span>
          </div>
          <h3 class="font-bold text-lg leading-tight"><?= e($c['title']) ?></h3>
          <p class="text-sm text-white/60 mt-2 line-clamp-2"><?= e($c['short_description']) ?></p>
          <div class="flex items-center justify-between mt-4">
            <span class="text-white/50 text-sm">by <?= e($c['instructor'] ?: 'Skilledu') ?></span>
            <span class="font-bold text-cyan-400">$<?= number_format((float)$c['price'], 2) ?></span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
