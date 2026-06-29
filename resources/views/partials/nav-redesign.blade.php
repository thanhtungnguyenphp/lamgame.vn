{{-- NAVIGATION REDESIGN — Sticky header + mega menu + search bar --}}
<header class="nav-redesign" id="nav-header">
    <div class="nav-redesign__inner">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="nav-redesign__logo" aria-label="Trang chủ Làm Game">
            <img src="{{ asset('assets/logos/svg/logo-dark.svg') . '?v=' . time() }}" alt="LamGame.vn">
        </a>

        {{-- Desktop Menu --}}
        <ul class="nav-redesign__menu" role="menubar">
            <li class="nav-redesign__item">
                <a href="{{ route('lamgame.blog') }}" class="nav-redesign__link {{ request()->routeIs('lamgame.blog*') ? 'nav-redesign__link--active' : '' }}">Blog</a>
            </li>
            <li class="nav-redesign__item">
                <a href="{{ route('lamgame.source-game') }}" class="nav-redesign__link {{ request()->routeIs('lamgame.source-game*') ? 'nav-redesign__link--active' : '' }}">
                    Source Game <span class="nav-redesign__link-arrow">▾</span>
                </a>
                <div class="nav-redesign__mega">
                    <div class="nav-redesign__mega-grid">
                        <a href="{{ route('lamgame.source-game') }}?cat=unity" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🎮</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Unity Source</h4>
                                <p>Game templates & kits</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=unreal" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🏗️</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Unreal Engine</h4>
                                <p>Blueprints & projects</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=2d" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🎨</div>
                            <div class="nav-redesign__mega-text">
                                <h4>2D Games</h4>
                                <p>Platformer, puzzle, RPG</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=3d" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🌐</div>
                            <div class="nav-redesign__mega-text">
                                <h4>3D Games</h4>
                                <p>FPS, racing, adventure</p>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            <li class="nav-redesign__item">
                <a href="{{ route('forum.index') }}" class="nav-redesign__link {{ request()->routeIs('forum*') ? 'nav-redesign__link--active' : '' }}">Forum</a>
            </li>
            <li class="nav-redesign__item">
                <a href="{{ route('lamgame.viec-lam-game') }}" class="nav-redesign__link {{ request()->routeIs('lamgame.viec-lam*') ? 'nav-redesign__link--active' : '' }}">Việc làm</a>
            </li>
            <li class="nav-redesign__item">
                <a href="{{ route('lamgame.ai-tools') }}" class="nav-redesign__link {{ request()->routeIs('lamgame.ai-tools*') ? 'nav-redesign__link--active' : '' }}">
                    AI Tools <span class="nav-redesign__link-arrow">▾</span>
                </a>
                <div class="nav-redesign__mega">
                    <div class="nav-redesign__mega-grid">
                        <a href="{{ route('lamgame.ai-tools') }}#gdd" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">📝</div>
                            <div class="nav-redesign__mega-text">
                                <h4>GDD Generator</h4>
                                <p>Tạo Game Design Document</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#asset" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🖼️</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Asset Generator</h4>
                                <p>Tạo game assets bằng AI</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#name" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">💡</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Name Generator</h4>
                                <p>Đặt tên game sáng tạo</p>
                            </div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#story" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">📖</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Story Writer</h4>
                                <p>Viết cốt truyện game</p>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            <li class="nav-redesign__item">
                <a href="#" class="nav-redesign__link {{ request()->routeIs('sport*') || request()->routeIs('lottery*') || request()->routeIs('mini-game.*') ? 'nav-redesign__link--active' : '' }}">
                    Giải trí <span class="nav-redesign__link-arrow">▾</span>
                </a>
                <div class="nav-redesign__mega">
                    <div class="nav-redesign__mega-grid">
                        <a href="{{ route('world-cup-2026') }}" class="nav-redesign__mega-item nav-redesign__mega-item--hot">
                            <div class="nav-redesign__mega-icon">🏆</div>
                            <div class="nav-redesign__mega-text">
                                <h4>World Cup 2026 <span class="nav-redesign__hot-badge">HOT</span></h4>
                                <p>Lịch thi đấu, kết quả, tin tức</p>
                            </div>
                        </a>
                        <a href="{{ route('mini-game.index') }}" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🕹️</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Chơi Game</h4>
                                <p>50+ mini games HTML5</p>
                            </div>
                        </a>
                        <a href="{{ route('sport.index') }}" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">⚽</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Thể thao</h4>
                                <p>Lịch thi đấu, BXH, live score</p>
                            </div>
                        </a>
                        <a href="{{ route('lottery.index') }}" class="nav-redesign__mega-item">
                            <div class="nav-redesign__mega-icon">🎰</div>
                            <div class="nav-redesign__mega-text">
                                <h4>Xổ số</h4>
                                <p>KQXS 3 miền, Vietlott</p>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
        </ul>

        {{-- Right Actions --}}
        <div class="nav-redesign__actions">
            {{-- Search --}}
            <div class="nav-redesign__search" id="nav-search">
                <input type="text" class="nav-redesign__search-input" placeholder="Tìm kiếm..." aria-label="Tìm kiếm">
                <button class="nav-redesign__search-btn" aria-label="Tìm kiếm" onclick="toggleNavSearch()">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            {{-- Dark mode --}}
            <button class="nav-redesign__theme-btn" data-theme-toggle aria-label="Chuyển đổi giao diện sáng/tối">
                <i class="fa fa-moon-o"></i>
            </button>

            {{-- Notifications --}}
            <div class="nav-redesign__notif-wrap" id="notif-wrap">
                <button class="nav-redesign__notif" aria-label="Thông báo" onclick="toggleNotifDropdown()">
                    <i class="fa fa-bell-o"></i>
                    <span class="nav-redesign__notif-badge" id="notif-badge">0</span>
                </button>
                <div class="nav-redesign__notif-dropdown" id="notif-dropdown">
                    <div class="notif-dropdown__header">
                        <strong>Thông báo</strong>
                        <a href="#" onclick="markAllRead()" class="notif-dropdown__mark-read">Đánh dấu đã đọc</a>
                    </div>
                    <div class="notif-dropdown__list" id="notif-list">
                        <p class="notif-dropdown__empty">Không có thông báo mới</p>
                    </div>
                    @auth('customer')
                    <a href="{{ route('shop.customers.account.profile.index') }}" class="notif-dropdown__footer">Xem tất cả →</a>
                    @endauth
                </div>
            </div>

            {{-- CTA / User --}}
            @guest('customer')
                <a href="{{ route('auth.login') }}" class="nav-redesign__cta">Đăng nhập</a>
            @else
                <a href="{{ route('shop.customers.account.profile.index') }}" class="ds-avatar ds-avatar--sm">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('customer')->user()->first_name) }}&size=32" alt="{{ auth('customer')->user()->first_name }}">
                </a>
            @endguest

            {{-- Mobile toggle --}}
            <button class="nav-redesign__mobile-btn" id="mobile-menu-btn" aria-label="Mở menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

