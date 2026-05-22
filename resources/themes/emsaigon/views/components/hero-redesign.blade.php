{{-- HERO SECTION REDESIGN — Modern gradient + animated stats + CTA --}}
<section class="hero-redesign" id="hero-banner" aria-label="Banner chính LamGame.vn">
    {{-- Particles --}}
    <div class="hero-redesign__particles" aria-hidden="true">
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
        <div class="hero-redesign__particle"></div>
    </div>
    <div class="hero-redesign__overlay" aria-hidden="true"></div>

    <div class="hero-redesign__container">
        {{-- Content --}}
        <div class="hero-redesign__content">
            <div class="hero-redesign__badge">
                <span class="hero-redesign__badge-dot"></span>
                Cộng đồng Game Dev #1 Việt Nam
            </div>

            <h1 class="hero-redesign__title">
                Xây dựng sự nghiệp<br>
                <span class="hero-redesign__title-highlight">Game Developer</span>
            </h1>

            <p class="hero-redesign__subtitle">
                Kết nối với hàng nghìn developer, khám phá việc làm hot, chia sẻ source code và phát triển game cùng cộng đồng.
            </p>

            <div class="hero-redesign__cta">
                <a href="{{ route('lamgame.viec-lam-game') }}" class="hero-redesign__btn hero-redesign__btn--primary">
                    <i class="fa fa-briefcase"></i> Khám phá việc làm
                </a>
                <a href="#forum" class="hero-redesign__btn hero-redesign__btn--secondary">
                    <i class="fa fa-users"></i> Tham gia cộng đồng
                </a>
            </div>

            <div class="hero-redesign__stats">
                <div class="hero-redesign__stat">
                    <div class="hero-redesign__stat-value" data-count="{{ $stats['members'] ?? 2500 }}">0</div>
                    <div class="hero-redesign__stat-label">Thành viên</div>
                </div>
                <div class="hero-redesign__stat">
                    <div class="hero-redesign__stat-value" data-count="{{ $stats['games'] ?? 120 }}">0</div>
                    <div class="hero-redesign__stat-label">Game & Source</div>
                </div>
                <div class="hero-redesign__stat">
                    <div class="hero-redesign__stat-value" data-count="{{ $stats['jobs'] ?? 50 }}">0</div>
                    <div class="hero-redesign__stat-label">Việc làm mới/tuần</div>
                </div>
            </div>
        </div>

        {{-- Visual --}}
        <div class="hero-redesign__visual">
            <div class="hero-redesign__visual-card">
                <img src="{{ asset('images/hero-game-dev.webp') }}" alt="Game Development Community" loading="eager" width="400" height="300">
                <div class="hero-redesign__visual-float hero-redesign__visual-float--top">
                    🔥 +12 jobs hôm nay
                </div>
                <div class="hero-redesign__visual-float hero-redesign__visual-float--bottom">
                    ⭐ 4.9/5 đánh giá
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="hero-redesign__scroll" aria-hidden="true">
        <span>Cuộn xuống</span>
        <div class="hero-redesign__scroll-arrow"></div>
    </div>
</section>

@push('scripts')
<script>
// Count-up animation for stats
(function() {
    function animateCount(el) {
        const target = parseInt(el.getAttribute('data-count'), 10);
        const duration = 2000;
        const start = performance.now();
        
        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
            el.textContent = Math.round(target * eased).toLocaleString() + (target >= 100 ? '+' : '+');
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    }

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('[data-count]').forEach(animateCount);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    document.addEventListener('DOMContentLoaded', function() {
        const stats = document.querySelector('.hero-redesign__stats');
        if (stats) observer.observe(stats);
    });
})();
</script>
@endpush
