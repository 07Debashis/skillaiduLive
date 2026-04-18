<?php $page_title = 'Login'; require_once __DIR__ . '/includes/header.php'; ?>
<section class="hero-gradient min-h-[80vh] flex items-center">
  <div class="container-x grid md:grid-cols-2 gap-10 py-16">
    <div class="hidden md:block">
      <span class="badge">Welcome back</span>
      <h1 class="text-4xl md:text-5xl font-extrabold mt-4 leading-tight">Continue your <span style="background:linear-gradient(135deg,#06b6d4,#3b82f6);-webkit-background-clip:text;color:transparent">learning journey</span></h1>
      <p class="text-white/60 mt-4 max-w-md">Sign in to access your enrolled courses and pick up where you left off.</p>
    </div>
    <div class="card p-8 fade-up">
      <h2 class="text-2xl font-bold mb-1">Login</h2>
      <p class="text-white/50 text-sm mb-6">Use your email and password.</p>
      <?php if ($err = flash_get('error')): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm"><?= e($err) ?></div>
      <?php endif; ?>
      <form method="POST" action="/auth/login-process.php" data-validate class="space-y-4">
        <div><label class="text-sm text-white/70">Email</label><input class="input mt-1" type="email" name="email" required></div>
        <div><label class="text-sm text-white/70">Password</label><input class="input mt-1" type="password" name="password" required></div>
        <button class="btn-brand w-full" type="submit">Sign in</button>
      </form>
      <p class="text-sm text-white/60 mt-4">No account? <a href="/signup.php" class="text-cyan-400 hover:underline">Create one</a></p>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
