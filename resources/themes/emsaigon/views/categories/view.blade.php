@extends('layouts.master')

@section('page_title', trim($category->meta_title) != "" ? $category->meta_title : $category->name)

@section('page_description', trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, ''))

<!-- SEO Meta Content -->
@push('meta')
    <meta 
        name="description" 
        content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"
    />

    <meta 
        name="keywords" 
        content="{{ $category->meta_keywords }}"
    />

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@endPush

@section('content')
    {!! view_render_event('bagisto.shop.categories.view.banner_path.before') !!}

    <!-- Hero Section for Category -->
    <section style="background: linear-gradient(135deg, #2c5f41 0%, #3d7c59 100%); color: white; padding: 3rem 0; text-align: center;">
        <div class="container">
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 700;">{{ $category->name }}</h1>
            @if ($category->description)
                <p style="font-size: 1.2rem; opacity: 0.9; max-width: 800px; margin: 0 auto;">
                    {!! \Illuminate\Support\Str::limit(strip_tags($category->description), 200) !!}
                </p>
            @endif
        </div>
    </section>

    <!-- Hero Image -->
    @if ($category->banner_path)
        <div class="container" style="margin-top: 2rem;">
            <img
                style="width: 100%; height: 300px; object-fit: cover; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);"
                src="{{ $category->banner_url }}"
                alt="{{ $category->name }}"
            />
        </div>
    @endif

    {!! view_render_event('bagisto.shop.categories.view.banner_path.after') !!}

    {!! view_render_event('bagisto.shop.categories.view.description.before') !!}

    @if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
        @if ($category->description)
            <div class="container" style="margin-top: 2rem;">
                <div style="max-width: 1000px; margin: 0 auto; padding: 2rem; background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <div style="color: #555; line-height: 1.8;">
                        {!! $category->description !!}
                    </div>
                </div>
            </div>
        @endif
    @endif
        
    {!! view_render_event('bagisto.shop.categories.view.description.after') !!}

    {{-- @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description'])) --}}
    @if(true)
        <!-- Products Section -->
        <section style="background: #f8f9fa; padding: 3rem 0; margin-top: 2rem;">
            <div class="container">
            <h2 style="text-align: center; color: #2c5f41; font-size: 2rem; margin-bottom: 2rem;">Sản phẩm trong danh mục</h2>
            
                
                <!-- Category Vue Component -->
                <v-category>
                    <!-- Loading State -->
                    <div class="loading-container">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
                            @for ($i = 1; $i <= 6; $i++)
                                <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    <div class="shimmer" style="height: 200px;"></div>
                                    <div style="padding: 1.5rem;">
                                        <div class="shimmer" style="height: 1rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="shimmer" style="height: 0.8rem; width: 60%; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="shimmer" style="height: 1.2rem; width: 40%; border-radius: 4px;"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </v-category>
            </div>
        </section>
    @endif

    @pushOnce('styles')
        <style>
            /* Shimmer effect styles */
            .shimmer {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
            }
            @keyframes loading {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
            }
            
            /* EmSaiGon category styling */
            .products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 1rem;
            }
            
            .product-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            }
            
            .filter-controls {
                background: white;
                padding: 1.5rem;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                margin-bottom: 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .filter-controls select {
                padding: 0.5rem 1rem;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-size: 0.9rem;
                background: white;
                cursor: pointer;
                transition: border-color 0.3s;
            }
            
            .filter-controls select:focus {
                outline: none;
                border-color: #2c5f41;
            }
            
            .load-more-btn {
                background: #2c5f41;
                color: white;
                border: none;
                padding: 1rem 3rem;
                border-radius: 10px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: block;
                margin: 3rem auto 0;
            }
            
            .load-more-btn:hover {
                background: #1e4530;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(44, 95, 65, 0.3);
            }
            
            .load-more-btn:disabled {
                background: #ccc;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }
            
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .empty-state .icon {
                font-size: 4rem;
                margin-bottom: 1rem;
                opacity: 0.6;
            }
            
            @media (max-width: 768px) {
                .products-grid {
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 1rem;
                }
                
                .filter-controls {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                .filter-controls > div {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                }
            }
        </style>
    @endpushOnce

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-category-template"
        >
            <div>
                <!-- Filter Controls -->
                <div class="filter-controls" v-if="!isLoading">
                    <div>
                        <span style="color: #666; font-weight: 500;">
                            Hiển thị <strong v-text="products.length"></strong> / <strong v-text="totalProducts"></strong> sản phẩm
                        </span>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <select v-model="filters.toolbar.sort" style="min-width: 150px;">
                            <option value="">Sắp xếp theo</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="name-desc">Tên Z-A</option>
                            <option value="price-asc">Giá thấp → cao</option>
                            <option value="price-desc">Giá cao → thấp</option>
                            <option value="created_at-desc">Mới nhất</option>
                        </select>
                        <select v-model="filters.toolbar.limit" style="min-width: 120px;">
                            <option value="12">12 / trang</option>
                            <option value="24">24 / trang</option>
                            <option value="36">36 / trang</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" v-if="!isLoading && products.length">
                    <div v-for="product in products" :key="product.id" class="product-card">
                        <div style="position: relative;">
                            <img 
                                :src="product.base_image?.medium_image_url || '/themes/shop/emsaigon/images/placeholder.jpg'" 
                                :alt="product.name"
                                style="width: 100%; height: 220px; object-fit: cover;"
                            />
                            <div 
                                v-if="product.special_price" 
                                style="position: absolute; top: 10px; right: 10px; background: #ff6b35; color: white; padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;"
                            >
                                SALE
                            </div>
                        </div>
                        <div style="padding: 1.5rem;">
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem; color: #333; font-size: 1.1rem; min-height: 3rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" v-text="product.name"></h4>
                            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem; min-height: 2.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" v-text="product.short_description"></p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span v-if="product.special_price" style="color: #ff6b35; font-weight: 700; font-size: 1.2rem;" v-text="product.formatted_price?.special_price || product.special_price"></span>
                                    <span v-else style="color: #2c5f41; font-weight: 700; font-size: 1.2rem;" v-text="product.formatted_price?.regular_price || product.price"></span>
                                    <br>
                                    <span v-if="product.special_price" style="color: #999; text-decoration: line-through; font-size: 0.9rem;" v-text="product.formatted_price?.regular_price || product.price"></span>
                                </div>
                                <a 
                                    :href="'/' + product.url_key" 
                                    style="background: #2c5f41; color: white; padding: 0.7rem 1.5rem; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s ease;"
                                    onmouseover="this.style.background='#1e4530'; this.style.transform='translateY(-1px)'"
                                    onmouseout="this.style.background='#2c5f41'; this.style.transform='translateY(0)'"
                                >
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="!isLoading && !products.length" class="empty-state">
                    <div class="icon">📦</div>
                    <h3 style="color: #2c5f41; margin-bottom: 0.5rem; font-size: 1.5rem;">Chưa có sản phẩm nào</h3>
                    <p style="color: #666; font-size: 1rem;">Danh mục này hiện chưa có sản phẩm. Hãy quay lại sau nhé!</p>
                </div>

                <!-- Load More Button -->
                <button
                    v-if="links.next && !isLoading"
                    @click="loadMoreProducts"
                    class="load-more-btn"
                    :disabled="loader"
                >
                    <span v-if="loader">🔄 Đang tải...</span>
                    <span v-else>📦 Xem thêm sản phẩm</span>
                </button>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                data() {
                    return {
                        isLoading: true,
                        filters: {
                            toolbar: {
                                sort: '',
                                limit: 12
                            },
                            filter: {},
                        },
                        products: [],
                        totalProducts: 0,
                        links: {},
                        loader: false,
                    }
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar);
                        return this.removeJsonEmptyValues(queryParams);
                    },

                    queryString() {
                        return this.jsonToQueryString(this.queryParams);
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },

                    queryString() {
                        window.history.replaceState({}, '', '?' + this.queryString);
                    },
                },

                mounted() {
                    this.getProducts();
                },

                methods: {
                    getProducts() {
                        this.isLoading = true;

                        this.$axios.get("{{ route('shop.api.products.index', ['category_id' => $category->id]) }}", {
                            params: this.queryParams 
                        })
                            .then(response => {
                                this.isLoading = false;
                                this.products = response.data.data;
                                this.totalProducts = response.data.meta?.total || response.data.data.length;
                                this.links = response.data.links || {};
                            }).catch(error => {
                                console.log(error);
                                this.isLoading = false;
                            });
                    },

                    loadMoreProducts() {
                        if (! this.links.next) {
                            return;
                        }

                        this.loader = true;

                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.loader = false;
                                this.products = [...this.products, ...response.data.data];
                                this.links = response.data.links || {};
                            }).catch(error => {
                                console.log(error);
                                this.loader = false;
                            });
                    },

                    removeJsonEmptyValues(params) {
                        Object.keys(params).forEach(function (key) {
                            if ((! params[key] && params[key] !== undefined) || params[key] === '') {
                                delete params[key];
                            }

                            if (Array.isArray(params[key])) {
                                params[key] = params[key].join(',');
                            }
                        });

                        return params;
                    },

                    jsonToQueryString(params) {
                        let parameters = new URLSearchParams();

                        for (const key in params) {
                            parameters.append(key, params[key]);
                        }

                        return parameters.toString();
                    }
                },
            });
        </script>
    @endpushOnce
@endsection
