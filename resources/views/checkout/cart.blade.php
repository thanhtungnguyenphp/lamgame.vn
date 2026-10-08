@extends('layouts.master')

@section('page_title', 'Giỏ hàng - Làm Game')

@push('styles')
<style>
    :root {
        --co-bg: #070B14; --co-surface: #111827; --co-surface-2: rgba(17,24,39,.6);
        --co-border: rgba(124,92,255,.15); --co-text: #F5F7FA; --co-text-2: #B7C0D1; --co-muted: #7A8599;
        --co-accent: #7C5CFF; --co-accent-2: #00D1FF; --co-success: #34D399; --co-error: #F87171;
        --co-radius: 14px;
    }
    .cart-container { padding: 2rem 0; min-height: 60vh; color: var(--co-text); }
    .cart-container h1 { color: var(--co-text); }
    .cart-content { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
    .cart-items { background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: var(--co-radius); padding: 1.5rem; }
    .cart-summary { background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: var(--co-radius); padding: 1.5rem; position: sticky; top: 100px; }
    .cart-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--co-border); }
    .cart-item:last-child { border-bottom: none; }
    .cart-item-image { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; background: var(--co-surface); }
    .cart-item-details { flex: 1; }
    .cart-item-name { font-weight: 600; color: var(--co-text); margin-bottom: 0.5rem; }
    .cart-item-price { color: var(--co-accent-2); font-weight: 700; font-size: 1.1rem; }
    .cart-item-actions { display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap; }
    .qty-control { display: flex; align-items: center; border: 1px solid var(--co-border); border-radius: 8px; overflow: hidden; }
    .qty-btn { background: var(--co-surface); border: none; padding: 0.5rem 0.9rem; cursor: pointer; font-size: 1.1rem; color: var(--co-text); min-width: 44px; min-height: 44px; }
    .qty-btn:hover { background: var(--co-accent); color: #fff; }
    .qty-input { width: 54px; text-align: center; border: none; font-size: 1rem; background: transparent; color: var(--co-text); }
    .remove-btn { color: var(--co-error); background: none; border: none; cursor: pointer; font-size: 0.9rem; }
    .remove-btn:hover { text-decoration: underline; }
    .summary-row { display: flex; justify-content: space-between; padding: 0.5rem 0; color: var(--co-text-2); }
    .summary-total { font-size: 1.15rem; font-weight: 700; color: var(--co-text); border-top: 1px solid var(--co-border); padding-top: 0.75rem; margin-top: 0.5rem; }
    .summary-total span:last-child { color: var(--co-accent-2); }
    .btn-checkout { width: 100%; padding: 0.9rem; background: linear-gradient(135deg,var(--co-accent),var(--co-accent-2)); color: #fff; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 1rem; text-decoration: none; display: block; text-align: center; transition: opacity .2s; }
    .btn-checkout:hover { opacity: .9; }
    .btn-continue { width: 100%; padding: 0.75rem; background: transparent; color: var(--co-text-2); border: 1px solid var(--co-border); border-radius: 10px; font-size: 0.9rem; cursor: pointer; margin-top: 0.5rem; text-decoration: none; display: block; text-align: center; }
    .btn-continue:hover { border-color: var(--co-accent); color: var(--co-text); }
    .empty-cart { text-align: center; padding: 4rem 2rem; background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: var(--co-radius); color: var(--co-text-2); }
    .empty-cart-icon { margin-bottom: 1.5rem; }
    .cart-skeleton { height: 100px; border-radius: 10px; background: linear-gradient(90deg, rgba(124,92,255,.06) 25%, rgba(124,92,255,.12) 50%, rgba(124,92,255,.06) 75%); background-size: 200% 100%; animation: coShimmer 1.3s infinite; margin-bottom: 1rem; }
    @keyframes coShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .co-alert { padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.9rem; }
    .co-alert--error { background: rgba(248,113,113,.1); border: 1px solid rgba(248,113,113,.3); color: var(--co-error); }
    @media (max-width: 768px) {
        .cart-content { grid-template-columns: 1fr; }
        .cart-summary { position: static; }
        .cart-item-image { width: 72px; height: 72px; }
    }
</style>
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <h1 style="margin-bottom: 1.5rem;">{{ __('lamgame.cart.title') }}</h1>
        
        <div id="cart-app">
            <div v-if="loading">
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-skeleton"></div>
                        <div class="cart-skeleton"></div>
                    </div>
                    <div class="cart-summary"><div class="cart-skeleton" style="height:180px"></div></div>
                </div>
            </div>

            <div v-else-if="loadError" class="empty-cart">
                <div class="co-alert co-alert--error" style="display:inline-block">{{ __('lamgame.cart.load_error') }}</div>
                <div><button class="btn-checkout" style="max-width:220px;margin:1rem auto 0" @click="loadCart">{{ __('lamgame.cart.retry') }}</button></div>
            </div>

            <div v-else-if="!cart || !cart.items || cart.items.length === 0" class="empty-cart">
                <div class="empty-cart-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#7A8599" stroke-width="1.5">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.5rem; color: var(--co-text); margin-bottom: 0.5rem;">{{ __('lamgame.cart.empty') }}</h3>
                <p style="margin-bottom: 1.5rem;">{{ __('lamgame.cart.empty_desc') }}</p>
                <a href="{{ url('/source-game') }}" class="btn-checkout" style="max-width: 280px; margin: 0 auto 1rem;">
                    {{ __('lamgame.cart.explore_source') }}
                </a>
                <a href="{{ url('/') }}" class="btn-continue" style="max-width: 280px; margin: 0 auto;">
                    Về trang chủ
                </a>
            </div>
            
            <div v-else class="cart-content">
                <div class="cart-items">
                    <div v-for="item in cart.items" :key="item.id" class="cart-item">
                        <img :src="item.base_image?.small_image_url || '/images/placeholder.png'" :alt="item.name" class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name">@{{ item.name }}</div>
                            <div class="cart-item-price">@{{ formatPrice(item.price) }}</div>
                            <div class="cart-item-actions">
                                <div class="qty-control">
                                    <button class="qty-btn" @click="updateQty(item, item.quantity - 1)">−</button>
                                    <input type="number" class="qty-input" :value="item.quantity" @change="updateQty(item, $event.target.value)" min="1">
                                    <button class="qty-btn" @click="updateQty(item, item.quantity + 1)">+</button>
                                </div>
                                <button class="remove-btn" @click="removeItem(item)">{{ __('lamgame.cart.remove') }}</button>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: var(--co-accent-2);">@{{ formatPrice(item.total) }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h3 style="margin-bottom: 1rem; color: var(--co-text);">{{ __('lamgame.cart.summary') }}</h3>
                    
                    <!-- Coupon -->
                    <div style="margin-bottom: 1rem;">
                        <div v-if="!cart.coupon_code" style="display: flex; gap: 0.5rem;">
                            <input type="text" v-model="couponCode" placeholder="{{ __('lamgame.cart.coupon_ph') }}" 
                                style="flex: 1; padding: 0.6rem; border: 1px solid var(--co-border); border-radius: 8px; background: var(--co-surface); color: var(--co-text);">
                            <button @click="applyCoupon" :disabled="applyingCoupon" 
                                style="padding: 0.6rem 1rem; background: var(--co-accent); color: white; border: none; border-radius: 8px; cursor: pointer;">
                                @{{ applyingCoupon ? '...' : @json(__('lamgame.cart.apply')) }}
                            </button>
                        </div>
                        <div v-else style="display: flex; justify-content: space-between; align-items: center; background: rgba(52,211,153,.1); padding: 0.6rem; border-radius: 8px;">
                            <span style="color: var(--co-success);">🎫 @{{ cart.coupon_code }}</span>
                            <button @click="removeCoupon" style="background: none; border: none; color: var(--co-error); cursor: pointer;">{{ __('lamgame.cart.remove') }}</button>
                        </div>
                        <p v-if="couponError" style="color: var(--co-error); font-size: 0.85rem; margin-top: 0.25rem;">@{{ couponError }}</p>
                    </div>
                    
                    <div class="summary-row">
                        <span>{{ __('lamgame.cart.subtotal') }}</span>
                        <span>@{{ formatPrice(cart.sub_total) }}</span>
                    </div>
                    <div class="summary-row" v-if="cart.discount_amount > 0">
                        <span>{{ __('lamgame.cart.discount') }}</span>
                        <span style="color: var(--co-success);">-@{{ formatPrice(cart.discount_amount) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>{{ __('lamgame.cart.tax') }}</span>
                        <span>@{{ formatPrice(cart.tax_total || 0) }}</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>{{ __('lamgame.cart.total') }}</span>
                        <span>@{{ formatPrice(cart.grand_total) }}</span>
                    </div>
                    <a href="/checkout/onepage" class="btn-checkout">{{ __('lamgame.cart.checkout') }}</a>
                    <a href="{{ url('/') }}" class="btn-continue">{{ __('lamgame.cart.continue') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            cart: null,
            loading: true,
            loadError: false,
            couponCode: '',
            couponError: '',
            applyingCoupon: false
        }
    },
    mounted() {
        this.loadCart();
    },
    methods: {
        async loadCart() {
            this.loading = true;
            this.loadError = false;
            try {
                const res = await fetch('/api/checkout/cart', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                this.cart = data.data;
            } catch (e) {
                console.error('Error loading cart:', e);
                this.loadError = true;
            } finally {
                this.loading = false;
            }
        },
        formatPrice(price) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price || 0);
        },
        async updateQty(item, qty) {
            if (qty < 1) return;
            try {
                await fetch('/api/checkout/cart', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qty: { [item.id]: qty } })
                });
                this.loadCart();
            } catch (e) {
                console.error('Error updating cart:', e);
            }
        },
        async removeItem(item) {
            if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;
            try {
                await fetch('/api/checkout/cart', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_item_id: item.id })
                });
                this.loadCart();
            } catch (e) {
                console.error('Error removing item:', e);
            }
        },
        async applyCoupon() {
            if (!this.couponCode.trim()) return;
            this.applyingCoupon = true;
            this.couponError = '';
            try {
                const res = await fetch('/api/checkout/cart/coupon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: this.couponCode })
                });
                const data = await res.json();
                if (!res.ok || data.message?.includes('không hợp lệ') || data.message?.includes('invalid')) {
                    this.couponError = data.message || 'Mã giảm giá không hợp lệ';
                } else {
                    this.couponCode = '';
                    this.loadCart();
                }
            } catch (e) {
                this.couponError = 'Có lỗi xảy ra';
            } finally {
                this.applyingCoupon = false;
            }
        },
        async removeCoupon() {
            try {
                await fetch('/api/checkout/cart/coupon', {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                this.loadCart();
            } catch (e) {
                console.error('Error removing coupon:', e);
            }
        }
    }
}).mount('#cart-app');
</script>
@endpush
