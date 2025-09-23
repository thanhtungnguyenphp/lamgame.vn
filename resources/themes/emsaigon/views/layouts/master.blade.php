<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('page_title', 'Làm Game • Học lập trình game từ cơ bản đến nâng cao')</title>
    <meta name="description" content="@yield('page_description', 'Làm Game - Trung tâm đào tạo lập trình game chuyên nghiệp. Học Unity, Unreal Engine, C#, Game Design từ cơ bản đến nâng cao.')" />
    <meta name="keywords" content="@yield('page_keywords', 'làm game, lập trình game, Unity, Unreal Engine, C#, Game Design, học lập trình, game development')" />
    <meta name="theme-color" content="#667eea" />
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'Làm Game - Trung tâm đào tạo lập trình game')" />
    <meta property="og:description" content="@yield('og_description', 'Học Unity, Unreal Engine, C#, Game Design từ cơ bản đến nâng cao. Cam kết việc làm sau khóa học.')" />
    <meta property="og:image" content="@yield('og_image', asset('logo/lamgame-horizontal.svg'))" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="icon" type="image/png" href="{{ asset('logo/lamgame-logo.png') }}" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @stack('meta')
    
    <!-- Styles -->
    @stack('styles')
    
    <!-- LamGame Branding CSS -->
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/lamgame-branding.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/lamgame-homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/hero-banner-v2.css') }}">
    
    @vite(['resources/themes/emsaigon/assets/scss/app.scss'], 'themes/shop/emsaigon/build')
</head>

<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="wrap row">
            <a href="{{ route('shop.home.index') }}" class="brand" aria-label="Trang chủ Làm Game">
                <img src="{{ asset('logo/lamgame-horizontal.svg') }}" alt="Làm Game Logo" onerror="this.src='{{ asset('logo/lamgame-logo.png') }}'" style="height: 48px; width: auto;" />
                <span class="title">Làm Game</span>
            </a>
            <nav aria-label="Điều hướng chính">
                <button class="menu-btn" aria-label="Mở menu" onclick="toggleMenu()">☰</button>
                <ul id="nav-menu">
                    <li><a href="#khoa-hoc">Khóa học</a></li>
                    <li><a href="{{ route('lamgame.blog') }}">Blog</a></li>
                    <li><a href="{{ route('lamgame.source-game') }}">Source Game</a></li>
                    <li><a href="{{ route('forum.index') }}">Forum</a></li>
                    <li><a href="{{ route('lamgame.viec-lam-game') }}">Việc làm</a></li>
                    <li><a href="#lien-he">Liên hệ</a></li>
                    <li><button class="cta" onclick="scrollToSection('#khoa-hoc');trackCTA('header_courses')">Khám phá khóa học</button></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="lien-he">
        <div class="section-wrap foot">
            <div>
                <h3>Làm Game</h3>
                <p class="muted">Học lập trình game • Unity • Unreal Engine • C# Programming</p>
                <p class="muted">Website: <strong>{{ config('app.url') }}</strong></p>
                <p class="muted">📧 Email: info@lamgame.vn</p>
                <p class="muted">📞 Hotline: 0909 123 456</p>
                <p class="muted">📍 Địa chỉ: Tầng 7, Tòa nhà ABC, 123 Nguyễn Huế, Quận 1, TP.HCM</p>
            </div>
            <div>
                <div class="socials">
                    <a href="https://facebook.com/lamgamevn" aria-label="Facebook" target="_blank">f</a>
                    <a href="https://zalo.me/lamgamevn" aria-label="Zalo" target="_blank">Z</a>
                    <a href="https://youtube.com/@lamgamevn" aria-label="YouTube" target="_blank">Y</a>
                    <a href="https://tiktok.com/@lamgamevn" aria-label="TikTok" target="_blank">t</a>
                </div>
                <p class="muted mt-12">© {{ date('Y') }} Làm Game. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Contact Buttons -->
    <div class="floating" aria-label="Liên hệ nhanh">
        <a href="https://m.me/lamgamevn" title="Facebook Messenger" target="_blank">M</a>
        <a href="https://zalo.me/lamgamevn" title="Zalo" target="_blank">Z</a>
        <a href="tel:0909123456" title="Gọi ngay" target="_blank">📞</a>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script src="{{ asset('themes/shop/emsaigon/assets/js/hero-banner-v2.js') }}"></script>
    @vite(['resources/themes/emsaigon/assets/js/app.js'], 'themes/shop/emsaigon/build')
    
    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        function toggleMenu() {
            const menu = document.getElementById('nav-menu');
            const menuBtn = document.querySelector('.menu-btn');
            
            menu.classList.toggle('active');
            
            // Update menu button icon
            menuBtn.innerHTML = menu.classList.contains('active') ? '✕' : '☰';
        }

        // Smooth scrolling
        function scrollToSection(selector) {
            document.querySelector(selector)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Close menu when clicking nav links (mobile)
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                const menu = document.getElementById('nav-menu');
                const menuBtn = document.querySelector('.menu-btn');
                
                if (window.innerWidth < 769 && menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    menuBtn.innerHTML = '☰';
                }
            });
        });

        // Tracking functions for LamGame analytics
        function trackCTA(action) {
            console.log('LamGame CTA clicked:', action);
            // Integration with Google Analytics, Facebook Pixel, etc.
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'CTA',
                    'event_label': action,
                    'value': 1
                });
            }
        }

        function trackRegistration() {
            console.log('Course registration attempted');
            // Track course registration events
            if (typeof gtag !== 'undefined') {
                gtag('event', 'begin_checkout', {
                    'event_category': 'Course',
                    'event_label': 'registration_started'
                });
            }
        }
    </script>
</body>
</html>
