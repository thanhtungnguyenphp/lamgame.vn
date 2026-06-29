/**
 * LamGame Shared Wrapper — Splash + Branding + GameOver overlay
 * Inject vào tất cả mini games legacy để đồng bộ UI/UX
 * Usage: <script src="/games/_shared/wrapper.js"></script>
 */
(function() {
  'use strict';
  const BRAND = { name: 'LamGame', url: 'https://lamgame.vn', color: '#6C63FF', dark: '#1A1A2E' };

  // === LOAD SHARED CSS ===
  const css = document.createElement('link');
  css.rel = 'stylesheet'; css.href = '/_shared/wrapper.css';
  document.head.appendChild(css);

  // === SPLASH SCREEN ===
  const splash = document.createElement('div');
  splash.id = 'lg-splash';
  splash.innerHTML = `
    <div style="position:fixed;inset:0;z-index:99999;display:flex;flex-direction:column;align-items:center;justify-content:center;background:${BRAND.dark};transition:opacity .5s">
      <div style="font-size:32px;margin-bottom:12px">🎮</div>
      <div style="font-family:system-ui;font-size:20px;font-weight:bold;color:${BRAND.color}">${BRAND.name}</div>
      <div style="font-family:system-ui;font-size:12px;color:#888;margin-top:8px">Đang tải game...</div>
    </div>`;
  document.body.prepend(splash);

  // Hide splash after load
  window.addEventListener('load', function() {
    setTimeout(function() {
      const s = document.getElementById('lg-splash');
      if (s) { s.firstElementChild.style.opacity = '0'; setTimeout(() => s.remove(), 500); }
    }, 800);
  });

  // === FOOTER BRANDING ===
  function ensureFooter() {
    if (document.querySelector('.lg-brand-footer')) return;
    const footer = document.createElement('div');
    footer.className = 'lg-brand-footer';
    footer.style.cssText = 'text-align:center;padding:10px;background:#1A1A2E;color:#ccc;font-size:12px;font-family:system-ui;position:fixed;bottom:0;left:0;right:0;z-index:9998';
    footer.innerHTML = `<a href="${BRAND.url}" style="color:${BRAND.color};text-decoration:none;font-weight:bold">🎮 ${BRAND.name}</a> — <a href="${BRAND.url}/choi-game" style="color:#aaa;text-decoration:none">Chơi thêm game miễn phí</a>`;
    document.body.appendChild(footer);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ensureFooter);
  else ensureFooter();

  // === LEADERBOARD INTEGRATION ===
  window.LamGame = {
    submitScore: function(score) {
      const slug = window.location.pathname.split('/').filter(Boolean).pop() || 'unknown';
      const player = localStorage.getItem('lamgame_player') || 'Guest';
      fetch('/api/games/' + slug + '/leaderboard', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ player: player, score: score })
      }).catch(function(){});
    },
    setPlayer: function(name) {
      localStorage.setItem('lamgame_player', name);
    }
  };
})();
