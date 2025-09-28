<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'LAMGAME • Làm Game - Học Lập Trình Game và Phát Triển Ứng Dụng')</title>
    <meta name="description" content="@yield('page_description', 'Làm Game - Nền tảng học lập trình game, phát triển ứng dụng và các khóa học lập trình chuyên sâu. Bắt đầu hành trình của bạn ngay hôm nay!')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('page_title', 'LAMGAME • Làm Game - Học Lập Trình Game và Phát Triển Ứng Dụng')">
    <meta property="og:description" content="@yield('page_description', 'Làm Game - Nền tảng học lập trình game, phát triển ứng dụng và các khóa học lập trình chuyên sâu. Bắt đầu hành trình của bạn ngay hôm nay!')">
    <meta property="og:image" content="{{ asset('logo/lamgame-logo.svg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/lamgame-logo.png') }}"
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    {{-- @bagistoVite(['resources/themes/emsaigon/assets/css/app.scss'], 'shop-emsaigon') --}}
    
    <!-- Basic Styles -->
    <style>
        /* Reset and base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        .header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        .header-content { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .brand { display: flex; align-items: center; gap: 10px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }
        .nav { display: flex; gap: 2rem; align-items: center; }
        .nav a { text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s ease; position: relative; }
        .nav a:hover, .nav a.cta { color: #6a4c93; }
        .nav a.active { color: #6a4c93; font-weight: 600; }
        .nav a.active::after { content: ''; position: absolute; bottom: -8px; left: 0; right: 0; height: 3px; background: #6a4c93; border-radius: 2px; }
        .nav a.cta { background: #6a4c93; color: white; padding: 0.5rem 1rem; border-radius: 5px; }
        .nav a.cta.active::after { display: none; }
        .mobile-toggle { display: none; flex-direction: column; gap: 3px; cursor: pointer; }
        .mobile-toggle span { width: 25px; height: 3px; background: #333; transition: 0.3s; }
        
        /* Sections */
        section { padding: 4rem 0; }
        .section-wrap { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .section-title { font-size: 2.5rem; text-align: center; margin-bottom: 1rem; color: #6a4c93; }
        .section-subtitle { text-align: center; font-size: 1.2rem; color: #666; margin-bottom: 3rem; }
        
        /* General button styles */
        .btn { padding: 1rem 2rem; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; transition: 0.3s; }
        .btn-primary { background: #ff6b35; color: white; }
        .btn-outline { background: transparent; color: white; border: 2px solid white; }
        .btn:hover { transform: translateY(-2px); }
        .meta { display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; }
        
        /* Grid */
        .grid { display: grid; gap: 2rem; }
        .grid.ps { grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); }
        .grid.benefits { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
        
        /* Cards */
        .card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .benefit { display: flex; gap: 1rem; align-items: flex-start; }
        .benefit .icon { font-size: 2rem; }
        
        /* Lists */
        .list { list-style: none; }
        .list li { padding: 0.5rem 0; border-bottom: 1px solid #eee; }
        .list li:before { content: '✓'; color: #6a4c93; font-weight: bold; margin-right: 0.5rem; }
        
        /* Price Grid */
        .price-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .price-item { text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; }
        .price-item.highlight { background: #fff3cd; border: 2px solid #ff6b35; }
        
        /* Gallery */
        .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; }
        .gallery-item img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
        
        /* Form */
        .form { max-width: 600px; margin: 0 auto; display: grid; gap: 1rem; }
        .field { display: flex; flex-direction: column; }
        .field.full { grid-column: 1 / -1; }
        .field label { margin-bottom: 0.5rem; font-weight: 500; }
        .field input, .field textarea { padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; }
        .choices { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .chip { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 20px; cursor: pointer; }
        .chip input { margin: 0; }
        .chip.selected { background: #6a4c93; color: white; border-color: #6a4c93; }
        
        /* Footer */
        .footer { background: #6a4c93; color: white; padding: 3rem 0; }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; }
        .footer h4 { margin-bottom: 1rem; }
        .social-links { display: flex; gap: 1rem; }
        .social-links a { color: white; text-decoration: none; }
        
        /* Floating contacts */
        .floating-contacts { position: fixed; bottom: 20px; right: 20px; display: flex; flex-direction: column; gap: 10px; z-index: 1000; }
        .floating-btn { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: transform 0.3s ease; }
        .floating-btn:hover { transform: scale(1.1); }
        .floating-btn.phone { background: #25D366; color: white; }
        .floating-btn.email { background: #EA4335; color: white; }
        .floating-btn.youtube { background: #FF0000; color: white; }
        
        /* Mobile Menu Active States */
        .mobile-menu-link.active,
        .mobile-submenu-item.active {
            background-color: #6a4c93 !important;
            color: white !important;
            font-weight: 600;
        }
        
        .mobile-menu-link.active:hover,
        .mobile-submenu-item.active:hover {
            background-color: #5a3c83 !important;
        }
        
        /* User Menu Dropdown */
        .user-menu {
            position: relative;
            display: inline-block;
        }
        
        .user-name {
            color: #6a4c93;
            font-weight: 600;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .user-name:hover {
            background: rgba(106, 76, 147, 0.1);
        }
        
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown a,
        .user-dropdown button {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            transition: background 0.2s ease;
            border: none;
            background: none;
            text-align: left;
            font-size: 1rem;
        }
        
        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background: #f8f9fa;
            color: #6a4c93;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .nav { display: none; }
            .mobile-toggle { display: flex; }
            .grid { grid-template-columns: 1fr; }
            .hero-cta { flex-direction: column; }
            .meta { flex-direction: column; text-align: center; }
        }
    </style>
    
    <!-- Homepage specific CSS -->
    <link rel="stylesheet" href="{{ asset('themes/shop/emsaigon/assets/css/lamgame-homepage.css') }}">
    
    <!-- Dynamic styles from pages -->
    @stack('styles')
    
    <!-- Pagination CSS -->
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <img src="{{ asset('logo/lamgame-horizontal.svg') }}" alt="LAMGAME Logo" class="logo" style="width: auto; height: 50px; border-radius: 0;">
                </div>
                
                {{-- Navigation Menu --}}
                <nav class="nav">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                    <a href="{{ route('lamgame.source-game') }}" class="{{ request()->routeIs('lamgame.source-game') ? 'active' : '' }}">Source Game</a>
                    <a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'active' : '' }}">Forum</a>
                    <a href="{{ route('lamgame.blog') }}" class="{{ request()->routeIs('lamgame.blog*', 'blog.*') ? 'active' : '' }}">Blog</a>
                    <a href="{{ route('lamgame.viec-lam-game') }}" class="{{ request()->routeIs('lamgame.viec-lam-game*') ? 'active' : '' }}">Việc làm</a>
                    <a href="{{ route('lamgame.gioi-thieu') }}" class="{{ request()->routeIs('lamgame.gioi-thieu*') ? 'active' : '' }}">Giới thiệu</a>
                    <a href="{{ route('lamgame.lien-he') }}" class="{{ request()->routeIs('lamgame.lien-he*') ? 'active' : '' }}">Liên hệ</a>
                    
                    @guest('customer')
                        <a href="{{ route('auth.login') }}" class="cta">Tham gia ngay</a>
                    @else
                        <div class="user-menu">
                            <span class="user-name">{{ auth('customer')->user()->first_name }}</span>
                            <div class="user-dropdown">
                                <a href="{{ route('auth.profile') }}">Thông tin cá nhân</a>
                                <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer;">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </nav>
                
                <div class="mobile-toggle" onclick="openMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>

    {{-- Dynamic Mobile Menu --}}
    @include('menu::frontend.partials.mobile-menu')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                {{-- Dynamic Footer Menu --}}
                @include('menu::frontend.partials.footer-menu')
                
                <div class="footer-section">
                    <h4>Liên hệ</h4>
                    <p>📍 Địa chỉ: Tòa nhà E.Town Central, 11 Đoàn Văn Bơ, Phường 13, Quận 4, TP.HCM</p>
                    <p>📞 Hotline: 09.1111.8300</p>
                    <p>✉️ Email: salegamevui@gmail.com</p>
                </div>
                <div class="footer-section">
                    <h4>Theo dõi chúng tôi</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">📘 Facebook</a>
                        <a href="#" aria-label="Instagram">📷 Instagram</a>
                        <a href="https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw" target="_blank" aria-label="YouTube">📺 YouTube</a>
                    </div>
                </div>
                <div class="footer-section">
                    <p>&copy; 2025 LAMGAME - Làm Game. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Contact Buttons -->
    <div class="floating-contacts">
        <a href="tel:0911118300" class="floating-btn phone" aria-label="Gọi điện thoại">
            📞
        </a>
        <a href="mailto:salegamevui@gmail.com" class="floating-btn email" aria-label="Gửi email">
            ✉️
        </a>
        <a href="https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw" target="_blank" class="floating-btn youtube" aria-label="YouTube">
            📺
        </a>
    </div>

    <!-- Vue.js 3 and Axios for dynamic content -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js" onload="initializeVueApp()" onerror="handleVueLoadError()"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <!-- Initialize Vue App -->
    <script>
        function handleVueLoadError() {
            console.warn("Failed to load Vue.js. Some features may not work.");
        }
        
        function initializeVueApp() {
            try {
                // Check if Vue is available
                if (typeof Vue === "undefined") {
                    console.warn("Vue is not loaded. Some features may not work.");
                    return;
                }
                
                const { createApp } = Vue;
                const app = createApp({});
                
                // Add axios to app config if available
                if (typeof axios !== "undefined") {
                    app.config.globalProperties.$axios = axios;
                }
                
                // Event emitter for component communication  
                app.config.globalProperties.$emitter = {
                    emit: (event, data) => {
                        document.dispatchEvent(new CustomEvent(event, { detail: data }));
                    },
                    on: (event, callback) => {
                        document.addEventListener(event, (e) => callback(e.detail));
                    }
                };
                
                console.log("Vue app initialized successfully");
            } catch (error) {
                console.error("Vue initialization error:", error);
            }
        }
        
        // Fallback: try to initialize if Vue is already loaded
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof Vue !== "undefined") {
                initializeVueApp();
            }
        });
    </script>
    
    <!-- Vite Assets -->
    {{-- @bagistoVite(['resources/themes/emsaigon/assets/js/app.js'], 'shop-emsaigon') --}}
    
    @stack('scripts')

    <!-- Inline JavaScript -->
    <script>
        // Scroll to section function
        function scrollToSection(target) {
            const element = document.querySelector(target);
            if (element) {
                element.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Toggle mobile menu (legacy function for compatibility)
        function toggleMenu() {
            openMobileMenu();
        }

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Set active menu items based on current URL
        function setActiveMobileMenuItems() {
            const currentUrl = window.location.pathname;
            const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link, .mobile-submenu-item');
            
            mobileMenuLinks.forEach(link => {
                const linkUrl = new URL(link.href).pathname;
                
                // Remove existing active classes
                link.classList.remove('active');
                
                // Check for exact match or parent path match
                if (linkUrl === currentUrl || 
                    (currentUrl.startsWith(linkUrl) && linkUrl !== '/' && linkUrl.length > 1)) {
                    link.classList.add('active');
                }
                
                // Special handling for common routes
                if (currentUrl.includes('/forum') && linkUrl.includes('/forum')) {
                    link.classList.add('active');
                }
                if (currentUrl.includes('/blog') && linkUrl.includes('/blog')) {
                    link.classList.add('active');
                }
                if (currentUrl.includes('/source-game') && linkUrl.includes('/source-game')) {
                    link.classList.add('active');
                }
            });
        }
        
        // Analytics placeholder functions
        function trackCTA(action) {
            console.log('CTA tracked:', action);
            // Add your analytics tracking here
        }

        function trackRegistration() {
            console.log('Registration tracked');
            // Add your analytics tracking here
        }
        
        // Initialize active menu states and mount Vue app after DOM is loaded
        window.addEventListener('DOMContentLoaded', function() {
            // Set active states for mobile menu
            setTimeout(setActiveMobileMenuItems, 100);
            
            // Only mount if we have Vue components on the page
            if (typeof app !== 'undefined') {
                try {
                    app.mount('body');
                    console.log('Vue app mounted successfully');
                } catch (error) {
                    console.log('Vue app mount error:', error.message);
                    // Try mounting on a specific element if body fails
                    try {
                        app.mount('#app');
                        console.log('Vue app mounted on #app');
                    } catch (error2) {
                        console.log('Secondary mount failed:', error2.message);
                    }
                }
            }
        });
    </script>
    
    <!-- Additional page scripts -->
    @stack('scripts')
</body>
</html>
