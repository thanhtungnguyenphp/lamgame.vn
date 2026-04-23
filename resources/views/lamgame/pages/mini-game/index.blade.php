@extends('layouts.master')

@section('page_title', 'Chơi Game Miễn Phí Online - Kho ' . $games->total() . ' Mini Game HTML5 | LamGame')
@section('page_description', 'Kho ' . $games->total() . ' mini game HTML5 miễn phí - Chơi ngay trên trình duyệt, không cần cài đặt. Game arcade, puzzle, casual, card, action.')
@section('canonical_url', url('/choi-game'))

@push('og_extra')
<meta property="og:type" content="website">
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Kho Mini Game Miễn Phí",
    "description": "Kho {{ $games->total() }} mini game HTML5 miễn phí trên LamGame.vn",
    "url": "{{ url('/choi-game') }}",
    "publisher": {
        "@type": "Organization",
        "name": "LamGame.vn",
        "url": "https://lamgame.vn"
    }
}
</script>
@endpush

@section('content')
<div class="mg-page">
    {{-- Hero --}}
    <section class="mg-hero">
        <div class="container">
            <h1 class="mg-hero__title">🎮 Kho Mini Game Miễn Phí</h1>
            <p class="mg-hero__sub">{{ $games->total() }} game HTML5 · Chơi ngay trên trình duyệt · Không cần cài đặt</p>
        </div>
    </section>

    {{-- Filter --}}
    <section class="mg-filter">
        <div class="container">
            <form action="{{ route('mini-game.index') }}" method="GET" class="mg-filter__form">
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm game..." class="mg-filter__input">
                <select name="category" class="mg-filter__select" onchange="this.form.submit()">
                    <option value="">Tất cả thể loại</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ $current === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="mg-filter__btn">Tìm</button>
            </form>
        </div>
    </section>

    {{-- Game Grid --}}
    <section class="mg-grid-section">
        <div class="container">
            @if($games->isEmpty())
                <p style="text-align:center;padding:40px;color:#999;">Không tìm thấy game nào.</p>
            @else
                <div class="mg-grid">
                    @foreach($games as $game)
                    <a href="{{ $game->url }}" class="mg-card">
                        @if($game->thumbnail)
                            <img src="{{ asset($game->thumbnail) }}" alt="{{ $game->title }}" class="mg-card__thumb" loading="lazy">
                        @else
                            <div class="mg-card__icon">🎮</div>
                        @endif
                        <h3 class="mg-card__title">{{ $game->title }}</h3>
                        <p class="mg-card__desc">{{ Str::limit($game->description, 60) }}</p>
                        <span class="mg-card__cat">{{ $game->category_label }}</span>
                    </a>
                    @endforeach
                </div>

                <div class="mg-pagination">
                    {{ $games->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>
</div>

<style>
.mg-hero{text-align:center;padding:30px 20px;background:linear-gradient(135deg,#1A1A2E,#004E89)}
.mg-hero__title{color:#FFD700;font-size:2rem;margin-bottom:8px}
.mg-hero__sub{color:#E0E0E0;font-size:1rem;opacity:.85}
.mg-filter{padding:16px 0}
.mg-filter__form{display:flex;gap:10px;flex-wrap:wrap;justify-content:center}
.mg-filter__input{padding:10px 16px;border:1px solid #ddd;border-radius:8px;font-size:14px;min-width:200px}
.mg-filter__select{padding:10px 16px;border:1px solid #ddd;border-radius:8px;font-size:14px}
.mg-filter__btn{padding:10px 20px;background:#FF6B35;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600}
.mg-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;padding:10px 0}
.mg-card{display:block;background:#fff;border:1px solid #eee;border-radius:12px;overflow:hidden;text-decoration:none;color:inherit;transition:transform .2s,box-shadow .2s}
.mg-card:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.1)}
.mg-card__thumb{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}
.mg-card__icon{font-size:2rem;padding:20px 20px 8px}
.mg-card__title{font-size:1.05rem;font-weight:700;color:#1A1A2E;margin-bottom:6px;padding:12px 16px 0}
.mg-card__desc{font-size:.85rem;color:#666;line-height:1.4;margin-bottom:8px;padding:0 16px}
.mg-card__cat{display:inline-block;font-size:.75rem;background:#f0f0f0;color:#555;padding:3px 10px;border-radius:20px;margin:0 16px 16px}
.mg-pagination{text-align:center;padding:20px 0}
</style>
@endsection
