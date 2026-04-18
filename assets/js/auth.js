// Simple client-side validation
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', (e) => {
      const pw = form.querySelector('input[name="password"]');
      if (pw && pw.value.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters.');
      }
    });
  });
});
