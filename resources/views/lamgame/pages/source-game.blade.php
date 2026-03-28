@extends('layouts.master')

@section('page_title', $page_title ?? 'Source Game - Kho Mã Nguồn Game - Làm Game')
@section('page_description', $page_description ?? 'Kho source code game, thuê lập trình viên game, chia sẻ ý tưởng game. Cộng đồng game developer Việt Nam.')

@push('pagination_links')
@php
    $currentPage = $pagination['current_page'] ?? 1;
    $hasMore = $pagination['has_more'] ?? false;
    $baseUrl = route('lamgame.source-game');
@endphp
@if($currentPage > 1)
    <link rel="prev" href="{{ $currentPage == 2 ? $baseUrl : $baseUrl . '?page=' . ($currentPage - 1) }}">
@endif
@if($hasMore)
    <link rel="next" href="{{ $baseUrl . '?page=' . ($currentPage + 1) }}">
@endif
@endpush

@section('content')
<div class="sg-page">
    {{-- Hero compact --}}
    <section class="sg-hero">
        <div class="container">
            <h1 class="sg-hero__title">Kho Source Game</h1>
            <p class="sg-hero__sub">Mua bán source code · Thuê game developer · Chia sẻ ý tưởng game</p>
        </div>
    </section>

    {{-- Search --}}
    <section class="sg-search">
        <div class="container">
            <form action="{{ route('lamgame.source-game') }}" method="GET" class="sg-search__form">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm source game, engine, từ khóa..." class="sg-search__input">
                <select name="sort" class="sg-search__select" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                </select>
                <button type="submit" class="sg-search__btn"><i class="fa fa-search"></i> Tìm</button>
            </form>
        </div>
    </section>

    {{-- Results --}}
    <section class="sg-results">
        <div class="container">
            @if(count($featuredSources) > 0)
                <div class="sg-grid">
                    @foreach($featuredSources as $source)
                    <a href="{{ $source['href'] ?? '#' }}" class="sg-card">
                        <div class="sg-card__thumb">
                            <img src="{{ $source['preview_image'] }}" alt="{{ $source['title'] }}" loading="lazy">
                            @if(($source['price'] ?? 0) <= 0)
                                <span class="sg-card__badge sg-card__badge--free">Miễn phí</span>
                            @endif
                        </div>
                        <div class="sg-card__body">
                            <h3 class="sg-card__title">{{ $source['title'] }}</h3>
                            <p class="sg-card__desc">{{ \Str::limit(strip_tags($source['description'] ?? ''), 80) }}</p>
                            <div class="sg-card__footer">
                                <span class="sg-card__engine">{{ $source['engine'] ?? 'Unity' }}</span>
                                <span class="sg-card__price">
                                    @if(($source['price'] ?? 0) <= 0)
                                        Miễn phí
                                    @else
                                        {{ number_format($source['price']) }}đ
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($pagination['has_more'] ?? false)
                <div class="sg-pager">
                    @if($pagination['current_page'] > 1)
                        <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}" class="sg-pager__btn">← Trước</a>
                    @endif
                    <span class="sg-pager__info">Trang {{ $pagination['current_page'] }}</span>
                    @if($pagination['has_more'])
                        <a href="{{ route('lamgame.source-game', array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}" class="sg-pager__btn">Tiếp →</a>
                    @endif
                </div>
                @endif
            @else
                <div class="sg-empty">
                    <h3>Chưa có source game nào</h3>
                    <p>Hãy quay lại sau hoặc <a href="{{ route('lamgame.lien-he') }}">liên hệ</a> để đóng góp source game của bạn.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Services --}}
    <section class="sg-services">
        <div class="container">
            <h2 class="sg-services__title">Dịch vụ Game Development</h2>
            <div class="sg-services__grid">
                <div class="sg-svc">
                    <div class="sg-svc__icon">💻</div>
                    <h3>Thuê Game Developer</h3>
                    <p>Freelancer Unity, Unreal, Godot — nhận việc trong 24h</p>
                    <a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Liên hệ →</a>
                </div>
                <div class="sg-svc">
                    <div class="sg-svc__icon">💡</div>
                    <h3>Chia sẻ ý tưởng Game</h3>
                    <p>Đăng ý tưởng game, tìm đội ngũ phát triển cùng bạn</p>
                    <a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Gửi ý tưởng →</a>
                </div>
                <div class="sg-svc">
                    <div class="sg-svc__icon">📦</div>
                    <h3>Đăng bán Source Game</h3>
                    <p>Bán source code game của bạn cho cộng đồng developer</p>
                    <a href="{{ route('lamgame.lien-he') }}" class="sg-svc__link">Đăng bán →</a>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