{{-- Mobile Menu --}}
<div class="nav-redesign__mobile-backdrop" id="mobile-backdrop"></div>
<nav class="nav-redesign__mobile-menu" id="mobile-menu" aria-label="Menu di động">
    <ul class="nav-redesign__mobile-nav">
        <li><a href="{{ route('world-cup-2026') }}">🏆 World Cup 2026 <span class="nav-redesign__hot-badge">HOT</span></a></li>
        <li><a href="{{ route('lamgame.blog') }}">📝 Blog</a></li>
        <li><a href="{{ route('lamgame.source-game') }}">🎮 Source Game</a></li>
        <li><a href="{{ route('forum.index') }}">💬 Forum</a></li>
        <li><a href="{{ route('lamgame.viec-lam-game') }}">💼 Việc làm</a></li>
        <li><a href="{{ route('lamgame.ai-tools') }}">🤖 AI Tools</a></li>
        <li><a href="{{ route('mini-game.index') }}">🕹️ Chơi Game</a></li>
        <li><a href="{{ route('sport.index') }}">⚽ Thể thao</a></li>
        <li><a href="{{ route('lottery.index') }}">🎰 Xổ số</a></li>
    </ul>
</nav>

@push('scripts')
<script>
(function() {
    const header = document.getElementById('nav-header');
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const backdrop = document.getElementById('mobile-backdrop');

    // Sticky scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        const y = window.scrollY;
        if (y > 50) {
            header.classList.add('nav-redesign--scrolled');
            header.classList.remove('nav-redesign--transparent');
        } else {
            header.classList.remove('nav-redesign--scrolled');
        }
        lastScroll = y;
    }, { passive: true });

    // Mobile menu
    function toggleMobile() {
        const isOpen = mobileMenu.classList.contains('active');
        mobileMenu.classList.toggle('active');
        backdrop.classList.toggle('active');
        mobileBtn.classList.toggle('active');
        mobileBtn.setAttribute('aria-expanded', !isOpen);
        document.body.style.overflow = isOpen ? '' : 'hidden';
    }

    mobileBtn.addEventListener('click', toggleMobile);
    backdrop.addEventListener('click', toggleMobile);

    // Close mobile menu on link click
    mobileMenu.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            if (mobileMenu.classList.contains('active')) toggleMobile();
        });
    });
})();

// Search toggle
function toggleNavSearch() {
    document.getElementById('nav-search').classList.toggle('active');
    var input = document.querySelector('.nav-redesign__search-input');
    if (document.getElementById('nav-search').classList.contains('active')) {
        input.focus();
    }
}

// Notifications
function toggleNotifDropdown() {
    var dd = document.getElementById('notif-dropdown');
    dd.classList.toggle('active');
}
function markAllRead() {
    var badge = document.getElementById('notif-badge');
    badge.classList.remove('has-notif');
    badge.textContent = '0';
    document.getElementById('notif-list').innerHTML = '<p class="notif-dropdown__empty">Không có thông báo mới</p>';
}
// Show badge only when count > 0
function updateNotifBadge(count) {
    var badge = document.getElementById('notif-badge');
    if (count > 0) {
        badge.classList.add('has-notif');
        badge.textContent = count > 99 ? '99+' : count;
    } else {
        badge.classList.remove('has-notif');
    }
}
// Close dropdown on outside click
document.addEventListener('click', function(e) {
    var wrap = document.getElementById('notif-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notif-dropdown').classList.remove('active');
    }
});
</script>
@endpush
