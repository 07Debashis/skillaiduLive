<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
check_admin();
$page_title = 'Admin Overview';
require_once __DIR__ . '/../includes/header.php';

$total_courses    = (int)$pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$published        = (int)$pdo->query("SELECT COUNT(*) FROM courses WHERE published=1")->fetchColumn();
$total_users      = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_categories = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>
<div class="md:flex">
  <?php require __DIR__ . '/../includes/sidebar-admin.php'; ?>
  <div class="flex-1 p-6 md:p-10">
    <button id="sidebarToggle" class="md:hidden btn-ghost mb-4">☰ Menu</button>
    <h1 class="text-3xl font-extrabold">Dashboard</h1>
    <p class="text-white/60 text-sm mt-1">Quick stats from your platform.</p>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
      <?php foreach ([['Courses',$total_courses,'#06b6d4'],['Published',$published,'#22c55e'],['Users',$total_users,'#3b82f6'],['Categories',$total_categories,'#a855f7']] as $s): ?>
        <div class="card p-5">
          <div class="text-white/50 text-sm"><?= e($s[0]) ?></div>
          <div class="text-3xl font-extrabold mt-1" style="color:<?= $s[2] ?>"><?= (int)$s[1] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="card mt-8 p-6">
      <h3 class="font-bold mb-2">Quick actions</h3>
      <div class="flex flex-wrap gap-3">
        <a href="/admin/manage-courses.php?new=1" class="btn-brand">+ New course</a>
        <a href="/admin/manage-categories.php?new=1" class="btn-ghost">+ New category</a>
        <a href="/admin/manage-courses.php" class="btn-ghost">Manage courses</a>
        <a href="/admin/manage-categories.php" class="btn-ghost">Manage categories</a>
        <a href="/admin/manage-users.php" class="btn-ghost">Manage users</a>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
