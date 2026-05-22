// LAM GAME — Dark Mode Toggle
(function() {
  const STORAGE_KEY = 'lamgame-theme';

  function getPreferred() {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function apply(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
    document.querySelectorAll('[data-theme-toggle]').forEach(el => {
      el.setAttribute('aria-pressed', theme === 'dark');
    });
  }

  function toggle() {
    const current = document.documentElement.getAttribute('data-theme') || getPreferred();
    apply(current === 'dark' ? 'light' : 'dark');
  }

  // Init on load
  apply(getPreferred());

  // Listen for system changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem(STORAGE_KEY)) apply(e.matches ? 'dark' : 'light');
  });

  // Expose globally
  window.LamGameTheme = { toggle, apply, getPreferred };

  // Auto-bind toggle buttons
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
      btn.addEventListener('click', toggle);
    });
  });
})();
