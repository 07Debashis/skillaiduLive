<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN categories cat ON cat.id=c.category_id WHERE c.id=? AND c.published=1");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) { http_response_code(404); $page_title='Not found'; require_once __DIR__ . '/../includes/header.php'; echo '<div class="container-x py-20 text-center"><h1 class="text-3xl font-bold">Course not found</h1><a href="/pages/courses.php" class="text-cyan-400 hover:underline mt-4 inline-block">← Back to courses</a></div>'; require_once __DIR__ . '/../includes/footer.php'; exit; }

$page_title = $c['title'];
$page_desc  = $c['short_description'];
require_once __DIR__ . '/../includes/header.php';

$embed = youtube_embed($c['video_url']);
?>
<section class="container-x py-12 grid lg:grid-cols-[1fr_360px] gap-10">
  <div>
    <a href="/pages/courses.php" class="text-sm text-white/50 hover:text-cyan-400">← All courses</a>
    <h1 class="text-4xl font-extrabold mt-3"><?= e($c['title']) ?></h1>
    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-white/60">
      <?php if ($c['category_name']): ?><span class="badge"><?= e($c['category_name']) ?></span><?php endif; ?>
      <span><?= e($c['level']) ?></span>
      <span>•</span><span><?= (float)$c['duration_hours'] ?>h</span>
      <span>•</span><span>by <?= e($c['instructor'] ?: 'Skillaidu') ?></span>
    </div>

    <div class="card mt-6 overflow-hidden">
      <div class="aspect-video bg-black">
        <?php if ($embed): ?>
          <iframe class="w-full h-full" src="<?= e($embed) ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <?php elseif ($c['video_url']): ?>
          <video class="w-full h-full" controls src="<?= e($c['video_url']) ?>"></video>
        <?php elseif ($c['thumbnail_url']): ?>
          <img src="<?= e($c['thumbnail_url']) ?>" alt="<?= e($c['title']) ?>" class="w-full h-full object-cover">
        <?php endif; ?>
      </div>
    </div>

    <div class="prose prose-invert mt-8 max-w-none">
      <h2 class="text-2xl font-bold mb-3">About this course</h2>
      <p class="text-white/75 leading-relaxed whitespace-pre-line"><?= e($c['description'] ?: $c['short_description']) ?></p>
    </div>
  </div>

  <aside class="card p-6 h-fit lg:sticky lg:top-24">
    <div class="text-3xl font-bold text-cyan-400">$<?= number_format((float)$c['price'], 2) ?></div>
    <div class="text-white/50 text-sm mt-1">One-time purchase</div>
    <?php if (is_logged_in()): ?>
      <button class="btn-brand w-full mt-4">Enroll now</button>
    <?php else: ?>
      <a href="/login.php" class="btn-brand w-full mt-4 inline-block text-center">Login to enroll</a>
    <?php endif; ?>
    <ul class="mt-6 space-y-2 text-sm text-white/70">
      <li>✓ Lifetime access</li>
      <li>✓ <?= (float)$c['duration_hours'] ?> hours of content</li>
      <li>✓ Certificate of completion</li>
      <li>✓ Learn at your own pace</li>
    </ul>
  </aside>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
