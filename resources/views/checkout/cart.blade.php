@extends('layouts.master')

@section('page_title', 'Giỏ hàng - Làm Game')

@push('styles')
<style>
    .cart-container { padding: 2rem 0; min-height: 60vh; }
    .cart-content { display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start; }
    .cart-items { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .cart-summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; }
    .cart-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #eee; }
    .cart-item:last-child { border-bottom: none; }
    .cart-item-image { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; background: #f5f5f5; }
    .cart-item-details { flex: 1; }
    .cart-item-name { font-weight: 600; color: #333; margin-bottom: 0.5rem; }
    .cart-item-price { color: #2c5f41; font-weight: 700; font-size: 1.1rem; }
    .cart-item-actions { display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem; }
    .qty-control { display: flex; align-items: center; border: 1px solid #ddd; border-radius: 6px; }
    .qty-btn { background: none; border: none; padding: 0.5rem 0.75rem; cursor: pointer; font-size: 1rem; }
    .qty-btn:hover { background: #f5f5f5; }
    .qty-input { width: 50px; text-align: center; border: none; font-size: 1rem; }
    .remove-btn { color: #dc3545; background: none; border: none; cursor: pointer; font-size: 0.9rem; }
    .remove-btn:hover { text-decoration: underline; }
    .summary-row { display: flex; justify-content: space-between; padding: 0.5rem 0; }
    .summary-total { font-size: 1.1rem; font-weight: 700; color: #2c5f41; border-top: 1px solid #e5e7eb; padding-top: 0.75rem; margin-top: 0.5rem; }
    .btn-checkout { width: 100%; padding: 0.875rem; background: #2c5f41; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 1rem; text-decoration: none; display: block; text-align: center; }
    .btn-checkout:hover { background: #1e4530; }
    .btn-continue { width: 100%; padding: 0.75rem; background: white; color: #2c5f41; border: 1px solid #2c5f41; border-radius: 8px; font-size: 0.9rem; cursor: pointer; margin-top: 0.5rem; text-decoration: none; display: block; text-align: center; }
    .btn-continue:hover { background: #f0fdf4; }
    .empty-cart { text-align: center; padding: 3rem; }
    .empty-cart-icon { font-size: 4rem; margin-bottom: 1rem; }
    @media (max-width: 768px) {
        .cart-content { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <h1 style="margin-bottom: 1.5rem; color: #2c5f41;">🛒 Giỏ hàng</h1>
        
        <div id="cart-app">
            <div v-if="loading" style="text-align: center; padding: 3rem;">
                <p>Đang tải...</p>
            </div>
            
            <div v-else-if="!cart || !cart.items || cart.items.length === 0" class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h3>Giỏ hàng trống</h3>
                <p style="color: #666; margin: 1rem 0;">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('home') }}" class="btn-continue" style="max-width: 300px; margin: 0 auto;">Tiếp tục mua sắm</a>
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
                                <button class="remove-btn" @click="removeItem(item)">Xóa</button>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: #2c5f41;">@{{ formatPrice(item.total) }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h3 style="margin-bottom: 1rem; color: #333;">Tóm tắt đơn hàng</h3>
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span>@{{ formatPrice(cart.sub_total) }}</span>
                    </div>
                    <div class="summary-row" v-if="cart.discount_amount > 0">
                        <span>Giảm giá</span>
                        <span style="color: #dc3545;">-@{{ formatPrice(cart.discount_amount) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Thuế</span>
                        <span>@{{ formatPrice(cart.tax_total || 0) }}</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Tổng cộng</span>
                        <span>@{{ formatPrice(cart.grand_total) }}</span>
                    </div>
                    <a href="/checkout/onepage" class="btn-checkout">Tiến hành thanh toán</a>
                    <a href="{{ route('home') }}" class="btn-continue">Tiếp tục mua sắm</a>
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
            loading: true
        }
    },
    mounted() {
        this.loadCart();
    },
    methods: {
        async loadCart() {
            try {
                const res = await fetch('/api/checkout/cart', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.cart = data.data;
            } catch (e) {
                console.error('Error loading cart:', e);
            } finally {
                this.loading = false;
            }
        },
        formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0);
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
        }
    }
}).mount('#cart-app');
</script>
@endpush
