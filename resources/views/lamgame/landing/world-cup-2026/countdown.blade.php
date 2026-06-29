{{-- Countdown Section — Tournament is LIVE --}}
<section class="wc26-countdown" id="countdown">
    <div class="container">
        <h2 class="wc26-section__title">⏱️ World Cup 2026 — Đang diễn ra!</h2>
        <div class="wc26-countdown__timer" id="wc26-timer">
            <div class="wc26-countdown__item">
                <span class="wc26-countdown__number" id="wc26-days">--</span>
                <span class="wc26-countdown__label">Ngày còn lại</span>
            </div>
            <div class="wc26-countdown__item">
                <span class="wc26-countdown__number" id="wc26-hours">--</span>
                <span class="wc26-countdown__label">Giờ</span>
            </div>
            <div class="wc26-countdown__item">
                <span class="wc26-countdown__number" id="wc26-minutes">--</span>
                <span class="wc26-countdown__label">Phút</span>
            </div>
            <div class="wc26-countdown__item">
                <span class="wc26-countdown__number" id="wc26-seconds">--</span>
                <span class="wc26-countdown__label">Giây</span>
            </div>
        </div>
        <p class="wc26-countdown__desc">Đếm ngược đến Chung kết — 19/07/2026 tại New Jersey, Mỹ</p>
        <div class="wc26-countdown__stats">
            <div class="wc26-stat">
                <span class="wc26-stat__number">48</span>
                <span class="wc26-stat__label">Đội tuyển</span>
            </div>
            <div class="wc26-stat">
                <span class="wc26-stat__number">16</span>
                <span class="wc26-stat__label">Thành phố</span>
            </div>
            <div class="wc26-stat">
                <span class="wc26-stat__number">104</span>
                <span class="wc26-stat__label">Trận đấu</span>
            </div>
            <div class="wc26-stat">
                <span class="wc26-stat__number">3</span>
                <span class="wc26-stat__label">Quốc gia</span>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
(function(){
    // Countdown to Final: July 19, 2026 at 19:00 UTC-4 (EDT)
    const final = new Date('2026-07-19T23:00:00Z').getTime();
    function update() {
        const now = Date.now();
        const diff = final - now;
        if (diff <= 0) { document.getElementById('wc26-timer').innerHTML = '<p style="font-size:2rem;color:#fbbf24">🏆 Chung kết đã diễn ra!</p>'; return; }
        document.getElementById('wc26-days').textContent = Math.floor(diff / 86400000);
        document.getElementById('wc26-hours').textContent = Math.floor((diff % 86400000) / 3600000);
        document.getElementById('wc26-minutes').textContent = Math.floor((diff % 3600000) / 60000);
        document.getElementById('wc26-seconds').textContent = Math.floor((diff % 60000) / 1000);
    }
    update();
    setInterval(update, 1000);
})();
</script>
@endpush
