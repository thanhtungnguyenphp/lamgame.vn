<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('page_title', 'Seller Dashboard - Làm Game')</title>
    <meta name="theme-color" content="#2c5f41" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logos/favicon/favicon.ico') }}" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8f9fa; color: #1f2937; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        
        /* Seller Header */
        .seller-header { background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .seller-header .container { display: flex; justify-content: space-between; align-items: center; }
        .seller-logo { font-size: 1.5rem; font-weight: 800; color: white; text-decoration: none; }
        .seller-nav { display: flex; gap: 1.5rem; align-items: center; }
        .seller-nav a { color: white; text-decoration: none; font-weight: 500; opacity: 0.9; transition: opacity 0.2s; }
        .seller-nav a:hover { opacity: 1; }
        .seller-user { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; background: rgba(255,255,255,0.1); border-radius: 8px; }
        
        /* Main Content */
        .seller-main { min-height: calc(100vh - 140px); }
        
        /* Footer */
        .seller-footer { background: white; border-top: 1px solid #e5e7eb; padding: 1.5rem 0; margin-top: 3rem; text-align: center; color: #6b7280; font-size: 0.875rem; }
        
        /* Mobile */
        @media (max-width: 768px) {
            .seller-nav { display: none; }
            .seller-header .container { flex-direction: column; gap: 1rem; }
        }
    </style>
</head>

<body>
    <!-- Seller Header -->
    <header class="seller-header">
        <div class="container">
            <a href="{{ route('seller.dashboard') }}" class="seller-logo">
                🏪 Seller Dashboard
            </a>
            
            <nav class="seller-nav">
                <a href="{{ route('seller.dashboard') }}">Dashboard</a>
                <a href="{{ route('seller.products.index') }}">Sản phẩm</a>
                <a href="{{ route('seller.orders.index') }}">Đơn hàng</a>
                <a href="{{ route('seller.withdrawals.index') }}">Rút tiền</a>
                <a href="{{ route('seller.analytics') }}">Phân tích</a>
                <a href="{{ route('seller.register') }}">Cài đặt</a>
            </nav>
            
            <div class="seller-user">
                <span>{{ auth()->guard('customer')->user()->seller->shop_name }}</span>
                <a href="{{ route('shop.customers.account.profile.index') }}" style="color: white;">⚙️</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="seller-main">
        @if(session('success'))
            <div style="max-width: 900px; margin: 1rem auto; padding: 1rem 1.5rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46;">
                ✓ {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div style="max-width: 900px; margin: 1rem auto; padding: 1rem 1.5rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b;">
                ✗ {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="seller-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Làm Game. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
