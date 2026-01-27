@extends('layouts.master')

@section('page_title', 'Thanh toán - Làm Game')

@push('styles')
<style>
    .checkout-container { padding: 2rem 0; min-height: 60vh; }
    .checkout-content { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start; }
    .checkout-steps { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .checkout-summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; position: sticky; top: 100px; }
    .step-section { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #eee; }
    .step-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .step-title { font-size: 1.25rem; font-weight: 600; color: #333; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .step-title .step-number { background: #2c5f41; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; }
    .address-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; cursor: pointer; transition: all 0.2s; }
    .address-card:hover { border-color: #2c5f41; }
    .address-card.selected { border-color: #2c5f41; background: #f0fdf4; }
    .summary-item { display: flex; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid #eee; }
    .summary-item:last-child { border-bottom: none; }
    .summary-item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; background: #f5f5f5; }
    .summary-row { display: flex; justify-content: space-between; padding: 0.5rem 0; }
    .summary-total { font-size: 1.1rem; font-weight: 700; color: #2c5f41; border-top: 1px solid #e5e7eb; padding-top: 0.75rem; margin-top: 0.5rem; }
    .btn-proceed { width: 100%; padding: 0.875rem; background: #2c5f41; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 1rem; }
    .btn-proceed:hover { background: #1e4530; }
    .btn-proceed:disabled { background: #ccc; cursor: not-allowed; }
    .shipping-option, .payment-option { border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 0.5rem; cursor: pointer; display: flex; align-items: center; gap: 0.75rem; }
    .shipping-option:hover, .payment-option:hover { border-color: #2c5f41; }
    .shipping-option.selected, .payment-option.selected { border-color: #2c5f41; background: #f0fdf4; }
    .radio-circle { width: 20px; height: 20px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .radio-circle.checked { border-color: #2c5f41; }
    .radio-circle.checked::after { content: ''; width: 10px; height: 10px; background: #2c5f41; border-radius: 50%; }
    @media (max-width: 768px) {
        .checkout-content { grid-template-columns: 1fr; }
        .checkout-summary { position: static; order: -1; }
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="container">
        <h1 style="margin-bottom: 1.5rem; color: #2c5f41;">💳 Thanh toán</h1>
        
        <div id="checkout-app">
            <div v-if="loading" style="text-align: center; padding: 3rem;">
                <p>Đang tải...</p>
            </div>
            
            <div v-else class="checkout-content">
                <div class="checkout-steps">
                    <!-- Address Section -->
                    <div class="step-section">
                        <div class="step-title">
                            <span class="step-number">1</span>
                            Địa chỉ giao hàng
                        </div>
                        <div v-if="addresses.length > 0">
                            <div 
                                v-for="addr in addresses" 
                                :key="addr.id"
                                class="address-card"
                                :class="{ selected: selectedAddress?.id === addr.id }"
                                @click="selectAddress(addr)"
                                style="margin-bottom: 0.5rem;"
                            >
                                <div style="font-weight: 600;">@{{ addr.first_name }} @{{ addr.last_name }}</div>
                                <div style="color: #666; font-size: 0.9rem;">@{{ addr.address }}, @{{ addr.city }}, @{{ addr.state }}</div>
                                <div style="color: #666; font-size: 0.9rem;">@{{ addr.phone }}</div>
                            </div>
                        </div>
                        <div v-else style="color: #666;">
                            Chưa có địa chỉ. Vui lòng thêm địa chỉ mới.
                        </div>
                    </div>

                    <!-- Shipping Section -->
                    <div class="step-section" v-if="cart?.have_stockable_items && shippingMethods.length > 0">
                        <div class="step-title">
                            <span class="step-number">2</span>
                            Phương thức vận chuyển
                        </div>
                        <div 
                            v-for="method in shippingMethods" 
                            :key="method.method"
                            class="shipping-option"
                            :class="{ selected: selectedShipping?.method === method.method }"
                            @click="selectShipping(method)"
                        >
                            <div class="radio-circle" :class="{ checked: selectedShipping?.method === method.method }"></div>
                            <div style="flex: 1;">
                                <div style="font-weight: 500;">@{{ method.method_title }}</div>
                                <div style="color: #666; font-size: 0.875rem;">@{{ method.method_description }}</div>
                            </div>
                            <div style="font-weight: 600; color: #2c5f41;">@{{ formatPrice(method.price) }}</div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="step-section" v-if="paymentMethods.length > 0">
                        <div class="step-title">
                            <span class="step-number">@{{ cart?.have_stockable_items ? '3' : '2' }}</span>
                            Phương thức thanh toán
                        </div>
                        <div 
                            v-for="method in paymentMethods" 
                            :key="method.method"
                            class="payment-option"
                            :class="{ selected: selectedPayment?.method === method.method }"
                            @click="selectPayment(method)"
                        >
                            <div class="radio-circle" :class="{ checked: selectedPayment?.method === method.method }"></div>
                            <div>
                                <div style="font-weight: 500;">@{{ method.method_title }}</div>
                                <div style="color: #666; font-size: 0.875rem;">@{{ method.method_description }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="checkout-summary">
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Đơn hàng của bạn</h3>
                    
                    <div v-for="item in cart?.items" :key="item.id" class="summary-item">
                        <img :src="item.base_image?.small_image_url || '/images/placeholder.png'" class="summary-item-image">
                        <div style="flex: 1;">
                            <div style="font-weight: 500; font-size: 0.9rem;">@{{ item.name }}</div>
                            <div style="color: #666; font-size: 0.875rem;">@{{ formatPrice(item.price) }} × @{{ item.quantity }}</div>
                        </div>
                    </div>

                    <div style="margin-top: 1rem;">
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span>@{{ formatPrice(cart?.sub_total) }}</span>
                        </div>
                        <div class="summary-row" v-if="cart?.shipping_amount > 0">
                            <span>Phí vận chuyển</span>
                            <span>@{{ formatPrice(cart?.shipping_amount) }}</span>
                        </div>
                        <div class="summary-row" v-if="cart?.discount_amount > 0">
                            <span>Giảm giá</span>
                            <span style="color: #dc3545;">-@{{ formatPrice(cart?.discount_amount) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Thuế</span>
                            <span>@{{ formatPrice(cart?.tax_total || 0) }}</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Tổng cộng</span>
                            <span>@{{ formatPrice(cart?.grand_total) }}</span>
                        </div>
                    </div>

                    <button 
                        class="btn-proceed" 
                        @click="placeOrder"
                        :disabled="!canPlaceOrder || isPlacing"
                    >
                        @{{ isPlacing ? 'Đang xử lý...' : 'Đặt hàng' }}
                    </button>
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
            loading: true,
            cart: null,
            addresses: [],
            shippingMethods: [],
            paymentMethods: [],
            selectedAddress: null,
            selectedShipping: null,
            selectedPayment: null,
            isPlacing: false
        }
    },
    computed: {
        canPlaceOrder() {
            if (!this.selectedAddress) return false;
            if (this.cart?.have_stockable_items && !this.selectedShipping) return false;
            if (!this.selectedPayment) return false;
            return true;
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        async loadData() {
            try {
                // Load cart summary (includes shipping/payment if already set)
                const summaryRes = await fetch('/api/checkout/onepage/summary', { 
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                
                if (summaryRes.ok) {
                    const summaryData = await summaryRes.json();
                    this.cart = summaryData.data;
                }
                
                // Load addresses
                const addrRes = await fetch('/api/customer/addresses', { 
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                
                if (addrRes.ok) {
                    const addrData = await addrRes.json();
                    this.addresses = addrData.data || [];
                    if (this.addresses.length > 0) {
                        this.selectAddress(this.addresses[0]);
                    }
                }
            } catch (e) {
                console.error('Error:', e);
            } finally {
                this.loading = false;
            }
        },
        async selectAddress(addr) {
            this.selectedAddress = addr;
            try {
                const addressStr = addr.address1 || addr.address || '';
                const res = await fetch('/api/checkout/onepage/addresses', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        billing: { 
                            address_id: addr.id,
                            first_name: addr.first_name,
                            last_name: addr.last_name,
                            email: addr.email || '{{ auth()->guard("customer")->user()?->email }}',
                            address: addressStr,
                            city: addr.city,
                            state: addr.state,
                            postcode: addr.postcode || '700000',
                            country: addr.country || 'VN',
                            phone: addr.phone,
                            use_for_shipping: true 
                        },
                        shipping: { 
                            address_id: addr.id,
                            first_name: addr.first_name,
                            last_name: addr.last_name,
                            email: addr.email || '{{ auth()->guard("customer")->user()?->email }}',
                            address: addressStr,
                            city: addr.city,
                            state: addr.state,
                            postcode: addr.postcode || '700000',
                            country: addr.country || 'VN',
                            phone: addr.phone
                        }
                    })
                });
                
                if (res.ok) {
                    const data = await res.json();
                    console.log('Address response:', data);
                    
                    if (data.data?.redirect) {
                        window.location.href = data.data.data;
                        return;
                    }
                    
                    const responseData = data.data?.data || data.data;
                    
                    if (this.cart?.have_stockable_items && responseData?.shippingMethods) {
                        this.shippingMethods = this.flattenShippingMethods(responseData.shippingMethods);
                        if (this.shippingMethods.length > 0) {
                            this.selectShipping(this.shippingMethods[0]);
                        }
                    } else if (Array.isArray(responseData)) {
                        this.paymentMethods = responseData;
                        if (this.paymentMethods.length > 0) {
                            this.selectPayment(this.paymentMethods[0]);
                        }
                    } else {
                        await this.loadPaymentAfterAddress();
                    }
                } else {
                    const err = await res.json();
                    console.error('Address error:', err);
                }
            } catch (e) {
                console.error('Error selecting address:', e);
            }
        },
        flattenShippingMethods(shippingMethods) {
            // Flatten nested shipping methods structure
            let methods = [];
            if (Array.isArray(shippingMethods)) {
                shippingMethods.forEach(carrier => {
                    if (carrier.rates) {
                        methods = methods.concat(carrier.rates);
                    }
                });
            } else if (typeof shippingMethods === 'object') {
                Object.values(shippingMethods).forEach(carrier => {
                    if (carrier.rates) {
                        methods = methods.concat(carrier.rates);
                    }
                });
            }
            return methods;
        },
        async selectShipping(method) {
            this.selectedShipping = method;
            try {
                const res = await fetch('/api/checkout/onepage/shipping-methods', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ shipping_method: method.method })
                });
                
                const data = await res.json();
                console.log('Shipping response:', data);
                
                if (res.ok) {
                    // Response has payment_methods key
                    let payments = data.payment_methods || data.data || data;
                    if (Array.isArray(payments) && payments.length > 0) {
                        this.paymentMethods = payments;
                        this.selectPayment(this.paymentMethods[0]);
                    }
                    await this.refreshCart();
                }
            } catch (e) {
                console.error('Error selecting shipping:', e);
            }
        },
        async loadPaymentAfterAddress() {
            // For digital products without shipping - call shipping endpoint with free method
            try {
                const res = await fetch('/api/checkout/onepage/shipping-methods', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ shipping_method: 'free_free' })
                });
                
                if (res.ok) {
                    const data = await res.json();
                    console.log('Payment after address response:', data);
                    
                    // Response has payment_methods key
                    let payments = data.payment_methods || data.data || data;
                    if (Array.isArray(payments) && payments.length > 0) {
                        this.paymentMethods = payments;
                        this.selectPayment(this.paymentMethods[0]);
                    }
                }
            } catch (e) {
                console.error('Error loading payment:', e);
            }
        },
        async selectPayment(method) {
            this.selectedPayment = method;
            try {
                await fetch('/api/checkout/onepage/payment-methods', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ payment: { method: method.method } })
                });
                await this.refreshCart();
            } catch (e) {
                console.error('Error selecting payment:', e);
            }
        },
        async refreshCart() {
            try {
                const res = await fetch('/api/checkout/onepage/summary', { 
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                if (res.ok) {
                    const data = await res.json();
                    this.cart = data.data;
                }
            } catch (e) {
                console.error('Error refreshing cart:', e);
            }
        },
        async placeOrder() {
            if (!this.canPlaceOrder) return;
            this.isPlacing = true;
            try {
                const res = await fetch('/api/checkout/onepage/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });
                const data = await res.json();
                if (data.data?.redirect_url) {
                    window.location.href = data.data.redirect_url;
                } else {
                    window.location.href = '/checkout/onepage/success';
                }
            } catch (e) {
                console.error('Error placing order:', e);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            } finally {
                this.isPlacing = false;
            }
        },
        formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0);
        }
    }
}).mount('#checkout-app');
</script>
@endpush
