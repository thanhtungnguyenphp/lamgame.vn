{{-- Blog — Dark Gaming UI --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'Blog - LamGame.vn')
@section('page_description', $page_description ?? '')

@section('content')
<div class="bl-page">

{{-- HEADER --}}
<section class="bl-header">
    <div class="bl-container">
        <h1 class="bl-header__title">Blog & Tutorial</h1>
        <p class="bl-header__sub">Kiến thức game dev, tips & tricks, xu hướng công nghệ mới nhất</p>

        {{-- CATEGORY CHIPS --}}
        <div class="bl-chips">
            <a href="{{ route('lamgame.blog') }}" class="bl-chip {{ !$currentCategory && !$currentTag ? 'bl-chip--active' : '' }}">Tất cả</a>
            @foreach($categories as $cat)
            <a href="{{ route('lamgame.blog', ['category' => $cat->slug]) }}" class="bl-chip {{ $currentCategory == $cat->slug ? 'bl-chip--active' : '' }}">{{ $cat->name }} <span class="bl-chip__count">{{ $cat->blogs_count }}</span></a>
            @endforeach
        </div>

        {{-- SEARCH --}}
        <form action="{{ route('lamgame.blog') }}" method="GET" class="bl-search">
            <input type="text" name="search" placeholder="Tìm bài viết..." value="{{ $searchQuery ?? '' }}" class="bl-search__input">
            <button type="submit" class="bl-search__btn">🔍</button>
        </form>
    </div>
</section>

{{-- FEATURED --}}
@if($featuredBlog && !$searchQuery && !$currentCategory && !$currentTag)
<section class="bl-section">
    <div class="bl-container">
        <a href="{{ route('lamgame.blog') }}/{{ $featuredBlog->slug }}" class="bl-featured">
            <div class="bl-featured__img">
                <img src="{{ $featuredBlog->src ?? '' }}" alt="{{ $featuredBlog->name }}" loading="lazy">
            </div>
            <div class="bl-featured__body">
                <span class="bl-badge">{{ $featuredBlog->category->name ?? 'Featured' }}</span>
                <h2 class="bl-featured__title">{{ $featuredBlog->name }}</h2>
                <p class="bl-featured__desc">{{ Str::limit(strip_tags($featuredBlog->short_description), 150) }}</p>
                <div class="bl-featured__meta">
                    <span>{{ $featuredBlog->author ?? 'LamGame' }}</span>
                    <span>·</span>
                    <span>{{ $featuredBlog->published_at ? $featuredBlog->published_at->diffForHumans() : '' }}</span>
                    <span>·</span>
                    <span>{{ ceil(str_word_count(strip_tags($featuredBlog->description ?? '')) / 200) }} phút đọc</span>
                </div>
            </div>
        </a>
    </div>
</section>
@endif

{{-- BLOG GRID --}}
<section class="bl-section">
    <div class="bl-container">
        @if($searchQuery)
        <p class="bl-results">Kết quả cho "<strong>{{ $searchQuery }}</strong>" — {{ $blogs->total() }} bài viết</p>
        @endif

        <div class="bl-grid">
            @forelse($blogs as $blog)
            <a href="{{ route('lamgame.blog') }}/{{ $blog->slug }}" class="bl-card">
                <div class="bl-card__img">
                    <img src="{{ $blog->src ?? '' }}" alt="{{ $blog->name }}" loading="lazy">
                    <span class="bl-badge">{{ $blog->category->name ?? '' }}</span>
                </div>
                <div class="bl-card__body">
                    <h3 class="bl-card__title">{{ Str::limit($blog->name, 60) }}</h3>
                    <p class="bl-card__desc">{{ Str::limit(strip_tags($blog->short_description), 100) }}</p>
                    <div class="bl-card__meta">
                        <span>{{ $blog->author ?? 'LamGame' }}</span>
                        <span>{{ $blog->published_at ? $blog->published_at->diffForHumans() : '' }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="bl-empty">
                <p>Không tìm thấy bài viết nào.</p>
                <a href="{{ route('lamgame.blog') }}" class="bl-btn bl-btn--outline">Xem tất cả bài viết</a>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($blogs->hasPages())
        <div class="bl-pagination">
            {{ $blogs->appends(request()->query())->links('pagination::simple-default') }}
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
            @foreach($popularTags as $tag)
            <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" class="bl-tag {{ $currentTag == $tag->slug ? 'bl-tag--active' : '' }}">{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
.bl-page{background:#070B14;color:#F5F7FA;font-family:'Inter',sans-serif;min-height:100vh}
.bl-container{max-width:1100px;margin:0 auto;padding:0 24px}
.bl-section{padding:48px 0}
.bl-section--alt{background:#0B1020}
.bl-section__title{font-family:'Space Grotesk',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:20px}

/* HEADER */
.bl-header{padding:80px 24px 40px;text-align:center;background:radial-gradient(ellipse at 50% 80%,rgba(124,92,255,.08) 0%,transparent 60%)}
.bl-header__title{font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:700;margin-bottom:8px}
.bl-header__sub{color:#7A8599;margin-bottom:28px;font-size:1.05rem}

/* CHIPS */
.bl-chips{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:24px}
.bl-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:.82rem;font-weight:500;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .2s}
.bl-chip:hover{border-color:#7C5CFF;color:#F5F7FA}
.bl-chip--active{background:rgba(124,92,255,.15);border-color:#7C5CFF;color:#F5F7FA}
.bl-chip__count{font-size:.7rem;opacity:.6}

/* SEARCH */
.bl-search{display:flex;max-width:400px;margin:0 auto;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:8px;overflow:hidden}
.bl-search__input{flex:1;padding:10px 16px;background:transparent;border:none;color:#F5F7FA;font-size:.9rem;outline:none}
.bl-search__input::placeholder{color:#7A8599}
.bl-search__btn{padding:10px 16px;background:transparent;border:none;color:#7C5CFF;cursor:pointer;font-size:1rem}

/* FEATURED */
.bl-featured{display:grid;grid-template-columns:1.2fr 1fr;gap:32px;align-items:center;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:16px;overflow:hidden;text-decoration:none!important;transition:all .3s}
.bl-featured:hover{border-color:#7C5CFF;box-shadow:0 8px 30px rgba(124,92,255,.1)}
.bl-featured__img{aspect-ratio:16/10;overflow:hidden}
.bl-featured__img img{width:100%;height:100%;object-fit:cover}
.bl-featured__body{padding:24px 32px 24px 0}
.bl-featured__title{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:700;color:#F5F7FA;margin:12px 0}
.bl-featured__desc{color:#7A8599;font-size:.92rem;line-height:1.6;margin-bottom:16px}
.bl-featured__meta{display:flex;gap:8px;font-size:.8rem;color:#7A8599}

/* BADGE */
.bl-badge{display:inline-block;background:rgba(124,92,255,.15);border:1px solid rgba(124,92,255,.3);color:#B7C0D1;padding:3px 10px;border-radius:5px;font-size:.72rem;font-weight:600}

/* GRID */
.bl-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}

/* CARD */
.bl-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:14px;overflow:hidden;text-decoration:none!important;transition:all .3s}
.bl-card:hover{border-color:#7C5CFF;box-shadow:0 8px 25px rgba(124,92,255,.1);transform:translateY(-3px)}
.bl-card__img{position:relative;aspect-ratio:16/10;overflow:hidden;background:#111827}
.bl-card__img img{width:100%;height:100%;object-fit:cover}
.bl-card__img .bl-badge{position:absolute;top:10px;left:10px}
.bl-card__body{padding:16px}
.bl-card__title{font-size:.95rem;font-weight:600;color:#F5F7FA;margin-bottom:8px;line-height:1.4}
.bl-card__desc{font-size:.82rem;color:#7A8599;line-height:1.5;margin-bottom:12px}
.bl-card__meta{display:flex;justify-content:space-between;font-size:.75rem;color:#7A8599}

/* TAGS */
.bl-tags{display:flex;flex-wrap:wrap;gap:8px}
.bl-tag{padding:6px 12px;border-radius:6px;font-size:.8rem;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .2s}
.bl-tag:hover{border-color:#00D1FF;color:#00D1FF}
.bl-tag--active{background:rgba(0,209,255,.1);border-color:#00D1FF;color:#00D1FF}

/* PAGINATION */
.bl-pagination{display:flex;justify-content:center;margin-top:40px;gap:8px}
.bl-pagination a,.bl-pagination span{padding:8px 14px;border-radius:6px;font-size:.85rem;text-decoration:none!important;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);color:#B7C0D1;transition:all .2s}
.bl-pagination a:hover{border-color:#7C5CFF;color:#F5F7FA}
.bl-pagination .active span{background:rgba(124,92,255,.2);border-color:#7C5CFF;color:#F5F7FA}

/* EMPTY */
.bl-empty{grid-column:1/-1;text-align:center;padding:60px 20px;color:#7A8599}
.bl-btn{display:inline-flex;padding:10px 20px;border-radius:8px;font-weight:600;font-size:.9rem;text-decoration:none!important;transition:all .3s}
.bl-btn--outline{color:#7C5CFF!important;border:1.5px solid #7C5CFF}

/* RESULTS */
.bl-results{color:#7A8599;margin-bottom:24px;font-size:.9rem}
.bl-results strong{color:#F5F7FA}

/* RESPONSIVE */
@media(max-width:768px){
    .bl-featured{grid-template-columns:1fr}
    .bl-featured__body{padding:20px}
    .bl-grid{grid-template-columns:1fr}
    .bl-header{padding:60px 20px 30px}
    .bl-header__title{font-size:1.8rem}
    .bl-chips{justify-content:flex-start;overflow-x:auto;flex-wrap:nowrap;padding-bottom:8px}
}
</style>
@endpush
