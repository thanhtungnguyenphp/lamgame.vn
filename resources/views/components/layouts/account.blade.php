@extends('layouts.master')

@section('page_title', $title ?? 'Tài khoản của tôi')

@push('styles')
<style>
:root {
    --ac-bg: #070B14; --ac-surface: rgba(17,24,39,.6); --ac-surface-2: #111827;
    --ac-border: rgba(124,92,255,.15); --ac-text: #F5F7FA; --ac-text-2: #B7C0D1; --ac-muted: #7A8599;
    --ac-accent: #7C5CFF; --ac-accent-2: #00D1FF; --ac-success: #34D399; --ac-error: #F87171;
}
.account-container {
    min-height: calc(100vh - 200px);
    padding: 2rem 0;
    color: var(--ac-text);
}
.account-wrapper { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.account-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    margin-top: 1rem;
}
.account-sidebar {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 14px;
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 100px;
}
.account-user {
    display: flex; align-items: center; gap: 0.9rem;
    padding-bottom: 1.25rem; margin-bottom: 1rem;
    border-bottom: 1px solid var(--ac-border);
}
.account-user__avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg,var(--ac-accent),var(--ac-accent-2));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 1.3rem; flex-shrink: 0;
}
.account-user__name { font-weight: 700; color: var(--ac-text); font-size: 1.05rem; }
.account-user__email { color: var(--ac-muted); font-size: 0.82rem; word-break: break-all; }
.account-nav { list-style: none; margin: 0; padding: 0; }
.account-nav li { margin-bottom: 0.35rem; }
.account-nav a, .account-nav button {
    display: flex; align-items: center; gap: 0.75rem;
    width: 100%; padding: 0.7rem 1rem; border-radius: 10px;
    color: var(--ac-text-2); text-decoration: none; font-size: 0.95rem;
    background: none; border: none; cursor: pointer; text-align: left;
    transition: all 0.18s;
}
.account-nav a:hover, .account-nav button:hover {
    background: rgba(124,92,255,.1); color: var(--ac-text);
}
.account-nav a.active {
    background: linear-gradient(135deg,rgba(124,92,255,.25),rgba(0,209,255,.15));
    color: #fff; font-weight: 600;
    border: 1px solid var(--ac-border);
}
.account-nav__divider { margin: 0.75rem 0; border-top: 1px solid var(--ac-border); }
.account-content {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 14px;
    padding: 2rem;
    color: var(--ac-text);
}
.account-content h1, .account-content h2, .account-content h3 { color: var(--ac-text); }
.account-content a { color: var(--ac-accent-2); }
@media (max-width: 768px) {
    .account-layout { grid-template-columns: 1fr; }
    .account-sidebar { position: static; }
}
</style>
@endpush

@section('content')
@php $acCustomer = auth('customer')->user(); @endphp
<div class="account-container">
    <div class="account-wrapper">
        <div class="account-layout">
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <div class="account-user">
                    <div class="account-user__avatar">{{ strtoupper(mb_substr($acCustomer->first_name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div class="account-user__name">{{ trim(($acCustomer->first_name ?? '') . ' ' . ($acCustomer->last_name ?? '')) ?: __('lamgame.account.guest') }}</div>
                        <div class="account-user__email">{{ $acCustomer->email ?? '' }}</div>
                    </div>
                </div>

                <ul class="account-nav">
                    <li>
                        <a href="{{ route('shop.customers.account.profile.index') }}" class="{{ request()->routeIs('shop.customers.account.index') || request()->routeIs('shop.customers.account.profile.*') ? 'active' : '' }}">
                            <span>👤</span> {{ __('lamgame.account.profile') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.orders.index') }}" class="{{ request()->routeIs('shop.customers.account.orders.*') ? 'active' : '' }}">
                            <span>📦</span> {{ __('lamgame.account.orders') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.downloadable_products.index') }}" class="{{ request()->routeIs('shop.customers.account.downloadable_products.*') ? 'active' : '' }}">
                            <span>⬇️</span> {{ __('lamgame.account.downloads') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lamgame.my-licenses') }}" class="{{ request()->routeIs('lamgame.my-licenses') ? 'active' : '' }}">
                            <span>🔑</span> {{ __('lamgame.account.licenses') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.addresses.index') }}" class="{{ request()->routeIs('shop.customers.account.addresses.*') ? 'active' : '' }}">
                            <span>📍</span> {{ __('lamgame.account.addresses') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.wishlist.index') }}" class="{{ request()->routeIs('shop.customers.account.wishlist.*') ? 'active' : '' }}">
                            <span>❤️</span> {{ __('lamgame.account.wishlist') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.customers.account.reviews.index') }}" class="{{ request()->routeIs('shop.customers.account.reviews.*') ? 'active' : '' }}">
                            <span>⭐</span> {{ __('lamgame.account.reviews') }}
                        </a>
                    </li>

                    @auth('customer')
                        @php $currentSeller = auth('customer')->user()->seller; @endphp
                        <li><div class="account-nav__divider"></div></li>
                        @if($currentSeller && $currentSeller->isActive())
                            <li>
                                <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.*') ? 'active' : '' }}" style="color: var(--ac-success);">
                                    <span>🏪</span> {{ __('lamgame.account.seller_dashboard') }}
                                </a>
                            </li>
                        @elseif($currentSeller && $currentSeller->isPending())
                            <li>
                                <a href="{{ route('seller.pending') }}" style="color: #FBBF24;">
                                    <span>⏳</span> {{ __('lamgame.account.seller_pending') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('seller.register') }}" style="color: var(--ac-accent-2);">
                                    <span>➕</span> {{ __('lamgame.account.seller_register') }}
                                </a>
                            </li>
                        @endif

                        <li><div class="account-nav__divider"></div></li>
                        <li>
                            <form method="POST" action="{{ route('shop.customer.session.destroy') }}" style="margin:0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--ac-error);">
                                    <span>🚪</span> {{ __('lamgame.account.logout') }}
                                </button>
                            </form>
                        </li>
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
