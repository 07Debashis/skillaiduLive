<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
check_admin();

if (($_POST['action'] ?? '') === 'role' && !empty($_POST['id'])) {
    $stmt = $pdo->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->execute([$_POST['role'] === 'admin' ? 'admin' : 'student', (int)$_POST['id']]);
    flash_set('success','Role updated.');
    header('Location: /admin/manage-users.php'); exit;
}

$page_title = 'Manage Users';
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="md:flex">
  <?php require __DIR__ . '/../includes/sidebar-admin.php'; ?>
  <div class="flex-1 p-6 md:p-10">
    <h1 class="text-3xl font-extrabold">Users</h1>
    <p class="text-white/60 text-sm mt-1"><?= count($users) ?> registered</p>
    <?php if ($msg = flash_get('success')): ?><div class="mt-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm"><?= e($msg) ?></div><?php endif; ?>
    <div class="card mt-6 overflow-hidden">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= e($u['full_name']) ?></td>
              <td class="text-white/60"><?= e($u['email']) ?></td>
              <td><span class="badge"><?= e($u['role']) ?></span></td>
              <td class="text-white/50 text-sm"><?= e(date('M j, Y', strtotime($u['created_at']))) ?></td>
              <td>
                <form method="POST" class="flex gap-2">
                  <input type="hidden" name="action" value="role">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <select name="role" class="input" style="padding:.3rem .5rem">
                    <option value="student" <?= $u['role']==='student'?'selected':'' ?>>student</option>
                    <option value="admin"   <?= $u['role']==='admin'  ?'selected':'' ?>>admin</option>
                  </select>
                  <button class="btn-ghost text-xs">Save</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
