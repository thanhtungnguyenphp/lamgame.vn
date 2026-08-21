{{-- Header V2 — Dark Theme (CSS-only mega menu) --}}
<header class="lg-v2-header" x-data="{ mobileOpen: false, searchOpen: false }">
    <div class="lg-v2-container" style="overflow:visible;">
        <nav class="lg-v2-nav">
            {{-- Logo --}}
            <a href="/" class="lg-v2-nav__logo">
                <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="8" fill="url(#logo-gradient)"/>
                    <path d="M8 12l8-4 8 4v8l-8 4-8-4v-8z" stroke="white" stroke-width="1.5" fill="none"/>
                    <circle cx="16" cy="16" r="3" fill="white"/>
                    <defs><linearGradient id="logo-gradient" x1="0" y1="0" x2="32" y2="32"><stop stop-color="#8B5CF6"/><stop offset="1" stop-color="#6366F1"/></linearGradient></defs>
                </svg>
                <span class="lg-v2-nav__logo-name">LAMGAME<span class="text-lg-accent">.VN</span></span>
            </a>

            {{-- Desktop Menu --}}
            <div class="lg-v2-nav__menu">
                {{-- Source Game dropdown --}}
                <div class="lg-v2-nav__dropdown">
                    <a href="{{ route('lamgame.source-game') }}" class="lg-v2-nav__link">Source Game <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"/></svg></a>
                    <div class="lg-v2-nav__mega">
                        <a href="{{ route('lamgame.source-game') }}?cat=unity" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🎮</span>
                            <div><strong>Unity Source</strong><span>Game templates & kits</span></div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=unreal" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🏗️</span>
                            <div><strong>Unreal Engine</strong><span>Blueprints & projects</span></div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=2d" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🎨</span>
                            <div><strong>2D Games</strong><span>Platformer, puzzle, RPG</span></div>
                        </a>
                        <a href="{{ route('lamgame.source-game') }}?cat=3d" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🌐</span>
                            <div><strong>3D Games</strong><span>FPS, racing, adventure</span></div>
                        </a>
                    </div>
                </div>

                {{-- AI Tools dropdown --}}
                <div class="lg-v2-nav__dropdown">
                    <a href="{{ route('lamgame.ai-tools') }}" class="lg-v2-nav__link">AI Tools <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"/></svg></a>
                    <div class="lg-v2-nav__mega">
                        <a href="{{ route('lamgame.ai-tools') }}#gdd" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">📝</span>
                            <div><strong>GDD Generator</strong><span>Tạo Game Design Document</span></div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#asset" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🖼️</span>
                            <div><strong>Asset Generator</strong><span>Tạo game assets bằng AI</span></div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#name" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">💡</span>
                            <div><strong>Name Generator</strong><span>Đặt tên game sáng tạo</span></div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}#story" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">📖</span>
                            <div><strong>Story Writer</strong><span>Viết cốt truyện game</span></div>
                        </a>
                    </div>
                </div>

                <a href="/blog" class="lg-v2-nav__link">Blog</a>
                <a href="{{ route('forum.index') }}" class="lg-v2-nav__link">Forum</a>
                <a href="/viec-lam-game" class="lg-v2-nav__link">Việc làm</a>

                {{-- Giải trí dropdown --}}
                <div class="lg-v2-nav__dropdown">
                    <a href="#" class="lg-v2-nav__link">Giải trí <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"/></svg></a>
                    <div class="lg-v2-nav__mega">
                        <a href="{{ route('lamgame.blog') }}?category=game-industry" class="lg-v2-nav__mega-item lg-v2-nav__mega-item--hot">
                            <span class="lg-v2-nav__mega-icon">🎮</span>
                            <div><strong>Game Industry <span class="lg-v2-nav__hot-tag">HOT</span></strong><span>Tin tức, xu hướng ngành game</span></div>
                        </a>
                        <a href="/choi-game" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🕹️</span>
                            <div><strong>Chơi Game</strong><span>50+ mini games HTML5</span></div>
                        </a>
                        <a href="{{ route('lamgame.ai-tools') }}" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">📰</span>
                            <div><strong>Game Industry</strong><span>Tin tức ngành game</span></div>
                        </a>
                        <a href="{{ route('lamgame.blog') }}?category=ai-game-dev" class="lg-v2-nav__mega-item">
                            <span class="lg-v2-nav__mega-icon">🤖</span>
                            <div><strong>AI Game Dev</strong><span>AI tools & tutorials</span></div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right actions --}}
            <div class="lg-v2-nav__actions">
                {{-- Search --}}
                <button @click="searchOpen = true" class="lg-v2-nav__action-btn" title="Tìm kiếm (Ctrl+K)">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <span class="lg-v2-nav__search-hint">Tìm kiếm...</span>
                    <kbd class="lg-v2-nav__kbd">⌘K</kbd>
                </button>

                {{-- Cart --}}
                <a href="/checkout/cart" class="lg-v2-nav__action-btn lg-v2-nav__action-btn--icon" title="Giỏ hàng">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                </a>

                {{-- Auth --}}
                @guest
                <a href="{{ route('shop.customer.session.index') }}" class="lg-v2-btn lg-v2-btn--primary lg-v2-btn--sm">Đăng nhập</a>
                @else
                <a href="/customer/account" class="lg-v2-nav__action-btn lg-v2-nav__action-btn--icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                @endguest

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="lg-v2-nav__hamburger">
                    <svg x-show="!mobileOpen" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    <svg x-show="mobileOpen" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </nav>
    </div>

    {{-- Mobile Drawer --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150" class="lg-v2-nav__mobile-drawer">
        <a href="/" class="lg-v2-nav__mobile-link">🏠 Trang chủ</a>
        <a href="{{ route('lamgame.source-game') }}" class="lg-v2-nav__mobile-link">🎮 Source Game</a>
        <a href="{{ route('lamgame.ai-tools') }}" class="lg-v2-nav__mobile-link">🤖 AI Tools</a>
        <a href="/blog" class="lg-v2-nav__mobile-link">📝 Blog</a>
        <a href="{{ route('forum.index') }}" class="lg-v2-nav__mobile-link">💬 Forum</a>
        <a href="/viec-lam-game" class="lg-v2-nav__mobile-link">💼 Việc làm</a>
        <div class="lg-v2-nav__mobile-divider"></div>
        <a href="/choi-game" class="lg-v2-nav__mobile-link">🕹️ Chơi Game</a>
        <a href="{{ route('lamgame.blog') }}?category=game-industry" class="lg-v2-nav__mobile-link">📰 Game Industry</a>
        <a href="{{ route('lamgame.blog') }}?category=ai-game-dev" class="lg-v2-nav__mobile-link">🤖 AI Game Dev</a>
        <div class="lg-v2-nav__mobile-divider"></div>
        @guest
        <a href="{{ route('shop.customer.session.index') }}" class="lg-v2-btn lg-v2-btn--primary" style="width:100%;justify-content:center;">Đăng nhập</a>
        @endguest
    </div>

    {{-- Search Modal (Ctrl+K) --}}
    <div x-show="searchOpen" x-transition @keydown.escape.window="searchOpen = false" @keydown.ctrl.k.window.prevent="searchOpen = !searchOpen" class="lg-v2-search-modal" @click.self="searchOpen = false">
        <div class="lg-v2-search-modal__content">
            <div class="lg-v2-search-modal__input-wrap">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Tìm source game, AI tools, bài viết..." class="lg-v2-search-modal__input">
                <kbd class="lg-v2-nav__kbd">ESC</kbd>
            </div>
            <div class="lg-v2-search-modal__results">
                <p class="lg-v2-search-modal__hint">Nhập từ khóa để tìm kiếm...</p>
            </div>
        </div>
    </div>
</header>

<style>
/* ===== HEADER ===== */
.lg-v2-header {
    position: sticky;
    top: 0;
    z-index: 50;
    background: rgba(13, 13, 26, 0.9);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--lg-border);
}

.lg-v2-nav {
    display: flex;
    align-items: center;
    height: 56px;
    gap: 0.5rem;
}

/* Logo */
.lg-v2-nav__logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    flex-shrink: 0;
    margin-right: 1rem;
}

