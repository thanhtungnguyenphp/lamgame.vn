// World Cup 2026 Landing Page JS
(function() {
    'use strict';

    // Countdown timer — June 11, 2026 00:00 UTC-5 (EST)
    const WC_START = new Date('2026-06-11T00:00:00-05:00').getTime();

    function updateCountdown() {
        const now = Date.now();
        const diff = WC_START - now;
        if (diff <= 0) {
            document.getElementById('wc26-timer').innerHTML = '<p style="font-size:1.5rem;color:var(--wc-gold)">🎉 World Cup 2026 đang diễn ra!</p>';
            return;
        }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        document.getElementById('wc26-days').textContent = d;
        document.getElementById('wc26-hours').textContent = h.toString().padStart(2, '0');
        document.getElementById('wc26-minutes').textContent = m.toString().padStart(2, '0');
        document.getElementById('wc26-seconds').textContent = s.toString().padStart(2, '0');
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Team filter
    document.querySelectorAll('.wc26-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.wc26-filter-btn').forEach(b => b.classList.remove('wc26-filter-btn--active'));
            this.classList.add('wc26-filter-btn--active');
            const conf = this.dataset.conf;
            document.querySelectorAll('.wc26-team-card').forEach(card => {
                card.hidden = conf !== 'all' && card.dataset.conf !== conf;
            });
        });
    });

    // Schedule tabs (placeholder — will load from API when available)
    document.querySelectorAll('.wc26-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.wc26-tab').forEach(t => t.classList.remove('wc26-tab--active'));
            this.classList.add('wc26-tab--active');
            loadSchedule(this.dataset.tab);
        });
    });

    function loadSchedule(phase) {
        const container = document.getElementById('wc26-matches');
        container.innerHTML = '<div class="wc26-matches__loading"><p>📅 Lịch thi đấu ' + phase.replace('-', ' ') + ' sẽ được cập nhật khi FIFA công bố chính thức.</p></div>';
    }

    // Initial load
    document.getElementById('wc26-matches').innerHTML = '<div class="wc26-matches__loading"><p>📅 Lịch thi đấu chi tiết sẽ được cập nhật sau lễ bốc thăm chia bảng.</p><p style="margin-top:0.5rem;font-size:0.85rem;color:#888">Dự kiến: Tháng 12/2025</p></div>';

    // Smooth scroll for anchor links
    document.querySelectorAll('.wc26 a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();
