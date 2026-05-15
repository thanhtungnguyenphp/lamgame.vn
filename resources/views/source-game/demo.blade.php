@extends('shop::layouts.master')

@section('page_title')
    Demo — {{ $product->name }}
@endsection

@section('content-wrapper')
<div class="demo-container" style="position:relative; width:100%; height:calc(100vh - 80px); background:#1a1a2e;">
    {{-- Watermark --}}
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(-30deg); font-size:3rem; color:rgba(255,255,255,0.08); pointer-events:none; z-index:10; white-space:nowrap;">
        DEMO — lamgame.vn
    </div>

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 20px; background:#16213e; color:#fff;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('shop.product_or_category.index', $product->url_key) }}" style="color:#e94560; text-decoration:none;">← Quay lại</a>
            <h3 style="margin:0; font-size:1.1rem;">{{ $product->name }} — Demo</h3>
        </div>
        <a href="{{ route('shop.product_or_category.index', $product->url_key) }}" class="btn btn-primary" style="background:#e94560; border:none; padding:8px 20px; border-radius:4px; color:#fff; text-decoration:none;">
            Mua ngay
        </a>
    </div>

    {{-- Iframe sandbox --}}
    <iframe
        src="{{ $demoUrl }}"
        sandbox="allow-scripts allow-same-origin"
        style="width:100%; height:calc(100% - 56px); border:none;"
        loading="lazy"
        allow="autoplay"
    ></iframe>
</div>
@endsection
