@extends('layouts.master')

@section('page_title', $game->title . ' - Chơi Miễn Phí Online | LamGame')
@section('page_description', $game->description ?: $game->title . ' - Chơi miễn phí trên trình duyệt')
@section('canonical_url', $game->url)
@section('og_type', 'game')

@push('og_extra')
<meta property="og:type" content="game">
<meta name="keywords" content="{{ $game->keywords }}">
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "VideoGame",
    "name": "{{ $game->title }}",
    "description": "{{ $game->description }}",
    "url": "{{ $game->url }}",
    "genre": "{{ $game->category_label }}",
    "playMode": "SinglePlayer",
    "applicationCategory": "Game",
    "operatingSystem": "Web Browser",
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "VND",
        "availability": "https://schema.org/InStock"
    },
    "publisher": {
        "@type": "Organization",
        "name": "LamGame.vn",
        "url": "https://lamgame.vn"
    }
}
</script>
@endpush

@push('breadcrumb_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Trang chủ", "item": "{{ url('/') }}"},
        {"@type": "ListItem", "position": 2, "name": "Chơi Game", "item": "{{ url('/choi-game') }}"},
        {"@type": "ListItem", "position": 3, "name": "{{ $game->title }}", "item": "{{ $game->url }}"}
    ]
}
</script>
@endpush

@section('content')
<div class="mg-detail">
    {{-- Breadcrumb --}}
    <nav class="mg-breadcrumb">
        <div class="container">
            <a href="{{ url('/') }}">Trang chủ</a> ›
            <a href="{{ route('mini-game.index') }}">Chơi Game</a> ›
            <span>{{ $game->title }}</span>
        </div>
    </nav>

    {{-- Game Area --}}
    <section class="mg-play">
        <div class="container">
            <h1 class="mg-play__title">{{ $game->title }}</h1>
            <div class="mg-play__frame-wrap">
                <iframe
                    id="gameFrame"
                    src="/{{ $game->game_path }}/index.html"
                    class="mg-play__frame"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
            </div>
            <div class="mg-play__actions">
                <button onclick="toggleFullscreen()" class="mg-play__btn">⛶ Toàn màn hình</button>
                <span class="mg-play__stat">🎮 {{ number_format($game->play_count) }} lượt chơi</span>
                <span class="mg-play__cat">{{ $game->category_label }}</span>
            </div>
            @if($game->description)
            <p class="mg-play__desc">{{ $game->description }}</p>
            @endif
        </div>
    </section>

    {{-- Related Games --}}
    @if($related->isNotEmpty())
    <section class="mg-related">
        <div class="container">
            <h2 class="mg-related__title">Game tương tự</h2>
            <div class="mg-grid">
                @foreach($related as $r)
                <a href="{{ $r->url }}" class="mg-card">
                    <div class="mg-card__icon">🎮</div>
                    <h3 class="mg-card__title">{{ $r->title }}</h3>
                    <p class="mg-card__desc">{{ Str::limit($r->description, 60) }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

<style>
.mg-breadcrumb{padding:12px 0;font-size:.85rem;color:#888}
.mg-breadcrumb a{color:#FF6B35;text-decoration:none}
.mg-play{padding:10px 0 30px}
.mg-play__title{font-size:1.6rem;color:#1A1A2E;margin-bottom:16px}
.mg-play__frame-wrap{position:relative;width:100%;max-width:900px;aspect-ratio:16/10;background:#000;border-radius:12px;overflow:hidden;margin:0 auto}
.mg-play__frame{width:100%;height:100%;border:none}
.mg-play__actions{display:flex;align-items:center;gap:16px;margin-top:12px;flex-wrap:wrap;max-width:900px;margin-left:auto;margin-right:auto}
.mg-play__btn{padding:8px 16px;background:#FF6B35;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600;font-size:.9rem}
.mg-play__stat,.mg-play__cat{font-size:.85rem;color:#666}
.mg-play__desc{margin-top:12px;color:#555;line-height:1.6;max-width:900px;margin-left:auto;margin-right:auto}
.mg-related{padding:20px 0 40px}
.mg-related__title{font-size:1.3rem;color:#1A1A2E;margin-bottom:16px}
.mg-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px}
.mg-card{display:block;background:#fff;border:1px solid #eee;border-radius:12px;padding:16px;text-decoration:none;color:inherit;transition:transform .2s}
.mg-card:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.1)}
.mg-card__icon{font-size:1.8rem;margin-bottom:6px}
.mg-card__title{font-size:.95rem;font-weight:700;color:#1A1A2E;margin-bottom:4px}
.mg-card__desc{font-size:.8rem;color:#666;line-height:1.3}
</style>

<script>
function toggleFullscreen() {
    const frame = document.getElementById('gameFrame');
    if (frame.requestFullscreen) frame.requestFullscreen();
    else if (frame.webkitRequestFullscreen) frame.webkitRequestFullscreen();
}
</script>
@endsection
