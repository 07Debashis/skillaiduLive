<?php $page_title = 'Sign up'; require_once __DIR__ . '/includes/header.php'; ?>
<section class="hero-gradient min-h-[80vh] flex items-center">
  <div class="container-x grid md:grid-cols-2 gap-10 py-16">
    <div class="hidden md:block">
      <span class="badge">Join Skilledu</span>
      <h1 class="text-4xl md:text-5xl font-extrabold mt-4 leading-tight">Start learning <span style="background:linear-gradient(135deg,#06b6d4,#3b82f6);-webkit-background-clip:text;color:transparent">in minutes</span></h1>
      <p class="text-white/60 mt-4 max-w-md">Create a free account to enroll in courses, track progress and earn skills that matter.</p>
      <ul class="mt-6 space-y-2 text-white/70 text-sm">
        <li>✓ Access curated, expert-led courses</li>
        <li>✓ Learn at your own pace</li>
        <li>✓ First user becomes admin automatically</li>
      </ul>
    </div>
    <div class="card p-8 fade-up">
      <h2 class="text-2xl font-bold mb-1">Create account</h2>
      <p class="text-white/50 text-sm mb-6">It only takes a few seconds.</p>
      <?php if ($err = flash_get('error')): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm"><?= e($err) ?></div>
      <?php endif; ?>
      <form method="POST" action="/auth/register-process.php" data-validate class="space-y-4">
        <div><label class="text-sm text-white/70">Full name</label><input class="input mt-1" name="full_name" required></div>
        <div><label class="text-sm text-white/70">Email</label><input class="input mt-1" type="email" name="email" required></div>
        <div><label class="text-sm text-white/70">Password</label><input class="input mt-1" type="password" name="password" minlength="6" required></div>
        <button class="btn-brand w-full" type="submit">Create account</button>
      </form>
      <p class="text-sm text-white/60 mt-4">Already have an account? <a href="/login.php" class="text-cyan-400 hover:underline">Sign in</a></p>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
