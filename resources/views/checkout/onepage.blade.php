@extends('layouts.master')

@section('page_title', 'Thanh toán - Làm Game')

@push('styles')
<style>
    :root {
        --co-bg: #070B14; --co-surface: #111827; --co-surface-2: rgba(17,24,39,.6);
        --co-border: rgba(124,92,255,.15); --co-text: #F5F7FA; --co-text-2: #B7C0D1; --co-muted: #7A8599;
        --co-accent: #7C5CFF; --co-accent-2: #00D1FF; --co-success: #34D399; --co-error: #F87171;
        --co-radius: 14px;
    }
    [v-cloak] { display: none !important; }
    .checkout-container { padding: 2rem 0; min-height: 60vh; color: var(--co-text); }
    .checkout-container h1 { color: var(--co-text); }
    .checkout-content { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start; }
    .checkout-steps { background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: var(--co-radius); padding: 1.5rem; }
    .checkout-summary { background: var(--co-surface-2); border: 1px solid var(--co-border); border-radius: var(--co-radius); padding: 1.5rem; position: sticky; top: 100px; }
    .step-section { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--co-border); }
    .step-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .step-title { font-size: 1.15rem; font-weight: 700; color: var(--co-text); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .step-title .step-number { background: linear-gradient(135deg,var(--co-accent),var(--co-accent-2)); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; }
    .address-card { border: 1px solid var(--co-border); border-radius: 10px; padding: 1rem; cursor: pointer; transition: all 0.2s; margin-bottom: 0.5rem; color: var(--co-text-2); }
    .address-card:hover { border-color: var(--co-accent); }
    .address-card.selected { border-color: var(--co-accent); background: rgba(124,92,255,.08); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--co-text-2); }
    .form-group input, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid var(--co-border); border-radius: 8px; font-size: 1rem; background: var(--co-surface); color: var(--co-text); }
    .form-group input::placeholder { color: var(--co-muted); }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--co-accent); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .summary-item { display: flex; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--co-border); }
    .summary-item:last-child { border-bottom: none; }
    .summary-item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; background: var(--co-surface); }
    .summary-row { display: flex; justify-content: space-between; padding: 0.5rem 0; color: var(--co-text-2); }
    .summary-total { font-size: 1.15rem; font-weight: 700; color: var(--co-text); border-top: 1px solid var(--co-border); padding-top: 0.75rem; margin-top: 0.5rem; }
    .summary-total span:last-child { color: var(--co-accent-2); }
    .btn-proceed { width: 100%; padding: 0.9rem; background: linear-gradient(135deg,var(--co-accent),var(--co-accent-2)); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 1rem; transition: opacity .2s; }
    .btn-proceed:hover { opacity: .9; }
    .btn-proceed:disabled { background: var(--co-surface); color: var(--co-muted); cursor: not-allowed; }
    .shipping-option, .payment-option { border: 1px solid var(--co-border); border-radius: 10px; padding: 1rem; margin-bottom: 0.5rem; cursor: pointer; display: flex; align-items: center; gap: 0.75rem; color: var(--co-text-2); }
    .shipping-option:hover, .payment-option:hover { border-color: var(--co-accent); }
    .shipping-option.selected, .payment-option.selected { border-color: var(--co-accent); background: rgba(124,92,255,.08); }
    .radio-circle { width: 20px; height: 20px; border: 2px solid var(--co-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .radio-circle.checked { border-color: var(--co-accent); }
    .radio-circle.checked::after { content: ''; width: 10px; height: 10px; background: var(--co-accent); border-radius: 50%; }
    .login-prompt { background: rgba(0,209,255,.08); border: 1px solid rgba(0,209,255,.25); border-radius: 10px; padding: 1rem; margin-bottom: 1rem; color: var(--co-text-2); }
    .login-prompt a { color: var(--co-accent-2); font-weight: 600; }
    .error-msg { color: var(--co-error); font-size: 0.875rem; margin-top: 0.25rem; }
    .checkout-shimmer div { background: linear-gradient(90deg, rgba(124,92,255,.06) 25%, rgba(124,92,255,.12) 50%, rgba(124,92,255,.06) 75%) !important; background-size: 200% 100% !important; animation: coShimmer 1.3s infinite; }
    @keyframes coShimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    @media (max-width: 768px) {
        .checkout-content { grid-template-columns: 1fr; }
        .checkout-summary { position: static; order: -1; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="container">
        <h1 style="margin-bottom: 1.5rem;">💳 Thanh toán</h1>
        
        <div id="checkout-app" v-cloak>
            <div v-if="loading" style="text-align: center; padding: 3rem;">
                <div class="checkout-shimmer">
                    <div style="height: 24px; width: 200px; background: #e5e7eb; border-radius: 4px; margin: 0 auto 1rem;"></div>
                    <div style="height: 16px; width: 150px; background: #e5e7eb; border-radius: 4px; margin: 0 auto;"></div>
                </div>
            </div>
            
            <div v-else class="checkout-content">
                <div class="checkout-steps">
                    <!-- Login prompt for guest -->
                    @guest('customer')
                    <div class="login-prompt">
                        Đã có tài khoản? <a href="{{ url('/auth/login?redirect=' . urlencode(request()->url())) }}">Đăng nhập</a> để thanh toán nhanh hơn.
                    </div>
                    @endguest

                    <!-- Address Section -->
                    <div class="step-section">
                        <div class="step-title">
                            <span class="step-number">1</span>
                            Thông tin giao hàng
                        </div>
                        
                        @auth('customer')
                        <!-- Logged in: Show saved addresses -->
                        <div v-if="addresses.length > 0">
                            <div 
                                v-for="addr in addresses" 
                                :key="addr.id"
                                class="address-card"
                                :class="{ selected: selectedAddress?.id === addr.id }"
                                @click="selectSavedAddress(addr)"
                            >
                                <div style="font-weight: 600;">@{{ addr.first_name }} @{{ addr.last_name }}</div>
                                <div style="color: var(--co-muted); font-size: 0.9rem;">@{{ addr.address1 || addr.address }}, @{{ addr.city }}, @{{ addr.state }}</div>
                                <div style="color: var(--co-muted); font-size: 0.9rem;">@{{ addr.phone }}</div>
                            </div>
                            <div class="address-card" :class="{ selected: useNewAddress }" @click="useNewAddress = true; selectedAddress = null;">
                                <div style="text-align: center; color: var(--co-accent-2);">+ Nhập địa chỉ mới</div>
                            </div>
                        </div>
                        @endauth

                        <!-- Guest or new address form -->
                        <div v-if="!isLoggedIn || useNewAddress || addresses.length === 0">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Họ *</label>
                                    <input type="text" v-model="guestAddress.first_name" placeholder="Nguyễn">
                                    <div class="error-msg" v-if="errors.first_name">@{{ errors.first_name }}</div>
                                </div>
                                <div class="form-group">
                                    <label>Tên *</label>
                                    <input type="text" v-model="guestAddress.last_name" placeholder="Văn A">
                                    <div class="error-msg" v-if="errors.last_name">@{{ errors.last_name }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" v-model="guestAddress.email" placeholder="email@example.com">
                                <div class="error-msg" v-if="errors.email">@{{ errors.email }}</div>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại *</label>
                                <input type="tel" v-model="guestAddress.phone" placeholder="0901234567">
                                <div class="error-msg" v-if="errors.phone">@{{ errors.phone }}</div>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ *</label>
                                <input type="text" v-model="guestAddress.address" placeholder="Số nhà, tên đường">
                                <div class="error-msg" v-if="errors.address">@{{ errors.address }}</div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Thành phố *</label>
                                    <input type="text" v-model="guestAddress.city" placeholder="Hồ Chí Minh">
                                    <div class="error-msg" v-if="errors.city">@{{ errors.city }}</div>
                                </div>
                                <div class="form-group">
                                    <label>Tỉnh/Thành</label>
                                    <input type="text" v-model="guestAddress.state" placeholder="Hồ Chí Minh">
                                </div>
                            </div>
                            <button type="button" class="btn-proceed" style="margin-top: 0.5rem; margin-bottom: 0;" @click="submitAddress" :disabled="addressSubmitting">
                                @{{ addressSubmitting ? 'Đang xử lý...' : 'Tiếp tục' }}
                            </button>
                        </div>
                    </div>

                    <!-- Shipping Section -->
                    <div class="step-section" v-if="shippingMethods.length > 0">
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
                                <div style="color: var(--co-muted); font-size: 0.875rem;">@{{ method.method_description }}</div>
                            </div>
                            <div style="font-weight: 600; color: var(--co-accent-2);">@{{ formatPrice(method.price) }}</div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="step-section" v-if="paymentMethods.length > 0">
                        <div class="step-title">
                            <span class="step-number">@{{ shippingMethods.length > 0 ? '3' : '2' }}</span>
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
                                <div style="color: var(--co-muted); font-size: 0.875rem;">@{{ method.description }}</div>
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
                            <div style="color: var(--co-muted); font-size: 0.875rem;">@{{ formatPrice(item.price) }} × @{{ item.quantity }}</div>
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
                            <span style="color: var(--co-error);">-@{{ formatPrice(cart?.discount_amount) }}</span>
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

                    <!-- PayPal Smart Button -->
                    <div v-if="selectedPayment?.method === 'paypal_smart_button'" id="paypal-button-container" style="margin-top: 1rem;"></div>
                    
                    <!-- Lemon Squeezy Button -->
                    <button 
                        v-else-if="selectedPayment?.method === 'lemonsqueezy'"
                        class="btn-proceed" 
                        style="background: #7c3aed;"
                        @click="startLemonCheckout"
                        :disabled="!canPlaceOrder || isPlacing"
                    >
                        @{{ isPlacing ? 'Đang xử lý...' : '💳 Thanh toán quốc tế (Visa, MC, PayPal)' }}
                    </button>
                    
                    <!-- Normal Place Order Button -->
                    <button 
                        v-else
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
@php
    $paypalActive = (bool) core()->getConfigData('sales.payment_methods.paypal_smart_button.active');
    $paypalClientId = core()->getConfigData('sales.payment_methods.paypal_smart_button.client_id');
    // Always use USD for PayPal since VND is not supported
    $currencyToUse = 'USD';
@endphp

@if($paypalActive && $paypalClientId)
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency={{ $currencyToUse }}" data-partner-attribution-id="Bagisto_Cart"></script>
@endif

<script src="https://app.lemonsqueezy.com/js/lemon.js" defer></script>

<script>
// Đợi Vue load (Vue CDN dùng defer trong master layout → chạy trước DOMContentLoaded)
function _initCheckout() {
    if (typeof Vue === 'undefined') {
        console.error('Vue chưa sẵn sàng — checkout không khởi tạo được.');
        document.getElementById('checkout-app')?.removeAttribute('v-cloak');
        document.getElementById('checkout-app').innerHTML = '<p style="text-align:center;padding:2rem;color:#F87171">Có lỗi tải trang. Vui lòng tải lại (Ctrl+F5).</p>';
        return;
    }

    const { createApp } = Vue;

createApp({
    data() {
        return {
            loading: true,
            isLoggedIn: {{ auth()->guard('customer')->check() ? 'true' : 'false' }},
            cart: null,
            addresses: [],
            shippingMethods: [],
            paymentMethods: [],
            selectedAddress: null,
            selectedShipping: null,
            selectedPayment: null,
            isPlacing: false,
            useNewAddress: false,
            addressSubmitting: false,
            addressSubmitted: false,
            guestAddress: {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                state: '',
                postcode: '700000',
                country: 'VN'
            },
            errors: {}
        }
    },
    computed: {
        canPlaceOrder() {
            if (!this.addressSubmitted && !this.selectedAddress) return false;
            if (this.cart?.have_stockable_items && this.shippingMethods.length > 0 && !this.selectedShipping) return false;
            if (!this.selectedPayment) return false;
            return true;
        }
    },
    mounted() {
        this.loadData();
        // Setup Lemon.js event handler
        const checkLemon = () => {
            if (window.LemonSqueezy) {
                window.createLemonSqueezy?.();
                window.LemonSqueezy.Setup({
                    eventHandler: (event) => {
                        if (event.event === 'Checkout.Success') {
                            window.location.href = '/lemonsqueezy/success';
                        }
                    }
                });
            } else {
                setTimeout(checkLemon, 300);
            }
        };
        checkLemon();
    },
    methods: {
        async loadData() {
            try {
                const summaryRes = await fetch('/api/checkout/onepage/summary', { 
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                
                if (summaryRes.ok) {
                    const summaryData = await summaryRes.json();
                    this.cart = summaryData.data;
                }
                
                if (this.isLoggedIn) {
                    const addrRes = await fetch('/api/customer/addresses', { 
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                    
                    if (addrRes.ok) {
                        const addrData = await addrRes.json();
                        this.addresses = addrData.data || [];
                    }
                }
            } catch (e) {
                console.error('Error:', e);
            } finally {
                this.loading = false;
            }
        },
        
        validateAddress() {
            this.errors = {};
            if (!this.guestAddress.first_name) this.errors.first_name = 'Vui lòng nhập họ';
            if (!this.guestAddress.last_name) this.errors.last_name = 'Vui lòng nhập tên';
            if (!this.guestAddress.email) this.errors.email = 'Vui lòng nhập email';
            else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.guestAddress.email)) this.errors.email = 'Email không hợp lệ';
            if (!this.guestAddress.phone) this.errors.phone = 'Vui lòng nhập số điện thoại';
            if (!this.guestAddress.address) this.errors.address = 'Vui lòng nhập địa chỉ';
            if (!this.guestAddress.city) this.errors.city = 'Vui lòng nhập thành phố';
            return Object.keys(this.errors).length === 0;
        },
        
        async submitAddress() {
            if (!this.validateAddress()) return;
            
            this.addressSubmitting = true;
            try {
                const addr = this.guestAddress;
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
                            first_name: addr.first_name,
                            last_name: addr.last_name,
                            email: addr.email,
                            address: [addr.address],
                            city: addr.city,
                            state: addr.state || addr.city,
                            postcode: addr.postcode,
                            country: addr.country,
                            phone: addr.phone,
                            use_for_shipping: true 
                        },
                        shipping: { 
                            first_name: addr.first_name,
                            last_name: addr.last_name,
                            email: addr.email,
                            address: [addr.address],
                            city: addr.city,
                            state: addr.state || addr.city,
                            postcode: addr.postcode,
                            country: addr.country,
                            phone: addr.phone
                        }
                    })
                });
                
                const data = await res.json();
                
                if (res.ok) {
                    // Check if redirect is required (guest checkout not allowed)
                    if (data.data?.redirect === true) {
                        alert('Sản phẩm này yêu cầu đăng nhập để mua. Vui lòng đăng nhập.');
                        window.location.href = data.data.data || '/auth/login';
                        return;
                    }
                    
                    this.addressSubmitted = true;
                    const responseData = data.data?.data || data.data;
                    
                    if (responseData?.shippingMethods) {
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
                        await this.loadPaymentMethods();
                    }
                } else {
                    console.error('Address error:', data);
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const field = key.replace('billing.', '').replace('shipping.', '');
                            this.errors[field] = data.errors[key][0];
                        });
                    }
                }
            } catch (e) {
                console.error('Error:', e);
            } finally {
                this.addressSubmitting = false;
            }
        },
        
        async selectSavedAddress(addr) {
            this.selectedAddress = addr;
            this.useNewAddress = false;
            this.addressSubmitting = true;
            
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
                            address: [addressStr],
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
                            address: [addressStr],
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
                    this.addressSubmitted = true;
                    const responseData = data.data?.data || data.data;
                    
                    if (responseData?.shippingMethods) {
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
                        await this.loadPaymentMethods();
                    }
                }
            } catch (e) {
                console.error('Error:', e);
            } finally {
                this.addressSubmitting = false;
            }
        },
        
        flattenShippingMethods(shippingMethods) {
            let methods = [];
            if (Array.isArray(shippingMethods)) {
                shippingMethods.forEach(carrier => {
                    if (carrier.rates) methods = methods.concat(carrier.rates);
                });
            } else if (typeof shippingMethods === 'object') {
                Object.values(shippingMethods).forEach(carrier => {
                    if (carrier.rates) methods = methods.concat(carrier.rates);
                });
            }
            return methods;
        },
        
        async loadPaymentMethods() {
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
                    let payments = data.payment_methods || data.data || data;
                    if (Array.isArray(payments) && payments.length > 0) {
                        this.paymentMethods = payments;
                        this.selectPayment(this.paymentMethods[0]);
                    }
                }
            } catch (e) {
                console.error('Error:', e);
            }
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
                
                if (res.ok) {
                    const data = await res.json();
                    let payments = data.payment_methods || data.data || data;
                    if (Array.isArray(payments) && payments.length > 0) {
                        this.paymentMethods = payments;
                        this.selectPayment(this.paymentMethods[0]);
                    }
                    await this.refreshCart();
                }
            } catch (e) {
                console.error('Error:', e);
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
                
                // Render PayPal button if PayPal selected
                if (method.method === 'paypal_smart_button') {
                    this.$nextTick(() => this.renderPayPalButton());
                }
            } catch (e) {
                console.error('Error:', e);
            }
        },
        
        renderPayPalButton() {
            const container = document.getElementById('paypal-button-container');
            if (!container || typeof paypal === 'undefined') return;
            
            container.innerHTML = '';
            
            paypal.Buttons({
                style: { layout: 'vertical', shape: 'rect' },
                
                createOrder: async (data, actions) => {
                    try {
                        const res = await fetch('/paypal/smart-button/create-order', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin'
                        });
                        const result = await res.json();
                        if (result.result?.id) {
                            return result.result.id;
                        }
                        const msg = (typeof result.message === 'string' ? result.message : JSON.stringify(result.message)) || 'Failed to create PayPal order';
                        console.error('PayPal create-order response:', result);
                        throw new Error(msg);
                    } catch (e) {
                        console.error('PayPal create order error:', e);
                        alert('Không thể tạo đơn hàng PayPal: ' + (e.message || 'lỗi không xác định'));
                        throw e;
                    }
                },
                
                onApprove: async (data, actions) => {
                    try {
                        const res = await fetch('/paypal/smart-button/capture-order', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ orderData: data })
                        });
                        const result = await res.json();
                        if (result.success) {
                            window.location.href = result.redirect_url || '/checkout/onepage/success';
                        } else {
                            alert(result.message || 'Thanh toán thất bại');
                        }
                    } catch (e) {
                        console.error('PayPal capture error:', e);
                        alert('Có lỗi xảy ra khi xử lý thanh toán.');
                    }
                },
                
                onCancel: (data) => {
                    console.log('PayPal payment cancelled');
                },
                
                onError: (err) => {
                    console.error('PayPal error:', err);
                    alert('Có lỗi xảy ra với PayPal. Vui lòng thử lại.');
                }
            }).render('#paypal-button-container');
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
                console.error('Error:', e);
            }
        },
        
        async startLemonCheckout() {
            if (!this.canPlaceOrder) return;
            this.isPlacing = true;
            try {
                const res = await fetch('/lemonsqueezy/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });
                const data = await res.json();
                if (data.checkout_url) {
                    // LS-306: Mobile → redirect (overlay UX kém trên mobile)
                    const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent)
                        || window.innerWidth < 768;

                    if (!isMobile && window.LemonSqueezy) {
                        window.LemonSqueezy.Url.Open(data.checkout_url);
                    } else {
                        window.location.href = data.checkout_url;
                    }
                } else {
                    alert(data.error || 'Không thể tạo phiên thanh toán.');
                }
            } catch (e) {
                console.error('Error:', e);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            } finally {
                this.isPlacing = false;
            }
        },

        async placeOrder() {
            if (!this.canPlaceOrder) return;
            if (this.selectedPayment?.method === 'lemonsqueezy') return;
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
                if (res.ok) {
                    window.location.href = data.data?.redirect_url || '/checkout/onepage/success';
                } else {
                    alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                }
            } catch (e) {
                console.error('Error:', e);
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            } finally {
                this.isPlacing = false;
            }
        },
        
        formatPrice(price) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(price || 0);
        }
    }
}).mount('#checkout-app');
} // end _initCheckout

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', _initCheckout);
} else {
    // Nếu DOM đã parse xong (vd script chạy muộn), gọi ngay
    _initCheckout();
}
</script>
@endpush