.lg-v2-nav__logo-name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--lg-text);
}

/* Menu */
.lg-v2-nav__menu {
    display: none;
    align-items: center;
    gap: 0;
    flex: 1;
}

@media (min-width: 1024px) {
    .lg-v2-nav__menu {
        display: flex;
    }
}

.lg-v2-nav__link {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    padding: 0.375rem 0.625rem;
    font-size: 0.8125rem;
    font-weight: 400;
    color: var(--lg-text-secondary);
    text-decoration: none;
    border-radius: var(--lg-radius-tag);
    transition: all 0.15s ease;
    white-space: nowrap;
}

.lg-v2-nav__link:hover {
    color: var(--lg-text);
    background: var(--lg-bg-tertiary);
}

.lg-v2-nav__link--active {
    color: var(--lg-text);
    font-weight: 500;
}

/* Dropdown — CSS only hover */
.lg-v2-nav__dropdown {
    position: relative;
}

.lg-v2-nav__mega {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    min-width: 260px;
    padding: 0.5rem;
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-card);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    z-index: 100;
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    margin-top: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-50%) translateY(8px);
    transition: all 0.15s ease;
    pointer-events: none;
}

.lg-v2-nav__dropdown:hover .lg-v2-nav__mega {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
    pointer-events: auto;
}

