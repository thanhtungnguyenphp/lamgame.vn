@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = $reviewHelper->getAverageRating($product);
    $percentageRatings = $reviewHelper->getPercentageRating($product);
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
    $productBaseImage = product_image()->getProductBaseImage($product);
    $productImages = product_image()->getGalleryImages($product);
@endphp

@extends('layouts.master')

@section('page_title', trim($product->meta_title) != "" ? $product->meta_title : $product->name)

@section('page_description', trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, ''))

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) !!}
        </script>
    @endif

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $product->name }}" />
    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />
    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

@section('content')
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        <div style="background: #f8f9fa; padding: 1rem 0;">
            <div class="container">
                <nav class="breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb-list">
                        <li><a href="{{ route('shop.home.index') }}">Trang chủ</a></li>
                        @foreach ($product->categories as $category)
                            <li><a href="{{ route('shop.product_or_category.index', $category->slug) }}">{{ $category->name }}</a></li>
                            @break
                        @endforeach
                        <li class="active">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    @endif

    <!-- Product Section -->
    <section style="padding: 3rem 0; background: white;">
        <div class="container">
            <!-- Product Information Vue Component -->
            <v-product>
                <!-- Product Loading State -->
                <div class="product-detail-loading">
                    <div class="product-gallery-loading">
                        <div class="shimmer main-image-loading"></div>
                        <div class="thumbnail-list-loading">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="shimmer thumbnail-loading"></div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="product-info-loading">
                        <div class="shimmer title-loading"></div>
                        <div class="shimmer price-loading"></div>
                        <div class="shimmer description-loading"></div>
                        <div class="shimmer button-loading"></div>
                    </div>
                </div>
            </v-product>
        </div>
    </section>

    <!-- Product Tabs Section -->
    <section style="background: #f8f9fa; padding: 3rem 0;">
        <div class="container">
            <div class="product-tabs">
                <div class="tab-nav">
                    <button class="tab-btn active" onclick="showTab(event, 'description')">📝 Mô tả sản phẩm</button>
                    @if(count($attributeData))
                        <button class="tab-btn" onclick="showTab(event, 'specifications')">⚙️ Thông số kỹ thuật</button>
                    @endif
                    <button class="tab-btn" onclick="showTab(event, 'reviews')">⭐ Đánh giá ({{ $reviewHelper->getTotalFeedback($product) }})</button>
                </div>
                
                <div class="tab-content">
                    <div id="description-tab" class="tab-pane active">
                        <div class="tab-content-wrapper">
                            <h3 style="color: #2c5f41; margin-bottom: 1rem;">Thông tin chi tiết</h3>
                            <div style="line-height: 1.8; color: #555;">
                                {!! $product->description !!}
                            </div>
                        </div>
                    </div>
                    
                    @if(count($attributeData))
                        <div id="specifications-tab" class="tab-pane">
                            <div class="tab-content-wrapper">
                                <h3 style="color: #2c5f41; margin-bottom: 1rem;">Thông số kỹ thuật</h3>
                                <div class="specifications-grid">
                                    @foreach ($customAttributeValues as $customAttributeValue)
                                        @if (! empty($customAttributeValue['value']))
                                            <div class="spec-item">
                                                <dt>{{ $customAttributeValue['label'] }}:</dt>
                                                <dd>{{ $customAttributeValue['value'] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div id="reviews-tab" class="tab-pane">
                        <div class="tab-content-wrapper">
                            @if ($reviewHelper->getTotalFeedback($product))
                                <div class="reviews-section">
                                    <h3 style="color: #2c5f41; margin-bottom: 1rem;">Đánh giá từ khách hàng</h3>
                                    <div class="review-summary">
                                        <div class="rating-display">
                                            <span class="rating-score">{{ number_format($avgRatings, 1) }}</span>
                                            <div class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span style="color: {{ $i <= $avgRatings ? '#ffc107' : '#ddd' }}; font-size: 1.5rem;">★</span>
                                                @endfor
                                            </div>
                                            <p>Dựa trên {{ $reviewHelper->getTotalFeedback($product) }} đánh giá</p>
                                        </div>
                                    </div>
                                    <p style="text-align: center; color: #666; margin-top: 2rem;">
                                        💭 Chức năng hiển thị chi tiết đánh giá đang được phát triển
                                    </p>
                                </div>
                            @else
                                <div class="no-reviews">
                                    <div style="text-align: center;">
                                        <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.6;">💭</div>
                                        <h3 style="color: #2c5f41; margin-bottom: 0.5rem;">Chưa có đánh giá nào</h3>
                                        <p style="color: #666;">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @pushOnce('styles')
        <style>
            /* Breadcrumb styling */
            .breadcrumb-list {
                display: flex;
                gap: 0.5rem;
                align-items: center;
                list-style: none;
                font-size: 0.9rem;
                color: #666;
                margin: 0;
                padding: 0;
            }
            
            .breadcrumb-list li:not(:last-child)::after {
                content: "›";
                margin-left: 0.5rem;
                color: #999;
            }
            
            .breadcrumb-list a {
                color: #2c5f41;
                text-decoration: none;
                transition: color 0.3s;
            }
            
            .breadcrumb-list a:hover {
                color: #1e4530;
            }
            
            .breadcrumb-list .active {
                color: #333;
                font-weight: 500;
            }
            
            /* Product detail styling */
            .product-detail-loading, .product-detail-active {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4rem;
                align-items: start;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .product-gallery-loading, .product-gallery {
                position: sticky;
                top: 2rem;
            }
            
            .main-image-loading, .main-image {
                width: 100%;
                height: 450px;
                border-radius: 15px;
                margin-bottom: 1rem;
                background: #f0f0f0;
                object-fit: cover;
            }
            
            .thumbnail-list-loading, .thumbnail-list {
                display: flex;
                gap: 0.5rem;
            }
            
            .thumbnail-loading, .thumbnail {
                width: 80px;
                height: 80px;
                border-radius: 10px;
                background: #f0f0f0;
                cursor: pointer;
                transition: all 0.3s;
                object-fit: cover;
                border: 2px solid transparent;
            }
            
            .thumbnail:hover, .thumbnail.active {
                transform: scale(1.05);
                border-color: #2c5f41;
            }
            
            .product-info-loading, .product-info {
                padding-top: 1rem;
            }
            
            .product-title {
                font-size: 2.2rem;
                color: #2c5f41;
                font-weight: 700;
                margin-bottom: 1rem;
                line-height: 1.3;
            }
            
            .product-rating {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }
            
            .product-price {
                font-size: 2rem;
                color: #ff6b35;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            .original-price {
                color: #999;
                text-decoration: line-through;
                font-size: 1.3rem;
                margin-left: 0.5rem;
                font-weight: normal;
            }
            
            .product-description {
                color: #666;
                line-height: 1.8;
                margin-bottom: 2rem;
                font-size: 1.1rem;
            }
            
            .product-actions {
                display: flex;
                gap: 1rem;
                align-items: center;
                flex-wrap: wrap;
                margin-bottom: 2rem;
            }
            
            .quantity-selector {
                display: flex;
                align-items: center;
                border: 2px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
            }
            
            .quantity-btn {
                background: #f8f9fa;
                border: none;
                padding: 1rem 1.2rem;
                cursor: pointer;
                font-size: 1.3rem;
                color: #2c5f41;
                transition: background 0.3s;
                font-weight: 600;
            }
            
            .quantity-btn:hover {
                background: #e9ecef;
            }
            
            .quantity-input {
                border: none;
                padding: 1rem;
                text-align: center;
                width: 70px;
                font-size: 1.1rem;
                font-weight: 600;
            }
            
            .add-to-cart-btn, .buy-now-btn {
                border: none;
                padding: 1rem 2.5rem;
                border-radius: 10px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
                min-width: 200px;
                text-align: center;
            }
            
            .add-to-cart-btn {
                background: #2c5f41;
                color: white;
            }
            
            .add-to-cart-btn:hover {
                background: #1e4530;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(44, 95, 65, 0.3);
            }
            
            .buy-now-btn {
                background: #ff6b35;
                color: white;
            }
            
            .buy-now-btn:hover {
                background: #e55a2b;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            }
            
            .contact-info {
                margin-top: 2rem;
                padding: 1.5rem;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 15px;
                border-left: 5px solid #2c5f41;
            }
            
            /* Product tabs styling */
            .product-tabs {
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                overflow: hidden;
            }
            
            .tab-nav {
                display: flex;
                background: #f8f9fa;
                border-bottom: 1px solid #ddd;
                flex-wrap: wrap;
            }
            
            .tab-btn {
                background: none;
                border: none;
                padding: 1.2rem 2rem;
                cursor: pointer;
                font-size: 1rem;
                color: #666;
                transition: all 0.3s;
                border-bottom: 3px solid transparent;
                font-weight: 500;
                flex: 1;
                min-width: 150px;
            }
            
            .tab-btn.active {
                color: #2c5f41;
                background: white;
                border-bottom-color: #2c5f41;
                font-weight: 600;
            }
            
            .tab-btn:hover {
                color: #2c5f41;
                background: rgba(44, 95, 65, 0.05);
            }
            
            .tab-content {
                min-height: 300px;
            }
            
            .tab-pane {
                display: none;
            }
            
            .tab-pane.active {
                display: block;
            }
            
            .tab-content-wrapper {
                padding: 2rem;
            }
            
            .specifications-grid {
                display: grid;
                gap: 1rem;
            }
            
            .spec-item {
                display: flex;
                justify-content: space-between;
                padding: 1rem;
                background: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #2c5f41;
            }
            
            .spec-item dt {
                font-weight: 600;
                color: #2c5f41;
            }
            
            .spec-item dd {
                color: #666;
                margin: 0;
            }
            
            .review-summary {
                text-align: center;
                padding: 2rem;
                background: #f8f9fa;
                border-radius: 10px;
                margin-bottom: 2rem;
            }
            
            .rating-display {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
            
            .rating-score {
                font-size: 3rem;
                font-weight: 700;
                color: #2c5f41;
            }
            
            /* Shimmer effect */
            .shimmer {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
            }
            
            .title-loading {
                height: 2.5rem;
                width: 80%;
                margin-bottom: 1rem;
                border-radius: 4px;
            }
            
            .price-loading {
                height: 2rem;
                width: 40%;
                margin-bottom: 1rem;
                border-radius: 4px;
            }
            
            .description-loading {
                height: 5rem;
                width: 100%;
                margin-bottom: 2rem;
                border-radius: 4px;
            }
            
            .button-loading {
                height: 3.5rem;
                width: 60%;
                border-radius: 10px;
            }
            
            @keyframes loading {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
            
            /* Responsive design */
            @media (max-width: 968px) {
                .product-detail-loading, .product-detail-active {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }
                
                .product-gallery-loading, .product-gallery {
                    position: static;
                }
                
                .product-title {
                    font-size: 1.8rem;
                }
                
                .product-actions {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                .add-to-cart-btn,
                .buy-now-btn {
                    min-width: auto;
                    width: 100%;
                }
                
                .tab-nav {
                    flex-direction: column;
                }
                
                .tab-btn {
                    flex: none;
                }
            }
        </style>
    @endpushOnce

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <form
                ref="formData"
                @submit="handleSubmit($event, addToCart)"
            >
                <input
                    type="hidden"
                    name="product_id"
                    value="{{ $product->id }}"
                >

                <input
                    type="hidden"
                    name="is_buy_now"
                    v-model="is_buy_now"
                >

                <div class="product-detail-active">
                    <!-- Product Gallery -->
                    <div class="product-gallery">
                        <img 
                            :src="mainImage" 
                            :alt="product.name"
                            class="main-image"
                            @click="openImageModal"
                        />
                        <div class="thumbnail-list">
                            <img 
                                v-for="(image, index) in productImages" 
                                :key="index"
                                :src="image.small_image_url" 
                                :alt="product.name"
                                class="thumbnail"
                                :class="{ 'active': mainImage === image.large_image_url }"
                                @click="mainImage = image.large_image_url"
                            />
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="product-info">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        
                        <!-- Rating -->
                        @if ($totalRatings = $reviewHelper->getTotalFeedback($product))
                            <div class="product-rating">
                                <div class="rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= $avgRatings ? '#ffc107' : '#ddd' }}; font-size: 1.3rem;">★</span>
                                    @endfor
                                </div>
                                <span style="color: #666;">({{ $totalRatings }} đánh giá)</span>
                            </div>
                        @endif
                        
                        <!-- Price -->
                        <div class="product-price">
                            {!! $product->getTypeInstance()->getPriceHtml() !!}
                        </div>

                        <!-- Short Description -->
                        <div class="product-description">
                            {!! $product->short_description !!}
                        </div>

                        <!-- Product Actions -->
                        <div class="product-actions">
                            @if ($product->getTypeInstance()->showQuantityBox())
                                <div class="quantity-selector">
                                    <button type="button" class="quantity-btn" @click="decreaseQty">−</button>
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        v-model="quantity" 
                                        class="quantity-input"
                                        min="1"
                                    />
                                    <button type="button" class="quantity-btn" @click="increaseQty">+</button>
                                </div>
                            @endif

                            @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                                <button
                                    type="submit"
                                    class="add-to-cart-btn"
                                    :disabled="! product.isSaleable || isStoring.addToCart"
                                    @click="is_buy_now=0;"
                                >
                                    <span v-if="isStoring.addToCart">🔄 Đang thêm...</span>
                                    <span v-else>🛒 Thêm vào giỏ hàng</span>
                                </button>
                            @endif
                        </div>

                        @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                            <button
                                type="submit"
                                class="buy-now-btn"
                                :disabled="! product.isSaleable || isStoring.buyNow"
                                @click="is_buy_now=1;"
                                style="width: 100%; margin-top: 0.5rem;"
                            >
                                <span v-if="isStoring.buyNow">⚡ Đang xử lý...</span>
                                <span v-else>⚡ Mua ngay</span>
                            </button>
                        @endif

                        <!-- Contact Info -->
                        <div class="contact-info">
                            <h4 style="color: #2c5f41; margin-bottom: 1rem; font-size: 1.1rem;">💬 Cần tư vấn?</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                                <p style="color: #666; margin: 0;">📞 Hotline: <strong style="color: #2c5f41;">0908 123 456</strong></p>
                                <p style="color: #666; margin: 0;">✉️ Email: <strong style="color: #2c5f41;">hello@emsaigon.com</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        product: @json($product),
                        productImages: @json($productImages),
                        mainImage: "{{ $productBaseImage['large_image_url'] }}",
                        quantity: 1,
                        is_buy_now: 0,
                        isStoring: {
                            addToCart: false,
                            buyNow: false,
                        },
                    }
                },

                methods: {
                    increaseQty() {
                        this.quantity++;
                    },

                    decreaseQty() {
                        if (this.quantity > 1) {
                            this.quantity--;
                        }
                    },

                    openImageModal() {
                        // Simple image preview
                        window.open(this.mainImage, '_blank');
                    },

                    handleSubmit(event, callback) {
                        event.preventDefault();
                        callback();
                    },

                    addToCart() {
                        const operation = this.is_buy_now ? 'buyNow' : 'addToCart';
                        this.isStoring[operation] = true;

                        let formData = new FormData(this.$refs.formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);
                                    
                                    // Show success message
                                    alert('✅ ' + response.data.message);

                                    if (response.data.redirect) {
                                        window.location.href = response.data.redirect;
                                    }
                                } else {
                                    alert('⚠️ ' + response.data.data.message);
                                }

                                this.isStoring[operation] = false;
                            })
                            .catch(error => {
                                this.isStoring[operation] = false;
                                alert('❌ ' + (error.response?.data?.message || 'Có lỗi xảy ra'));
                            });
                    },
                },
            });
        </script>
    @endpushOnce

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    <!-- JavaScript for tabs -->
    <script>
        function showTab(event, tabName) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab and mark button as active
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
    </script>
@endsection
