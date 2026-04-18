</main>
<footer class="border-t border-white/5 mt-20">
  <div class="container-x py-10 grid md:grid-cols-3 gap-6 text-sm text-white/60">
    <div>
      <div class="flex items-center gap-2 mb-3">
        <span class="inline-block w-7 h-7 rounded-md" style="background:linear-gradient(135deg,#06b6d4,#3b82f6)"></span>
        <span class="font-bold text-white">Skillaidu</span>
      </div>
      <p>Learn modern skills with curated courses from industry experts.</p>
    </div>
    <div>
      <h4 class="text-white font-semibold mb-2">Explore</h4>
      <ul class="space-y-1">
        <li><a href="/pages/courses.php" class="hover:text-white">All courses</a></li>
        <li><a href="/index.php" class="hover:text-white">Home</a></li>
      </ul>
    </div>
    <div>
      <h4 class="text-white font-semibold mb-2">Account</h4>
      <ul class="space-y-1">
        <li><a href="/login.php" class="hover:text-white">Login</a></li>
        <li><a href="/signup.php" class="hover:text-white">Sign up</a></li>
      </ul>
    </div>
  </div>
  <div class="container-x py-4 border-t border-white/5 text-xs text-white/40 flex justify-between">
    <span>© <?= date('Y') ?> Skillaidu. All rights reserved.</span>
    <!--<span>Built with PHP + MySQL</span>-->
  </div>
</footer>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/auth.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