.lg-v2-nav__mega-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.5rem 0.625rem;
    border-radius: var(--lg-radius-btn);
    text-decoration: none;
    transition: background 0.15s ease;
}

.lg-v2-nav__mega-item:hover {
    background: var(--lg-bg-tertiary);
}

.lg-v2-nav__mega-item--hot {
    border: 1px solid rgba(245, 158, 11, 0.3);
    background: rgba(245, 158, 11, 0.05);
}

.lg-v2-nav__mega-icon {
    font-size: 1.125rem;
    width: 28px;
    text-align: center;
    flex-shrink: 0;
}

.lg-v2-nav__mega-item div {
    display: flex;
    flex-direction: column;
}

.lg-v2-nav__mega-item strong {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--lg-text);
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.lg-v2-nav__mega-item div > span {
    font-size: 0.6875rem;
    color: var(--lg-text-muted);
}

.lg-v2-nav__hot-tag {
    padding: 0.1rem 0.3rem;
    font-size: 0.5625rem;
    font-weight: 700;
    color: white;
    background: #EF4444;
    border-radius: 3px;
}

/* Right actions */
.lg-v2-nav__actions {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    margin-left: auto;
    flex-shrink: 0;
}

.lg-v2-nav__action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.4rem 0.625rem;
    color: var(--lg-text-secondary);
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-btn);
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;
    font-size: 0.75rem;
}

.lg-v2-nav__action-btn:hover {
    color: var(--lg-text);
    border-color: var(--lg-border-light);
}

.lg-v2-nav__action-btn--icon {
    padding: 0.4rem;
}

.lg-v2-nav__search-hint {
    display: none;
    color: var(--lg-text-muted);
    font-size: 0.75rem;
}

@media (min-width: 1024px) {
    .lg-v2-nav__search-hint {
        display: inline;
    }
}

.lg-v2-nav__kbd {
    display: none;
    padding: 0.1rem 0.3rem;
    font-size: 0.5625rem;
    font-family: inherit;
    color: var(--lg-text-muted);
    background: var(--lg-bg-tertiary);
    border: 1px solid var(--lg-border);
    border-radius: 3px;
}

@media (min-width: 1024px) {
    .lg-v2-nav__kbd {
        display: inline;
    }
}

/* Hamburger */
.lg-v2-nav__hamburger {
    display: flex;
    padding: 0.375rem;
    color: var(--lg-text);
    background: none;
    border: none;
    cursor: pointer;
}

@media (min-width: 1024px) {
    .lg-v2-nav__hamburger {
        display: none;
    }
}

/* Mobile Drawer */
.lg-v2-nav__mobile-drawer {
    display: flex;
    flex-direction: column;
    padding: 0.75rem 1rem;
    background: var(--lg-bg-secondary);
    border-bottom: 1px solid var(--lg-border);
    max-height: 80vh;
    overflow-y: auto;
}

@media (min-width: 1024px) {
    .lg-v2-nav__mobile-drawer {
        display: none !important;
    }
}

.lg-v2-nav__mobile-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 0.75rem;
    font-size: 0.875rem;
    color: var(--lg-text-secondary);
    text-decoration: none;
    border-radius: var(--lg-radius-tag);
}

.lg-v2-nav__mobile-link:hover {
    color: var(--lg-text);
    background: var(--lg-bg-tertiary);
}

.lg-v2-nav__mobile-divider {
    height: 1px;
    background: var(--lg-border);
    margin: 0.375rem 0;
}

/* Search Modal */
.lg-v2-search-modal {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 20vh;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
}

.lg-v2-search-modal__content {
    width: 100%;
    max-width: 560px;
    margin: 0 1rem;
    background: var(--lg-bg-secondary);
    border: 1px solid var(--lg-border);
    border-radius: var(--lg-radius-card);
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.lg-v2-search-modal__input-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--lg-border);
    color: var(--lg-text-muted);
}

.lg-v2-search-modal__input {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    font-size: 0.9375rem;
    color: var(--lg-text);
    font-family: inherit;
}

.lg-v2-search-modal__input::placeholder {
    color: var(--lg-text-muted);
}

.lg-v2-search-modal__results {
    padding: 1.25rem;
    max-height: 300px;
    overflow-y: auto;
}

.lg-v2-search-modal__hint {
    text-align: center;
    color: var(--lg-text-muted);
    font-size: 0.8125rem;
}
</style>
