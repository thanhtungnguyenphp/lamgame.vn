@extends('layouts.master')

@section('page_title', 'Demo — ' . $product->name . ' | LamGame')
@section('page_description', 'Chơi thử ' . $product->name . ' miễn phí trực tiếp trên trình duyệt.')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="demo-page">
    {{-- Header bar --}}
    <div class="demo-header">
        <div class="demo-header__left">
            <a href="{{ $backUrl }}" class="demo-header__back">← Quay lại</a>
            <h1 class="demo-header__title">{{ $product->name }} — Demo</h1>
        </div>
        <div class="demo-header__right">
            <button onclick="toggleFullscreen()" class="demo-header__btn">⛶ Fullscreen</button>
            @if(($product->price ?? 0) > 0)
            <a href="{{ $buyUrl }}" class="demo-header__buy">💰 Mua Source Code</a>
            @else
            <a href="{{ $buyUrl }}" class="demo-header__buy demo-header__buy--free">📥 Tải Source (Free)</a>
            @endif
        </div>
    </div>

    {{-- Game iframe --}}
    <div class="demo-frame" id="demoFrame">
        <div class="demo-watermark">DEMO — lamgame.vn</div>
        <iframe
            src="{{ $demoUrl }}"
            id="gameIframe"
            sandbox="allow-scripts allow-same-origin allow-popups"
            allow="autoplay; fullscreen"
            loading="lazy"
        ></iframe>
    </div>

    {{-- Bottom CTA --}}
    <div class="demo-cta">
        <p>Thích game này? Tải source code để học và customize.</p>
        <a href="{{ $buyUrl }}">Xem chi tiết Source Code →</a>
    </div>
</div>
@endsection

@push('styles')
<style>
.demo-page{background:#0a0e1a;min-height:100vh;display:flex;flex-direction:column}
.demo-header{display:flex;justify-content:space-between;align-items:center;padding:10px 20px;background:#16213e;border-bottom:1px solid rgba(124,92,255,.2)}
.demo-header__left{display:flex;align-items:center;gap:16px}
.demo-header__back{color:#7A8599;text-decoration:none;font-size:.88rem}
.demo-header__back:hover{color:#fff}
.demo-header__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin:0}
.demo-header__right{display:flex;gap:10px;align-items:center}
.demo-header__btn{padding:6px 14px;background:rgba(124,92,255,.15);border:1px solid rgba(124,92,255,.3);border-radius:6px;color:#A78BFA;cursor:pointer;font-size:.82rem}
.demo-header__btn:hover{background:rgba(124,92,255,.25);color:#fff}
.demo-header__buy{padding:8px 18px;background:#6C63FF;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;font-size:.85rem}
.demo-header__buy:hover{background:#5a52e0}
.demo-header__buy--free{background:#10B981}
.demo-header__buy--free:hover{background:#059669}
.demo-frame{flex:1;position:relative;min-height:calc(100vh - 120px)}
.demo-watermark{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-25deg);font-size:3rem;color:rgba(255,255,255,.04);pointer-events:none;z-index:10;white-space:nowrap;font-weight:700}
.demo-frame iframe{width:100%;height:100%;position:absolute;top:0;left:0;border:none}
.demo-cta{text-align:center;padding:16px;background:#16213e;border-top:1px solid rgba(124,92,255,.1)}
.demo-cta p{color:#7A8599;font-size:.85rem;margin-bottom:4px}
.demo-cta a{color:#7C5CFF;text-decoration:none;font-weight:500;font-size:.9rem}
.demo-cta a:hover{text-decoration:underline}
.demo-frame.fullscreen{position:fixed;top:0;left:0;right:0;bottom:0;z-index:9999;min-height:100vh}
@media(max-width:768px){.demo-header{flex-direction:column;gap:8px;text-align:center}.demo-header__title{font-size:.88rem}}
</style>
@endpush

@push('scripts')
<script>
function toggleFullscreen() {
    var frame = document.getElementById('demoFrame');
    if (frame.classList.contains('fullscreen')) {
        frame.classList.remove('fullscreen');
    } else {
        frame.classList.add('fullscreen');
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('demoFrame')?.classList.remove('fullscreen');
});
</script>
@endpush
