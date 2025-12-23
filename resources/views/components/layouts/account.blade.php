@extends('layouts.master')

@section('page_title', $title ?? 'Tài khoản của tôi')

@push('styles')
<style>
.account-container {
    min-height: calc(100vh - 200px);
    background: #f8f9fa;
    padding: 2rem 0;
}

.account-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.account-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.account-sidebar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.account-sidebar h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #2c5f41;
}

.account-nav {
    list-style: none;
}

.account-nav li {
    margin-bottom: 0.5rem;
}

.account-nav a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: #666;
    text-decoration: none;
    transition: all 0.2s;
}

.account-nav a:hover {
    background: #f0f7f4;
    color: #2c5f41;
}

.account-nav a.active {
    background: #2c5f41;
    color: white;
    font-weight: 500;
}

.account-content {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

@media (max-width: 768px) {
    .account-layout {
        grid-template-columns: 1fr;
    }
    
    .account-sidebar {
        position: static;
    }
}
</style>
@endpush

@section('content')
<div class="account-container">
    <div class="account-wrapper">
        <div class="account-layout">
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <h3>Tài khoản</h3>
                <ul class="account-nav">
                    <li>
                        <a href="{{ route('shop.customers.account.profile.index') }}" class="{{ request()->routeIs('shop.customers.account.profile.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.orders.index') }}" class="{{ request()->routeIs('shop.customers.account.orders.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                            Đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.addresses.index') }}" class="{{ request()->routeIs('shop.customers.account.addresses.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Địa chỉ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.wishlist.index') }}" class="{{ request()->routeIs('shop.customers.account.wishlist.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            Yêu thích
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.reviews.index') }}" class="{{ request()->routeIs('shop.customers.account.reviews.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Đánh giá
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.downloadable_products.index') }}" class="{{ request()->routeIs('shop.customers.account.downloadable_products.*') ? 'active' : '' }}">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Sản phẩm tải về
                        </a>
                    </li>
                    
                    @auth('customer')
                        @php
                            $currentSeller = auth('customer')->user()->seller;
                        @endphp
                        
                        @if($currentSeller && $currentSeller->isActive())
                            <li style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.*') ? 'active' : '' }}" style="color: #059669;">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 5a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                                    </svg>
                                    Seller Dashboard
                                </a>
                            </li>
                        @elseif($currentSeller && $currentSeller->isPending())
                            <li style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <a href="{{ route('seller.pending') }}" style="color: #d97706;">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Seller (Chờ duyệt)
                                </a>
                            </li>
                        @else
                            <li style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <a href="{{ route('seller.register') }}" style="color: #2563eb;">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                    </svg>
                                    Đăng ký Seller
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="account-content">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
@endsection
