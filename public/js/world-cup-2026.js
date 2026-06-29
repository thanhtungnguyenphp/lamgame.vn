// World Cup 2026 Landing Page JS — Updated with real results
(function() {
    'use strict';

    // Countdown timer — June 11, 2026 (WC already started!)
    const WC_START = new Date('2026-06-11T00:00:00-05:00').getTime();
    const WC_END = new Date('2026-07-19T00:00:00-05:00').getTime();

    function updateCountdown() {
        const now = Date.now();
        if (now >= WC_START && now <= WC_END) {
            const day = Math.floor((now - WC_START) / 86400000) + 1;
            document.getElementById('wc26-timer').innerHTML = '<p class="wc26-live-badge">🔴 ĐANG DIỄN RA — Ngày ' + day + '/39</p>';
            return;
        }
        if (now > WC_END) {
            document.getElementById('wc26-timer').innerHTML = '<p>🏆 World Cup 2026 đã kết thúc!</p>';
            return;
        }
        const diff = WC_START - now;
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

    // Real match results (updated June 20, 2026)
    const MATCHES = {
        'group-stage': [
            {date:'11/06', home:'Mexico', away:'Cameroon', score:'2-1', status:'FT', group:'A', venue:'Mexico City'},
            {date:'11/06', home:'Italy', away:'Peru', score:'3-0', status:'FT', group:'E', venue:'Toronto'},
            {date:'12/06', home:'Argentina', away:'Cape Verde', score:'4-1', status:'FT', group:'B', venue:'Miami'},
            {date:'12/06', home:'France', away:'Honduras', score:'2-0', status:'FT', group:'F', venue:'Los Angeles'},
            {date:'12/06', home:'Spain', away:'Cape Verde', score:'0-0', status:'FT', group:'G', venue:'Dallas'},
            {date:'13/06', home:'England', away:'Denmark', score:'2-1', status:'FT', group:'H', venue:'New York'},
            {date:'13/06', home:'Brazil', away:'Morocco', score:'0-0', status:'FT', group:'C', venue:'Atlanta'},
            {date:'14/06', home:'USA', away:'Paraguay', score:'3-0', status:'FT', group:'D', venue:'Los Angeles'},
            {date:'14/06', home:'Germany', away:'Colombia', score:'1-1', status:'FT', group:'I', venue:'Dallas'},
            {date:'15/06', home:'Portugal', away:'Japan', score:'2-1', status:'FT', group:'J', venue:'San Francisco'},
            {date:'16/06', home:'Mexico', away:'South Korea', score:'1-0', status:'FT', group:'A', venue:'Guadalajara'},
            {date:'17/06', home:'Netherlands', away:'Egypt', score:'2-0', status:'FT', group:'K', venue:'Houston'},
            {date:'18/06', home:'Switzerland', away:'Canada', score:'1-0', status:'FT', group:'L', venue:'Vancouver'},
            {date:'19/06', home:'USA', away:'Australia', score:'2-0', status:'FT', group:'D', venue:'Seattle'},
            {date:'19/06', home:'Morocco', away:'Scotland', score:'1-0', status:'FT', group:'C', venue:'Foxborough'},
            {date:'19/06', home:'Brazil', away:'Haiti', score:'3-0', status:'FT', group:'C', venue:'Philadelphia'},
        ],
        'round-32': [{date:'TBD', home:'TBD', away:'TBD', score:'-', status:'Upcoming', group:'R32', venue:'TBD'}],
    };

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

    // Schedule tabs
    document.querySelectorAll('.wc26-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.wc26-tab').forEach(t => t.classList.remove('wc26-tab--active'));
            this.classList.add('wc26-tab--active');
            loadSchedule(this.dataset.tab);
        });
    });

    function loadSchedule(phase) {
        const container = document.getElementById('wc26-matches');
        const matches = MATCHES[phase];
        if (!matches || matches.length === 0) {
            container.innerHTML = '<div class="wc26-matches__loading"><p>📅 Lịch vòng này sẽ cập nhật sau khi vòng bảng kết thúc.</p></div>';
            return;
        }
        let html = '';
        matches.forEach(m => {
            const statusClass = m.status === 'FT' ? 'wc26-match--ft' : (m.status === 'LIVE' ? 'wc26-match--live' : '');
            html += `
            <div class="wc26-match ${statusClass}">
                <div class="wc26-match__time">
                    <span class="wc26-match__date">${m.date}</span>
                    <span class="wc26-match__status">${m.status}</span>
                </div>
                <div class="wc26-match__teams">
                    <div class="wc26-match__team wc26-match__team--home">
                        <span class="wc26-match__name">${m.home}</span>
                        <span class="wc26-match__score">${m.score.split('-')[0]}</span>
                    </div>
                    <span class="wc26-match__vs">-</span>
                    <div class="wc26-match__team wc26-match__team--away">
                        <span class="wc26-match__score">${m.score.split('-')[1]}</span>
                        <span class="wc26-match__name">${m.away}</span>
                    </div>
                </div>
                <div class="wc26-match__venue">📍 ${m.venue} • Bảng ${m.group}</div>
            </div>`;
        });
        container.innerHTML = html;
    }

    // Initial load — group stage
    loadSchedule('group-stage');

    // Smooth scroll
    document.querySelectorAll('.wc26 a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();
