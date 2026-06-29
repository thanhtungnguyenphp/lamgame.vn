@extends('layouts.master')

@section('page_title', 'Forum - Cộng đồng Game Developer')
@section('page_description', 'Cộng đồng Game Developer lớn nhất Việt Nam. Chia sẻ project, source game, AI workflow và kết nối với indie developers.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "DiscussionForum",
    "name": "Forum Cộng đồng Game Developer",
    "description": "Cộng đồng Game Developer lớn nhất Việt Nam",
    "url": "{{ route('forum.index') }}",
    "isPartOf": {"@type": "WebSite","name": "Làm Game","url": "{{ url('/') }}"}
}
</script>
@endpush

@section('content')
<div class="fm-page">

{{-- HERO --}}
<section class="fm-hero">
    <div class="fm-hero__bg"></div>
    <div class="fm-container fm-hero__inner">
        <h1 class="fm-hero__title">Cộng đồng Game Developer<br><span class="fm-glow">lớn nhất Việt Nam</span></h1>
        <p class="fm-hero__sub">Chia sẻ project, source game, AI workflow và kết nối với indie developers.</p>
        <div class="fm-hero__cta">
            <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary">Đăng project</a>
            <a href="#feed" class="fm-btn fm-btn--ghost">Tham gia thảo luận ↓</a>
        </div>
        <div class="fm-hero__trust">
            <div><strong>12.000+</strong><span>Developers</span></div>
            <div><strong>3.500+</strong><span>Topics</span></div>
            <div><strong>500+</strong><span>Projects</span></div>
        </div>
    </div>
</section>

{{-- TOOLBAR: Search + Sort + Filters --}}
<div class="fm-toolbar" id="feed">
    <div class="fm-container">
        <div class="fm-toolbar__row">
            <form action="{{ route('forum.search') }}" method="GET" class="fm-search">
                <svg class="fm-search__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="q" placeholder="Tìm kiếm bài viết..." value="{{ $search }}" class="fm-search__input">
                @if($search)
                <a href="{{ route('forum.index') }}" class="fm-search__clear">✕</a>
                @endif
            </form>
            <div class="fm-sort">
                @php $sorts = ['latest' => 'Mới nhất', 'popular' => 'Phổ biến', 'activity' => 'Hoạt động']; @endphp
                @foreach($sorts as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}" class="fm-sort__tab {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
        {{-- Category chips --}}
        @if($categories->count() > 0)
        <div class="fm-chips">
            <a href="{{ route('forum.index') }}" class="fm-chip {{ !$category ? 'active' : '' }}">Tất cả</a>
            @foreach($categories->take(10) as $cat)
            <a href="{{ route('forum.category', $cat->slug) }}" class="fm-chip {{ $category === $cat->slug ? 'active' : '' }}">
                {{ $cat->icon ?? '' }} {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="fm-container">
    <div class="fm-layout">
        {{-- FEED --}}
        <div class="fm-feed">
            {{-- Sticky --}}
            @if($stickyPosts->count() > 0)
            <div class="fm-label">📌 Ghim</div>
            @foreach($stickyPosts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post, 'isSticky' => true])
            @endforeach
            <div class="fm-label" style="margin-top:16px">💬 Thảo luận</div>
            @endif

            {{-- Posts --}}
            @forelse($posts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
            @empty
            <div class="fm-empty">
                <div class="fm-empty__icon">📝</div>
                <h3>Chưa có bài viết nào</h3>
                <p>Hãy là người đầu tiên chia sẻ trong cộng đồng!</p>
                <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary">Tạo bài viết đầu tiên</a>
            </div>
            @endforelse

            @if($posts->hasPages())
            <div class="fm-pagi">{{ $posts->appends(request()->query())->links('pagination.dark') }}</div>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <aside class="fm-sidebar">
            {{-- Popular creators --}}
            @if(!empty($popularCreators ?? null) && count($popularCreators) > 0)
            <div class="fm-widget">
                <h3 class="fm-widget__title">🏆 Top Creators</h3>
                @foreach(($popularCreators ?? collect())->take(5) as $creator)
                <div class="fm-creator">
                    <div class="fm-avatar">{{ strtoupper(substr($creator->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <span class="fm-creator__name">{{ $creator->name }}</span>
                        <span class="fm-creator__stat">{{ $creator->posts_count ?? 0 }} bài</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Popular tags --}}
            @if($popularTags->count() > 0)
            <div class="fm-widget">
                <h3 class="fm-widget__title">🏷️ Tags phổ biến</h3>
                <div class="fm-widget__tags">
                    @foreach($popularTags->take(15) as $tag)
                    <a href="{{ route('forum.tag', $tag->slug) }}" class="fm-wtag">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- CTA --}}
            <div class="fm-widget fm-widget--cta">
                <h3 class="fm-widget__title">🚀 Bạn có project?</h3>
                <p>Showcase project của bạn cho 12.000+ developers</p>
                <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary fm-btn--sm">Đăng project →</a>
            </div>
        </aside>
    </div>
</div>

{{-- MOBILE STICKY CTA --}}
<div class="fm-mobile-cta">
    <a href="{{ route('forum.posts.create') }}" class="fm-btn fm-btn--primary">+ Đăng bài</a>
</div>

</div>

@push('styles')
<style>.fm-page{background:#070B14;min-height:100vh}</style>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"></noscript>
<link rel="stylesheet" href="{{ asset('css/forum.css') }}">
@endpush

@auth('customer')
@push('scripts')
<script>
(function(){
    const badge = document.getElementById('notifBadge');
    if (!badge) return;
    function check() {
        fetch('{{ route("forum.notifications.count") }}', {headers:{'Accept':'application/json'}})
            .then(r => r.json())
            .then(d => {
                if (d.count > 0) { badge.textContent = d.count > 99 ? '99+' : d.count; badge.style.display = ''; }
                else { badge.style.display = 'none'; }
            }).catch(() => {});
    }
    check();
    setInterval(check, 30000);
})();
</script>
@endpush
@endauth
@endsection
