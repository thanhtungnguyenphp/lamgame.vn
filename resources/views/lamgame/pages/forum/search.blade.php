@extends('layouts.master')

@section('page_title', 'Tìm kiếm: ' . $query . ' - Forum LamGame')
@section('page_description', 'Kết quả tìm kiếm cho "' . $query . '" trong Forum Cộng đồng Game Developer Việt Nam.')

@section('content')
<div class="fm-page">

{{-- HERO --}}
<section class="fm-hero fm-hero--compact">
    <div class="fm-hero__bg"></div>
    <div class="fm-container fm-hero__inner">
        <h1 class="fm-hero__title">Kết quả tìm kiếm</h1>
        <p class="fm-hero__sub">Tìm thấy <strong>{{ $posts->total() ?? $posts->count() }}</strong> kết quả cho "<span class="fm-glow">{{ $query }}</span>"</p>
    </div>
</section>

{{-- TOOLBAR: Search + Filters --}}
<div class="fm-toolbar" id="feed">
    <div class="fm-container">
        <div class="fm-toolbar__row">
            <form action="{{ route('forum.search') }}" method="GET" class="fm-search">
                <svg class="fm-search__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="q" placeholder="Tìm kiếm bài viết..." value="{{ $query }}" class="fm-search__input">
                @if($query)
                <a href="{{ route('forum.index') }}" class="fm-search__clear">✕</a>
                @endif
            </form>
            <a href="{{ route('forum.index') }}" class="fm-btn fm-btn--ghost">← Về Forum</a>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="fm-container">
    <div class="fm-layout">
        {{-- FEED --}}
        <div class="fm-feed">
            @forelse($posts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
            @empty
            <div class="fm-empty">
                <div class="fm-empty__icon">🔍</div>
                <h3>Không tìm thấy kết quả</h3>
                <p>Không có bài viết nào phù hợp với "{{ $query }}"</p>
                <div style="display: flex; gap: 12px; justify-content: center; margin-top: 16px;">
                    <a href="{{ route('forum.index') }}" class="fm-btn fm-btn--ghost">Về Forum</a>
                    <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary">Tạo bài viết mới</a>
                </div>
            </div>
            @endforelse

            @if($posts->hasPages())
            <div class="fm-pagi">{{ $posts->appends(request()->query())->links('pagination.dark') }}</div>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <aside class="fm-sidebar">
            {{-- Search Tips --}}
            <div class="fm-sidebar__box">
                <h4>💡 Mẹo tìm kiếm</h4>
                <ul class="fm-sidebar__tips">
                    <li>Sử dụng từ khóa ngắn gọn</li>
                    <li>Thử các từ đồng nghĩa</li>
                    <li>Kiểm tra chính tả</li>
                </ul>
            </div>

            {{-- Popular Tags --}}
            <div class="fm-sidebar__box">
                <h4>🏷️ Tags phổ biến</h4>
                <div class="fm-sidebar__tags">
                    <a href="{{ route('forum.search', ['q' => 'unity']) }}" class="fm-tag">Unity</a>
                    <a href="{{ route('forum.search', ['q' => 'godot']) }}" class="fm-tag">Godot</a>
                    <a href="{{ route('forum.search', ['q' => 'mobile']) }}" class="fm-tag">Mobile</a>
                    <a href="{{ route('forum.search', ['q' => '2d']) }}" class="fm-tag">2D</a>
                    <a href="{{ route('forum.search', ['q' => '3d']) }}" class="fm-tag">3D</a>
                    <a href="{{ route('forum.search', ['q' => 'ai']) }}" class="fm-tag">AI</a>
                </div>
            </div>

            {{-- CTA --}}
            <div class="fm-sidebar__cta">
                <strong>Không tìm thấy?</strong>
                <p>Hãy tạo bài viết mới để cộng đồng giúp đỡ!</p>
                <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary fm-btn--block">Đăng bài mới</a>
            </div>
        </aside>
    </div>
</div>

</div>
@endsection

@push('styles')
<style>
.fm-hero--compact {
    padding: 2rem 0;
}
.fm-hero--compact .fm-hero__title {
    font-size: 1.75rem;
}
.fm-sidebar__tips {
    list-style: none;
    padding: 0;
    margin: 0;
}
.fm-sidebar__tips li {
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 0.875rem;
    color: var(--lg-text-muted);
}
.fm-sidebar__tips li:last-child {
    border-bottom: none;
}
.fm-sidebar__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.fm-tag {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--lg-bg-tertiary);
    border-radius: 4px;
    font-size: 0.75rem;
    color: var(--lg-text-muted);
    text-decoration: none;
    transition: all 0.2s;
}
.fm-tag:hover {
    background: var(--lg-accent);
    color: white;
}
</style>
@endpush
