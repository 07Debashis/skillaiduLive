<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($page_title) ? e($page_title) . ' — Skillaidu' : 'Skillaidu — Learn skills that matter' ?></title>
  <meta name="description" content="<?= e($page_desc ?? 'Skillaidu — modern e-learning platform with curated courses on web, data, design and more.') ?>" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              bgDark: '#0a0e1a',
              brand: '#06b6d4',
              brandBlue: '#3b82f6',
            }
          }
        }
      }
  </script>
    
  <link rel="stylesheet" href="/assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>
<header class="border-b border-white/5 sticky top-0 z-40 backdrop-blur bg-[rgba(10,14,26,0.7)]">
  <div class="container-x flex items-center justify-between h-16">
    <a href="/index.php" class="flex items-center gap-2">
      <span class="inline-block w-8 h-8 rounded-lg" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)"></span>
      <span class="font-extrabold tracking-tight text-lg">Skillaidu</span>
    </a>
    <button id="navToggle" class="md:hidden text-white/80" aria-label="Toggle menu">
      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>
    <nav id="navMenu" class="hidden md:flex items-center gap-6 text-sm text-white/80">
      <a href="/index.php" class="hover:text-white">Home</a>
      <a href="/pages/courses.php" class="hover:text-white">Courses</a>
      <?php if (is_admin()): ?>
        <a href="/admin/index.php" class="hover:text-white">Admin</a>
      <?php endif; ?>
      <?php if (is_logged_in()): ?>
        <span class="text-white/60">Hi, <?= e(current_user()['name']) ?></span>
        <a href="/auth/logout.php" class="btn-ghost">Logout</a>
      <?php else: ?>
        <a href="/login.php" class="hover:text-white">Login</a>
        <a href="/signup.php" class="btn-brand">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>