// Mobile nav toggle
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('navToggle');
  const menu = document.getElementById('navMenu');
  if (btn && menu) btn.addEventListener('click', () => menu.classList.toggle('hidden'));
});