.sg-page { background: #f8fafc; }

/* Hero */
.sg-hero {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    color: #fff;
    padding: 3rem 0 2rem;
    text-align: center;
}
.sg-hero__title { font-size: 2rem; font-weight: 800; margin: 0; }
.sg-hero__sub { color: #94a3b8; font-size: 1rem; margin: 0.5rem 0 0; }

/* Search */
.sg-search { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1.25rem 0; }
.sg-search__form {
    display: flex;
    gap: 0.75rem;
    max-width: 800px;
    margin: 0 auto;
}
.sg-search__input {
    flex: 1;
    padding: 0.7rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    min-width: 0;
}
.sg-search__input:focus { outline: none; border-color: #667eea; }
.sg-search__select {
    padding: 0.7rem 0.75rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #fff;
    cursor: pointer;
}
.sg-search__btn {
    padding: 0.7rem 1.25rem;
    background: #1e293b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}
.sg-search__btn:hover { background: #334155; }

/* Results grid */
.sg-results { padding: 2rem 0 3rem; }
.sg-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

/* Card */
.sg-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s, box-shadow 0.2s;
}
.sg-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.sg-card__thumb {
    position: relative;
    aspect-ratio: 16/10;
    background: #1e293b;
    overflow: hidden;
}
.sg-card__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.sg-card:hover .sg-card__thumb img { transform: scale(1.05); }
.sg-card__badge {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.sg-card__badge--free { background: #10b981; color: #fff; }
.sg-card__body { padding: 1rem; }
.sg-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.35rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sg-card__desc {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0 0 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sg-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sg-card__engine {
    font-size: 0.75rem;
    color: #64748b;
    background: #f1f5f9;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}
.sg-card__price {
    font-size: 0.9rem;
    font-weight: 700;
    color: #10b981;
}

/* Pagination */
.sg-pager {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
}
.sg-pager__btn {
    padding: 0.5rem 1rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #1e293b;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
}
.sg-pager__btn:hover { background: #f1f5f9; }
.sg-pager__info { font-size: 0.85rem; color: #64748b; }

/* Empty */
.sg-empty {
    text-align: center;
    padding: 4rem 1rem;
    color: #64748b;
}
.sg-empty h3 { color: #1e293b; margin-bottom: 0.5rem; }
.sg-empty a { color: #667eea; }

/* Services */
.sg-services {
    padding: 3rem 0;
    background: #fff;
    border-top: 1px solid #e2e8f0;
}
.sg-services__title {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2rem;
}
.sg-services__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
.sg-svc {
    text-align: center;
    padding: 2rem 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    transition: border-color 0.2s;
}
.sg-svc:hover { border-color: #667eea; }
.sg-svc__icon { font-size: 2rem; margin-bottom: 0.75rem; }
.sg-svc h3 { font-size: 1.05rem; color: #1e293b; margin: 0 0 0.5rem; }
.sg-svc p { font-size: 0.85rem; color: #64748b; margin: 0 0 1rem; line-height: 1.5; }
.sg-svc__link {
    font-size: 0.85rem;
    font-weight: 600;
    color: #667eea;
    text-decoration: none;
}
.sg-svc__link:hover { text-decoration: underline; }

/* Responsive */
@media (max-width: 768px) {
    .sg-hero__title { font-size: 1.5rem; }
    .sg-search__form { flex-wrap: wrap; }
    .sg-search__input { width: 100%; }
    .sg-search__select { flex: 1; }
    .sg-search__btn { flex: 0; }
    .sg-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .sg-services__grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .sg-grid { grid-template-columns: 1fr; }
}
</style>
@endpush
@endsection
