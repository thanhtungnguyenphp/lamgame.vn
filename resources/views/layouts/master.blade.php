<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#6a4c93">
    <meta name="color-scheme" content="light">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LamGame">
    <meta name="msapplication-TileColor" content="#6a4c93">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <title>@yield('page_title', 'LAMGAME • Làm Game - Học Lập Trình Game và Phát Triển Ứng Dụng')</title>
    <meta name="description" content="@yield('page_description', 'Làm Game - Nền tảng học lập trình game, phát triển ứng dụng và các khóa học lập trình chuyên sâu. Bắt đầu hành trình của bạn ngay hôm nay!')">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('page_title', 'LAMGAME • Làm Game - Học Lập Trình Game và Phát Triển Ứng Dụng')">
    <meta property="og:description" content="@yield('page_description', 'Làm Game - Nền tảng học lập trình game, phát triển ứng dụng và các khóa học lập trình chuyên sâu. Bắt đầu hành trình của bạn ngay hôm nay!')">
    <meta property="og:image" content="@yield('og_image', asset('assets/logos/png/logo-square-512.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="LamGame.vn">
    <meta property="og:locale" content="vi_VN">
    @stack('og_extra')

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary')">
    <meta name="twitter:title" content="@yield('page_title', 'LAMGAME • Làm Game')">
    <meta name="twitter:description" content="@yield('page_description', 'Làm Game - Nền tảng học lập trình game và phát triển ứng dụng.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/logos/png/logo-square-512.png'))">
    
    <!-- Google AdSense -->
    <meta name="google-adsense-account" content="ca-pub-5812352607411986">
    
    <!-- Canonical URL (strip query params to avoid duplicate) -->
    @hasSection('canonical_url')
        <link rel="canonical" href="@yield('canonical_url')">
    @else
        <link rel="canonical" href="{{ strtok(url()->current(), '?') }}">
    @endif

    <!-- Pagination rel prev/next -->
    @stack('pagination_links')

    <!-- Robots directive for paginated pages -->
    @if(request()->has('page') && request()->get('page') > 1)
    <meta name="robots" content="noindex, follow">
    @endif
    
    @stack('meta')
    @stack('schema_markup')
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logos/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logos/favicon/favicon-16x16.png') }}">

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
        body { font-family: 'Roboto', sans-serif; line-height: 1.6; color: #333; background: #fff; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Header */
        .header { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        .header-content { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .logo { width: 50px; height: 50px; border-radius: 50%; }

        /* Brand Logo Mobile-First Optimization */
        .brand-logo-optimized {
            height: 30px; /* Mobile base */
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.08));
        }

        @media (min-width: 481px) {
            .brand-logo-optimized { height: 40px; }
        }

        @media (min-width: 769px) {
            .brand-logo-optimized { height: 50px; }
        }

        @media (min-width: 1025px) {
            .brand-logo-optimized {
                height: 30px;
                max-height: 30px;
            }
        }
        .nav { display: flex; gap: 2rem; align-items: center; }
        .nav a { text-decoration: none; color: #333; font-weight: 500; transition: all 0.3s ease; position: relative; }
        .nav a:hover, .nav a.cta { color: #6a4c93; }
        .nav a.active { color: #6a4c93; font-weight: 600; }
        .nav a.active::after { content: ''; position: absolute; bottom: -8px; left: 0; right: 0; height: 3px; background: #6a4c93; border-radius: 2px; }
        .nav a.cta { background: #6a4c93; color: white; padding: 0.5rem 1rem; border-radius: 5px; }
        .nav a.cta.active::after { display: none; }
        
        /* Cart Icon */
        .cart-icon { position: relative; font-size: 1.3rem; padding: 0.3rem 0.5rem !important; }
        .cart-badge { position: absolute; top: -5px; right: -5px; background: #ff6b35; color: white; font-size: 0.7rem; font-weight: 600; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        
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
        .footer { background: #1a1a2e; color: #ccc; padding: 0; }
        .footer-main { display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 3rem; padding: 3rem 0 2rem; }
        .footer h4 { color: #fff; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.2rem; position: relative; padding-bottom: 0.75rem; }
        .footer h4::after { content: ''; position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: #6a4c93; }
        .footer-section p { margin-bottom: 0.6rem; font-size: 0.9rem; line-height: 1.7; }
        .footer-section a { color: #aaa; text-decoration: none; transition: color 0.2s; }
        .footer-section a:hover { color: #fff; }
        .footer-nav-item { margin-bottom: 0.5rem; }
        .footer-nav-link { color: #aaa !important; font-size: 0.9rem; }
        .footer-nav-link:hover { color: #fff !important; }
        .footer-contact-item { display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.7rem; font-size: 0.9rem; }
        .footer-contact-item i { color: #6a4c93; width: 16px; text-align: center; margin-top: 3px; }
        .social-links { display: flex; gap: 0.75rem; }
        .social-links a { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); color: #aaa; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; }
        .social-links a:hover { background: #6a4c93; color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding: 1.2rem 0; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #666; }
        .footer-bottom a { color: #888; text-decoration: none; }
        .footer-bottom a:hover { color: #fff; }
        @media (max-width: 768px) {
            .footer-main { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; gap: 0.5rem; text-align: center; }
        }



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
        
        /* Mobile Performance Optimizations */
        @media (max-width: 768px) {
            /* Optimize touch targets */
            .btn, button, a {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Reduce motion for better performance on mobile */
            * {
                transition-duration: 0.2s !important;
            }
            
            /* Optimize scrolling */
            body {
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }
            
            /* Safe area for notched devices */
            .container {
                padding-left: max(20px, env(safe-area-inset-left));
                padding-right: max(20px, env(safe-area-inset-right));
            }
        }
        
        /* Reduce animations for users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
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

    <!-- Google Analytics -->
    @if(config('google_analytics.enabled') && (app()->environment('production') || config('google_analytics.enabled')))
    @php
        $gaId = config('google_analytics.tracking_id');
        $anonymizeIp = config('google_analytics.anonymize_ip') ? 'true' : 'false';
        $allowSignals = config('google_analytics.allow_google_signals') ? 'true' : 'false';
    @endphp
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{ $gaId }}', {
        'page_title': document.title,
        'page_location': window.location.href,
        'anonymize_ip': {{ $anonymizeIp }},
        'allow_google_signals': {{ $allowSignals }},
        'cookie_flags': 'SameSite=None;Secure'
      });

      // Enhanced tracking functions
      window.trackEvent = function(eventName, parameters = {}) {
        if (typeof gtag !== 'undefined') {
          gtag('event', eventName, parameters);
          console.log('GA Event tracked:', eventName, parameters);
        }
      };

      window.trackPageView = function(pageTitle, pagePath) {
        if (typeof gtag !== 'undefined') {
          gtag('config', '{{ $gaId }}', {
            'page_title': pageTitle,
            'page_path': pagePath
          });
        }
      };

      // Enhanced CTA tracking
      window.trackCTA = function(action, category = 'engagement') {
        trackEvent('cta_click', {
          'event_category': category,
          'event_label': action,
          'value': 1
        });
      };

      // Job application tracking
      window.trackJobApplication = function(jobId, jobTitle, company) {
        trackEvent('job_application', {
          'event_category': 'jobs',
          'event_label': jobTitle,
          'job_id': jobId,
          'company': company,
          'value': 1
        });
      };

      // Blog engagement tracking
      window.trackBlogView = function(blogId, blogTitle, category) {
        trackEvent('blog_view', {
          'event_category': 'blog',
          'event_label': blogTitle,
          'blog_id': blogId,
          'blog_category': category,
          'value': 1
        });
      };

      // Contact form tracking
      window.trackFormSubmit = function(formType) {
        trackEvent('form_submit', {
          'event_category': 'forms',
          'event_label': formType,
          'value': 1
        });
      };
    </script>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <x-logo
                        size="medium"
                        variant="horizontal"
                        alt="LamGame.vn - Game Development Platform"
                        class="brand-logo-optimized"
                        :priority="true"
                        :lazy="false"
                        :interactive="true"
                        href="{{ url('/') }}"
                        title="Về trang chủ LamGame.vn - Cộng đồng Game Developer Việt Nam"
                    />
                </div>

                {{-- Navigation Menu --}}
                <nav class="nav">
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Trang chủ</a>
                    <a href="{{ route('lamgame.source-game') }}" class="{{ request()->routeIs('lamgame.source-game') ? 'active' : '' }}">Source Game</a>
                    <a href="{{ route('forum.index') }}" class="{{ request()->routeIs('forum.*') ? 'active' : '' }}">Forum</a>
                    <a href="{{ route('lamgame.blog') }}" class="{{ request()->routeIs('lamgame.blog*', 'blog.*') ? 'active' : '' }}">Blog</a>
                    <a href="{{ route('lamgame.viec-lam-game') }}" class="{{ request()->routeIs('lamgame.viec-lam-game*') ? 'active' : '' }}">Việc làm</a>
                    <a href="{{ route('lamgame.gioi-thieu') }}" class="{{ request()->routeIs('lamgame.gioi-thieu*') ? 'active' : '' }}">Giới thiệu</a>
                    <a href="{{ route('lamgame.lien-he') }}" class="{{ request()->routeIs('lamgame.lien-he*') ? 'active' : '' }}">Liên hệ</a>

                    {{-- Cart Icon --}}
                    <a href="{{ route('shop.checkout.cart.index') }}" class="cart-icon {{ request()->routeIs('shop.checkout.cart.*') ? 'active' : '' }}" title="Giỏ hàng">
                        🛒
                        @php
                            $cartItemCount = 0;
                            try {
                                $cart = cart()->getCart();
                                $cartItemCount = $cart ? $cart->items->count() : 0;
                            } catch (\Exception $e) {}
                        @endphp
                        @if($cartItemCount > 0)
                            <span class="cart-badge">{{ $cartItemCount }}</span>
                        @endif
                    </a>

                    @guest('customer')
                        <a href="{{ route('auth.login') }}" class="cta">Tham gia ngay</a>
                    @else
                        <div class="user-menu">
                            <span class="user-name">{{ auth('customer')->user()->first_name }}</span>
                            <div class="user-dropdown">
                                <a href="{{ route('shop.customers.account.profile.index') }}">Thông tin cá nhân</a>
                                <form action="{{ route('shop.customer.session.destroy') }}" method="POST" style="display: inline;">
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
            <div class="footer-main">
                <div class="footer-section">
                    <h4>Chính sách & Điều khoản</h4>
                    <nav class="footer-nav">
                        <div class="footer-nav-item"><a href="/page/chinh-sach-bao-mat" class="footer-nav-link">Chính sách bảo mật</a></div>
                        <div class="footer-nav-item"><a href="/page/dieu-khoan-dich-vu" class="footer-nav-link">Điều khoản dịch vụ</a></div>
                        <div class="footer-nav-item"><a href="/page/chinh-sach-thanh-toan-rut-tien" class="footer-nav-link">Chính sách thanh toán & rút tiền</a></div>
                        <div class="footer-nav-item"><a href="/page/chinh-sach-hoan-tien-tranh-chap" class="footer-nav-link">Chính sách hoàn tiền & tranh chấp</a></div>
                        <div class="footer-nav-item"><a href="/page/chinh-sach-danh-gia-xac-nhan" class="footer-nav-link">Chính sách đánh giá & xác nhận</a></div>
                    </nav>
                </div>
                <div class="footer-section">
                    <h4>Liên hệ</h4>
                    <div class="footer-contact-item"><i>📍</i><span>Tòa nhà E.Town Central, 11 Đoàn Văn Bơ, P.13, Q.4, TP.HCM</span></div>
                    <div class="footer-contact-item"><i>📞</i><span>09.1111.8300</span></div>
                    <div class="footer-contact-item"><i>✉️</i><span>salegamevui@gmail.com</span></div>
                </div>
                <div class="footer-section">
                    <h4>Kết nối</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook" title="Facebook">f</a>
                        <a href="#" aria-label="Instagram" title="Instagram">in</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} LAMGAME - Làm Game. All rights reserved.</span>
                <a href="{{ route('lamgame.lien-he') }}">Liên hệ hỗ trợ</a>
            </div>
        </div>
    </footer>

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

        // Analytics functions (enhanced versions in GA script above)
        function trackCTA(action, category = 'engagement') {
            if (typeof window.trackCTA === 'function') {
                window.trackCTA(action, category);
            } else {
                console.log('CTA tracked:', action, category);
            }
        }

        function trackRegistration() {
            if (typeof window.trackEvent === 'function') {
                window.trackEvent('registration', {
                    'event_category': 'user',
                    'event_label': 'account_registration',
                    'value': 1
                });
            } else {
                console.log('Registration tracked');
            }
        }

        // Initialize active menu states after DOM is loaded
        window.addEventListener('DOMContentLoaded', function() {
            // Set active states for mobile menu
            setTimeout(setActiveMobileMenuItems, 100);
        });
    </script>

    <!-- Additional page scripts -->
    @stack('scripts')

    @include('partials.fcm-init')
</body>
</html>
