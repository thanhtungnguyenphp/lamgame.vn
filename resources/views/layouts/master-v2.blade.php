<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#0D0D1A">
    <meta name="color-scheme" content="dark">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LamGame">

    <title>@yield('page_title', 'LamGame.vn — Source Game chất lượng cho Unity & Unreal Developer')</title>
    <meta name="description" content="@yield('page_description', 'Marketplace source game hàng đầu Việt Nam. Tiết kiệm hàng trăm giờ phát triển với source code, hệ thống gameplay và template production-ready.')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('page_title', 'LamGame.vn — Source Game chất lượng cho Unity & Unreal Developer')">
    <meta property="og:description" content="@yield('page_description', 'Marketplace source game hàng đầu Việt Nam.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/logos/png/logo-square-512.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="LamGame.vn">
    <meta property="og:locale" content="vi_VN">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('page_title', 'LamGame.vn')">
    <meta name="twitter:description" content="@yield('page_description', 'Marketplace source game hàng đầu Việt Nam.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/logos/png/logo-square-512.png'))">

    <!-- Canonical -->
    @hasSection('canonical_url')
        <link rel="canonical" href="@yield('canonical_url')">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    <!-- Preload fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/homepage-v2.css') }}">
    @stack('styles')

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.privacy-consent-head')
    @include('partials.analytics')
    @include('partials.adsense')

    <!-- Alpine.js for header/footer interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="lg-v2">

    {{-- Header --}}
    @include('components.v2.header')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.v2.footer')

    {{-- Scripts --}}
    <script>
        // Intersection Observer for fade-in animations
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.lg-v2-fade-in').forEach(el => observer.observe(el));
        });
    </script>
    @include('partials.privacy-consent-banner')
    @stack('scripts')
</body>
</html>
