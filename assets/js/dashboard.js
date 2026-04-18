// Admin sidebar toggle (mobile)
document.addEventListener('DOMContentLoaded', () => {
  const t = document.getElementById('sidebarToggle');
  const s = document.getElementById('adminSidebar');
  if (t && s) t.addEventListener('click', () => s.classList.toggle('hidden'));
});
