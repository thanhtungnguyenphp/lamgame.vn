{{-- Blog — Dark Gaming UI - Optimized UX/UI --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'Blog - LamGame.vn')
@section('page_description', $page_description ?? '')

{{-- SEO: Tag/category pages canonical to /blog (Google already chose /blog as canonical) --}}
@section('canonical_url'){{ route('lamgame.blog') }}@endsection

{{-- SEO: Noindex ALL tag/category listing pages (only individual posts should be indexed) --}}
@push('meta')
@if(($currentTag ?? null) || ($currentCategory ?? null))
<meta name="robots" content="noindex, follow">
@endif
@endpush

{{-- SEO: CollectionPage schema for /blog --}}
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "{{ $page_title ?? 'Blog - LamGame.vn' }}",
    "description": "{{ $page_description ?? 'Kiến thức Game Dev, tips lập trình game, và xu hướng công nghệ mới nhất.' }}",
    "url": "{{ route('lamgame.blog') }}",
    "isPartOf": {"@id": "https://lamgame.vn/#website"}
    @if(isset($blogs) && $blogs->count() > 0)
    ,"mainEntity": {
        "@type": "ItemList",
        "numberOfItems": {{ $blogs->total() ?? $blogs->count() }},
        "itemListElement": [
            @foreach($blogs->take(10) as $i => $blog)
            {
                "@type": "ListItem",
                "position": {{ $i + 1 }},
                "url": "{{ route('blog.show', $blog->slug) }}",
                "name": "{{ addslashes($blog->name) }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    @endif
}
</script>
@endpush

@section('content')
<div class="bl-page">

{{-- HERO --}}
<section class="bl-hero">
    <div class="bl-hero__bg"></div>
    <div class="bl-container bl-hero__inner">
        <span class="bl-hero__badge">📖 Blog & Tutorial</span>
        <h1 class="bl-hero__title">Kiến thức <span class="bl-gradient-text">Game Dev</span> mới nhất</h1>
        <p class="bl-hero__sub">Tips, tricks, xu hướng công nghệ và hướng dẫn từ cộng đồng developer Việt Nam</p>

        {{-- SEARCH PREMIUM --}}
        <form action="{{ route('lamgame.blog') }}" method="GET" class="bl-search">
            <svg class="bl-search__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" placeholder="Tìm bài viết, tutorial, tips..." value="{{ $searchQuery ?? '' }}" class="bl-search__input">
            <button type="submit" class="bl-search__btn">Tìm kiếm</button>
        </form>
    </div>
</section>

{{-- FEATURED --}}
@if($featuredBlog && !$searchQuery && !$currentCategory && !$currentTag)
<section class="bl-section">
    <div class="bl-container">
        <a href="{{ route('lamgame.blog') }}/{{ $featuredBlog->slug }}" class="bl-featured">
            <div class="bl-featured__img">
                <img src="{{ $featuredBlog->featured_image }}" alt="{{ $featuredBlog->name }}" loading="lazy">
                <div class="bl-featured__overlay"></div>
            </div>
            <div class="bl-featured__body">
                <span class="bl-badge bl-badge--glow">{{ $featuredBlog->category->name ?? 'Featured' }}</span>
                <h2 class="bl-featured__title">{{ $featuredBlog->name }}</h2>
                <p class="bl-featured__desc">{{ Str::limit(strip_tags($featuredBlog->short_description), 150) }}</p>
                <div class="bl-featured__meta">
                    <span>{{ $featuredBlog->author ?? 'LamGame' }}</span>
                    <span>·</span>
                    <span>{{ $featuredBlog->published_at ? $featuredBlog->published_at->diffForHumans() : '' }}</span>
                    <span>·</span>
                    <span>{{ ceil(str_word_count(strip_tags($featuredBlog->description ?? '')) / 200) }} phút đọc</span>
                </div>
                <span class="bl-cta bl-cta--inline">Đọc ngay →</span>
            </div>
        </a>
    </div>
</section>
@endif

{{-- BLOG GRID --}}
<section class="bl-section">
    <div class="bl-container">
        {{-- CATEGORY FILTER — max 10 chips to avoid clutter --}}
        <div class="bl-chips">
            <a href="{{ route('lamgame.blog') }}" class="bl-chip {{ !$currentCategory && !$currentTag ? 'bl-chip--active' : '' }}">Tất cả</a>
            @foreach($categories->take(10) as $cat)
            <a href="{{ route('lamgame.blog', ['category' => $cat->slug]) }}" class="bl-chip {{ $currentCategory == $cat->slug ? 'bl-chip--active' : '' }}">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if($searchQuery)
        <p class="bl-results">Kết quả cho "<strong>{{ $searchQuery }}</strong>" — {{ $blogs->total() }} bài viết</p>
        @endif

        <div class="bl-grid">
            @forelse($blogs as $blog)
            <a href="{{ route('lamgame.blog') }}/{{ $blog->slug }}" class="bl-card">
                <div class="bl-card__img">
                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->name }}" loading="lazy">
                    <span class="bl-badge">{{ $blog->category->name ?? '' }}</span>
                </div>
                <div class="bl-card__body">
                    <h3 class="bl-card__title">{{ Str::limit($blog->name, 60) }}</h3>
                    <p class="bl-card__desc">{{ Str::limit(strip_tags($blog->short_description), 100) }}</p>
                    <div class="bl-card__footer">
                        <div class="bl-card__meta">
                            <span>{{ $blog->author ?? 'LamGame' }}</span>
                            <span>{{ $blog->published_at ? $blog->published_at->diffForHumans() : '' }}</span>
                        </div>
                        <span class="bl-card__cta">Đọc →</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="bl-empty">
                <p>Không tìm thấy bài viết nào.</p>
                <a href="{{ route('lamgame.blog') }}" class="bl-cta bl-cta--outline">Xem tất cả bài viết</a>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($blogs->hasPages())
        <div class="bl-pagination">
            {{ $blogs->appends(request()->query())->links('pagination.dark') }}
        </div>
        @endif
    </div>
</section>

{{-- TAGS --}}
@if($popularTags && $popularTags->count() > 0)
<section class="bl-section bl-section--alt">
    <div class="bl-container">
        <h2 class="bl-section__title">Tags phổ biến</h2>
        <div class="bl-tags">
            @foreach($popularTags->take(20) as $tag)
            <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" class="bl-tag {{ $currentTag == $tag->slug ? 'bl-tag--active' : '' }}">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA SECTION --}}
<section class="bl-section bl-section--cta">
    <div class="bl-container" style="text-align:center">
        <h2 class="bl-section__title">Bạn muốn chia sẻ kiến thức?</h2>
        <p style="color:#7A8599;margin-bottom:24px">Tham gia cộng đồng LamGame và đóng góp bài viết của bạn</p>
        <a href="/community" class="bl-cta bl-cta--primary">Tham gia cộng đồng</a>
    </div>
</section>

</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
/* === BASE === */
.bl-page{background:#070B14;color:#F5F7FA;font-family:'Inter',sans-serif;min-height:100vh}
.bl-container{max-width:1100px;margin:0 auto;padding:0 24px}
.bl-section{padding:48px 0}
.bl-section--alt{background:#0B1020}
.bl-section--cta{padding:64px 0;background:radial-gradient(ellipse at 50% 50%,rgba(124,92,255,.06) 0%,transparent 70%)}
.bl-section__title{font-family:'Space Grotesk',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:20px}
.bl-gradient-text{background:linear-gradient(135deg,#7C5CFF,#00D1FF);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

/* === HERO with DEPTH === */
.bl-hero{position:relative;padding:100px 24px 60px;text-align:center;overflow:hidden}
.bl-hero__bg{position:absolute;inset:0;background:radial-gradient(ellipse at 50% 0%,rgba(124,92,255,.12) 0%,transparent 50%),radial-gradient(ellipse at 80% 80%,rgba(0,209,255,.06) 0%,transparent 40%);pointer-events:none}
.bl-hero__bg::before{content:'';position:absolute;top:20%;left:10%;width:300px;height:300px;background:rgba(124,92,255,.04);border-radius:50%;filter:blur(80px)}
.bl-hero__bg::after{content:'';position:absolute;bottom:10%;right:15%;width:200px;height:200px;background:rgba(0,209,255,.04);border-radius:50%;filter:blur(60px)}
.bl-hero__inner{position:relative;z-index:1}
.bl-hero__badge{display:inline-block;padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.2);color:#B7C0D1;margin-bottom:16px}
.bl-hero__title{font-family:'Space Grotesk',sans-serif;font-size:2.8rem;font-weight:700;margin-bottom:12px;line-height:1.2}
.bl-hero__sub{color:#7A8599;margin-bottom:32px;font-size:1.05rem;max-width:500px;margin-left:auto;margin-right:auto}

/* === SEARCH PREMIUM === */
.bl-search{display:flex;align-items:center;max-width:480px;margin:0 auto;background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.15);border-radius:12px;overflow:hidden;transition:border-color .3s,box-shadow .3s}
.bl-search:focus-within{border-color:#7C5CFF;box-shadow:0 0 20px rgba(124,92,255,.15)}
.bl-search__icon{margin-left:16px;color:#7A8599;flex-shrink:0}
.bl-search__input{flex:1;padding:14px 12px;background:transparent;border:none;color:#F5F7FA;font-size:.9rem;outline:none}
.bl-search__input::placeholder{color:#7A8599}
.bl-search__btn{padding:10px 20px;margin:4px;background:linear-gradient(135deg,#7C5CFF,#6B4FE0);border:none;color:#fff;font-size:.82rem;font-weight:600;border-radius:8px;cursor:pointer;transition:transform .2s,box-shadow .2s}
.bl-search__btn:hover{transform:scale(1.02);box-shadow:0 4px 12px rgba(124,92,255,.3)}

/* === CTA BUTTONS === */
.bl-cta{display:inline-flex;align-items:center;gap:6px;padding:12px 24px;border-radius:10px;font-weight:600;font-size:.9rem;text-decoration:none!important;transition:all .3s;cursor:pointer}
.bl-cta--primary{background:linear-gradient(135deg,#7C5CFF,#6B4FE0);color:#fff;box-shadow:0 4px 16px rgba(124,92,255,.3)}
.bl-cta--primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(124,92,255,.4)}
.bl-cta--outline{color:#7C5CFF!important;border:1.5px solid #7C5CFF}
.bl-cta--outline:hover{background:rgba(124,92,255,.1)}
.bl-cta--inline{color:#7C5CFF;font-size:.85rem;padding:0;margin-top:12px}

/* === CHIPS === */
.bl-chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px}
.bl-chip{display:inline-flex;align-items:center;padding:7px 16px;border-radius:20px;font-size:.82rem;font-weight:500;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .25s}
.bl-chip:hover{border-color:#7C5CFF;color:#F5F7FA;background:rgba(124,92,255,.08)}
.bl-chip--active{background:rgba(124,92,255,.15);border-color:#7C5CFF;color:#F5F7FA}

/* === FEATURED === */
.bl-featured{display:grid;grid-template-columns:1.2fr 1fr;gap:0;align-items:stretch;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:16px;overflow:hidden;text-decoration:none!important;transition:all .4s}
.bl-featured:hover{border-color:#7C5CFF;box-shadow:0 12px 40px rgba(124,92,255,.12);transform:translateY(-4px)}
.bl-featured__img{position:relative;aspect-ratio:16/10;overflow:hidden}
.bl-featured__img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.bl-featured:hover .bl-featured__img img{transform:scale(1.03)}
.bl-featured__overlay{position:absolute;inset:0;background:linear-gradient(90deg,transparent 60%,rgba(7,11,20,.6))}
.bl-featured__body{padding:32px;display:flex;flex-direction:column;justify-content:center}
.bl-featured__title{font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;color:#F5F7FA;margin:12px 0;line-height:1.3}
.bl-featured__desc{color:#7A8599;font-size:.9rem;line-height:1.6;margin-bottom:12px}
.bl-featured__meta{display:flex;gap:8px;font-size:.78rem;color:#7A8599}

/* === BADGE === */
.bl-badge{display:inline-block;background:rgba(124,92,255,.15);border:1px solid rgba(124,92,255,.3);color:#B7C0D1;padding:4px 10px;border-radius:6px;font-size:.72rem;font-weight:600;letter-spacing:.02em}
.bl-badge--glow{box-shadow:0 0 8px rgba(124,92,255,.2)}

/* === GRID === */
.bl-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}

/* === CARD with STRONG HOVER === */
.bl-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:14px;overflow:hidden;text-decoration:none!important;transition:all .35s cubic-bezier(.4,0,.2,1)}
.bl-card:hover{border-color:#7C5CFF;box-shadow:0 12px 32px rgba(124,92,255,.15),0 0 0 1px rgba(124,92,255,.1);transform:translateY(-6px)}
.bl-card__img{position:relative;aspect-ratio:16/10;overflow:hidden;background:#111827}
.bl-card__img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.bl-card:hover .bl-card__img img{transform:scale(1.05)}
.bl-card__img .bl-badge{position:absolute;top:10px;left:10px}
.bl-card__body{padding:16px 18px}
.bl-card__title{font-size:.95rem;font-weight:600;color:#F5F7FA;margin-bottom:8px;line-height:1.4;transition:color .2s}
.bl-card:hover .bl-card__title{color:#B7A4FF}
.bl-card__desc{font-size:.82rem;color:#7A8599;line-height:1.5;margin-bottom:12px}
.bl-card__footer{display:flex;justify-content:space-between;align-items:center}
.bl-card__meta{display:flex;gap:8px;font-size:.75rem;color:#7A8599}
.bl-card__cta{font-size:.78rem;font-weight:600;color:#7C5CFF;opacity:0;transform:translateX(-4px);transition:all .3s}
.bl-card:hover .bl-card__cta{opacity:1;transform:translateX(0)}

/* === TAGS === */
.bl-tags{display:flex;flex-wrap:wrap;gap:8px}
.bl-tag{padding:7px 14px;border-radius:6px;font-size:.8rem;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .25s}
.bl-tag:hover{border-color:#00D1FF;color:#00D1FF;box-shadow:0 0 10px rgba(0,209,255,.1)}
.bl-tag--active{background:rgba(0,209,255,.1);border-color:#00D1FF;color:#00D1FF}

/* === PAGINATION === */
.bl-pagination{display:flex;justify-content:center;margin-top:40px;gap:8px}
.bl-pagination a,.bl-pagination span{padding:8px 14px;border-radius:8px;font-size:.85rem;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .25s}
.bl-pagination a:hover{border-color:#7C5CFF;color:#F5F7FA;background:rgba(124,92,255,.08)}
.bl-pagination .active span{background:rgba(124,92,255,.2);border-color:#7C5CFF;color:#F5F7FA}

/* === EMPTY === */
.bl-empty{grid-column:1/-1;text-align:center;padding:60px 20px;color:#7A8599}

/* === RESULTS === */
.bl-results{color:#7A8599;margin-bottom:24px;font-size:.9rem}
.bl-results strong{color:#F5F7FA}

/* === RESPONSIVE === */
@media(max-width:768px){
    .bl-hero{padding:70px 20px 40px}
    .bl-hero__title{font-size:2rem}
    .bl-search{flex-direction:column;border-radius:12px}
    .bl-search__icon{display:none}
    .bl-search__input{padding:14px 16px}
    .bl-search__btn{margin:0 4px 4px;border-radius:8px}
    .bl-featured{grid-template-columns:1fr}
    .bl-featured__body{padding:20px}
    .bl-featured__title{font-size:1.2rem}
    .bl-grid{grid-template-columns:1fr}
    .bl-chips{flex-wrap:nowrap;overflow-x:auto;padding-bottom:8px;-webkit-overflow-scrolling:touch}
    .bl-chip{white-space:nowrap}
}
@media(min-width:769px) and (max-width:1024px){
    .bl-grid{grid-template-columns:repeat(2,1fr)}
}
</style>
@endpush
