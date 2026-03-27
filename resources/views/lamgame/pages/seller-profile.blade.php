@extends('layouts.master')

@section('title', $seller->shop_name . ' - Seller Profile')

@section('content')
<!-- Seller Header -->
<section style="background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 3rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            @if($seller->logo)
                <img src="{{ asset('storage/' . $seller->logo) }}" alt="{{ $seller->shop_name }}"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
            @else
                <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                    {{ mb_substr($seller->shop_name, 0, 1) }}
                </div>
            @endif
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $seller->shop_name }}</h1>
                @if($seller->shop_description)
                    <p style="opacity: 0.9; max-width: 600px;">{{ $seller->shop_description }}</p>
                @endif
                <div style="display: flex; gap: 2rem; margin-top: 1rem; flex-wrap: wrap;">
                    <span>📦 {{ $seller->total_products }} sản phẩm</span>
                    <span>🛒 {{ $seller->total_sales }} lượt bán</span>
                    @if($seller->rating_average > 0)
                        <span>⭐ {{ number_format($seller->rating_average, 1) }} rating</span>
                    @endif
                    <span>📅 Tham gia {{ $seller->created_at->format('m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products -->
<section style="padding: 3rem 0; background: #f8f9fa;">
    <div class="container">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">Sản phẩm của {{ $seller->shop_name }}</h2>

        @if($products->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($products as $product)
                    <a href="{{ route('lamgame.source-game.detail', $product->url_key ?? $product->id) }}"
                       style="background: white; border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s;"
                       onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/placeholder-game.svg') }}"
                             alt="{{ $product->name }}" style="width: 100%; height: 180px; object-fit: cover;">
                        <div style="padding: 1rem;">
                            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">{{ $product->name }}</h3>
                            <p style="color: #2c5f41; font-weight: 700; font-size: 1.1rem;">
                                {{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Miễn phí' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 2rem;">{{ $products->links() }}</div>
        @else
            <div style="text-align: center; padding: 3rem; color: #666;">
                <p style="font-size: 1.2rem;">Seller chưa có sản phẩm nào.</p>
            </div>
        @endif
    </div>
</section>
@endsection
