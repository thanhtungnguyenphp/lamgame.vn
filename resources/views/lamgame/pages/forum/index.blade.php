@extends('layouts.master')

@section('page_title', 'Forum - Cộng đồng Game Developer')
@section('page_description', 'Tham gia thảo luận về game development, chia sẻ ý tưởng và tìm kiếm đồng đội.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "DiscussionForum",
    "name": "Forum Cộng đồng Game Developer",
    "description": "Tham gia thảo luận về game development, chia sẻ ý tưởng và tìm kiếm đồng đội",
    "url": "{{ route('forum.index') }}",
    "isPartOf": {
        "@type": "WebSite",
        "name": "Làm Game",
        "url": "{{ url('/') }}"
    }
}
</script>
@endpush

@section('content')
<div class="fm-page">
    {{-- Header --}}
    <div class="fm-hdr">
        <div class="container">
            <div class="fm-hdr-row">
                <div>
                    <h1 class="fm-hdr-title">🎮 Cộng Đồng Game Developer</h1>
                    <p class="fm-hdr-desc">Chia sẻ, thảo luận và học hỏi cùng cộng đồng game dev Việt Nam</p>
                </div>
                <div class="fm-hdr-actions">
                    @auth('customer')
                    <a href="{{ route('forum.bookmarks') }}" class="fm-hdr-icon" title="Bài đã lưu"><i class="far fa-bookmark"></i></a>
                    <a href="{{ route('forum.notifications') }}" class="fm-hdr-icon fm-notif-bell" title="Thông báo" id="notifBell">
                        <i class="far fa-bell"></i>
                        <span class="fm-notif-badge" id="notifBadge" style="display:none;"></span>
                    </a>
                    @endauth
                    <a href="{{ route('forum.posts.create') }}" class="fm-btn-create">
                        <i class="fas fa-pen"></i> Đăng bài
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar: search + sort + categories --}}
    <div class="fm-toolbar">
        <div class="container">
            <form action="{{ route('forum.search') }}" method="GET" class="fm-search">
                <i class="fas fa-search fm-search-ico"></i>
                <input type="text" name="q" placeholder="Tìm kiếm bài viết..." value="{{ $search }}" class="fm-search-input">
                @if($search)
                <a href="{{ route('forum.index') }}" class="fm-search-clear"><i class="fas fa-times"></i></a>
                @endif
            </form>

            <div class="fm-filters">
                {{-- Sort tabs --}}
                <div class="fm-sort">
                    @php $sorts = ['latest' => 'Mới nhất', 'popular' => 'Phổ biến', 'activity' => 'Hoạt động']; @endphp
                    @foreach($sorts as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}" class="fm-sort-tab {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>

                {{-- Category chips --}}
                @if($categories->count() > 0)
                <div class="fm-cats">
                    <a href="{{ route('forum.index') }}" class="fm-cat-chip {{ !$category ? 'active' : '' }}">Tất cả</a>
                    @foreach($categories as $cat)
                    <a href="{{ route('forum.category', $cat->slug) }}" class="fm-cat-chip {{ $category === $cat->slug ? 'active' : '' }}">
                        {{ $cat->icon ?? '' }} {{ $cat->name }}
                        <span class="fm-cat-count">{{ $cat->posts_count }}</span>
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Tags --}}
                @if($popularTags->count() > 0)
                <div class="fm-tags">
                    @foreach($popularTags as $tag)
                    <a href="{{ route('forum.tag', $tag->slug) }}" class="fm-tag">{{ $tag->name }}</a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <div class="container">
        <div class="fm-content">
            {{-- Sticky --}}
            @if($stickyPosts->count() > 0)
            <div class="fm-sticky-label">📌 Ghim</div>
            @foreach($stickyPosts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post, 'isSticky' => true])
            @endforeach
            @endif

            {{-- Post list --}}
            @forelse($posts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
            @empty
            <div class="fm-empty">
                <div class="fm-empty-ico">📝</div>
                <h3>Chưa có bài viết nào</h3>
                <p>Hãy là người đầu tiên chia sẻ trong cộng đồng!</p>
                <a href="{{ route('forum.posts.create') }}" class="fm-btn-create">Tạo bài viết đầu tiên</a>
            </div>
            @endforelse

            @if($posts->hasPages())
            <div class="fm-pagi">{{ $posts->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.fm-page { min-height: 100vh; background: #f8fafc; }

/* Header */
.fm-hdr { background: #1a1a2e; color: #fff; padding: 1.5rem 0; }
.fm-hdr-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
.fm-hdr-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
.fm-hdr-desc { color: #a0aec0; font-size: 0.9rem; margin: 0.25rem 0 0; }
.fm-btn-create {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;
    background: linear-gradient(135deg, #6a4c93, #9b5de5); color: #fff;
    text-decoration: none; white-space: nowrap; border: none; cursor: pointer;
    transition: opacity .2s;
}
.fm-btn-create:hover { opacity: .85; }
.fm-hdr-actions { display: flex; align-items: center; gap: 0.75rem; }
.fm-hdr-icon { color: #a0aec0; font-size: 1.2rem; position: relative; transition: color .15s; }
.fm-hdr-icon:hover { color: #fff; }
.fm-notif-badge { position: absolute; top: -6px; right: -8px; background: #ef4444; color: #fff; font-size: 0.6rem; min-width: 16px; height: 16px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; }

/* Toolbar */
.fm-toolbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 0; position: sticky; top: 0; z-index: 100; }
.fm-search {
    position: relative; display: flex; align-items: center;
    border: 1.5px solid #e2e8f0; border-radius: 8px; background: #f8fafc;
    transition: border-color .2s;
}
.fm-search:focus-within { border-color: #6a4c93; }
.fm-search-ico { position: absolute; left: 0.75rem; color: #94a3b8; pointer-events: none; }
.fm-search-input { flex: 1; border: none; outline: none; background: transparent; padding: 0.5rem 0.75rem 0.5rem 2.25rem; font-size: 0.9rem; }
.fm-search-clear { color: #94a3b8; padding: 0 0.75rem; text-decoration: none; }
.fm-search-clear:hover { color: #6a4c93; }

.fm-filters { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; margin-top: 0.625rem; }

/* Sort tabs */
.fm-sort { display: flex; gap: 0.25rem; }
.fm-sort-tab {
    padding: 0.3rem 0.75rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600;
    color: #64748b; text-decoration: none; transition: all .15s;
}
.fm-sort-tab:hover { background: #f1f5f9; color: #1a202c; }
.fm-sort-tab.active { background: #1a1a2e; color: #fff; }

/* Category chips */
.fm-cats { display: flex; gap: 0.375rem; overflow-x: auto; scrollbar-width: none; }
.fm-cats::-webkit-scrollbar { display: none; }
.fm-cat-chip {
    display: inline-flex; align-items: center; gap: 0.25rem;
    padding: 0.3rem 0.625rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500;
    color: #4a5568; background: #f1f5f9; text-decoration: none; white-space: nowrap;
    transition: all .15s;
}
.fm-cat-chip:hover { background: #e2e8f0; }
.fm-cat-chip.active { background: #6a4c93; color: #fff; }
.fm-cat-chip.active .fm-cat-count { background: rgba(255,255,255,.2); color: #fff; }
.fm-cat-count { font-size: 0.7rem; background: #e2e8f0; padding: 0.1rem 0.35rem; border-radius: 4px; }

/* Tags */
.fm-tags { display: flex; gap: 0.375rem; flex-wrap: wrap; }
.fm-tag {
    padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500;
    background: #e2e8f0; color: #4a5568; text-decoration: none; transition: background .15s;
}
.fm-tag:hover { background: #cbd5e0; }

/* Content */
.fm-content { max-width: 800px; margin: 0 auto; padding: 1.25rem 0 2rem; display: flex; flex-direction: column; gap: 0.75rem; }
.fm-sticky-label { font-size: 0.8rem; font-weight: 700; color: #b7791f; text-transform: uppercase; letter-spacing: 0.05em; }

/* Post card */
.fm-card {
    background: #fff; border-radius: 10px; padding: 1rem 1.25rem;
    border: 1px solid #e2e8f0; transition: border-color .2s;
}
.fm-card:hover { border-color: #6a4c93; }
.fm-card.sticky { border-left: 3px solid #ffd700; background: #fffdf5; }

.fm-card-top { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap; }
.fm-type {
    font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px;
    text-transform: uppercase;
}
.fm-type-idea { background: #fef3c7; color: #92400e; }
.fm-type-question { background: #fee2e2; color: #991b1b; }
.fm-type-showcase { background: #d1fae5; color: #065f46; }
.fm-type-job { background: #e0e7ff; color: #3730a3; }
.fm-type-review { background: #fce7f3; color: #9d174d; }
.fm-cat-link { font-size: 0.75rem; color: #6a4c93; text-decoration: none; font-weight: 500; }
.fm-cat-link:hover { text-decoration: underline; }
.fm-time { font-size: 0.75rem; color: #a0aec0; margin-left: auto; }

.fm-card-title { margin: 0 0 0.375rem; }
.fm-card-title a { color: #1a202c; text-decoration: none; font-size: 1.1rem; font-weight: 600; line-height: 1.4; }
.fm-card-title a:hover { color: #6a4c93; }

.fm-card-excerpt { color: #64748b; font-size: 0.875rem; line-height: 1.5; margin: 0 0 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

.fm-card-tags { display: flex; gap: 0.375rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
.fm-card-tag { font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 3px; text-decoration: none; font-weight: 500; }

.fm-card-footer { display: flex; align-items: center; gap: 1rem; font-size: 0.8rem; color: #94a3b8; }
.fm-avatar {
    width: 24px; height: 24px; border-radius: 50%; font-size: 0.6rem; font-weight: 700;
    background: linear-gradient(135deg, #6a4c93, #9b5de5); color: #fff;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.fm-author { font-weight: 600; color: #4a5568; }
.fm-card-stats { display: flex; gap: 0.75rem; margin-left: auto; }
.fm-card-stat { display: flex; align-items: center; gap: 0.2rem; }
.fm-card-stat i { font-size: 0.75rem; }

/* Empty */
.fm-empty { text-align: center; padding: 3rem 1rem; background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; }
.fm-empty-ico { font-size: 3rem; margin-bottom: 0.75rem; }
.fm-empty h3 { font-size: 1.2rem; color: #1a202c; margin: 0 0 0.25rem; }
.fm-empty p { color: #94a3b8; margin: 0 0 1.25rem; }

/* Pagination */
.fm-pagi { display: flex; justify-content: center; margin-top: 1rem; }

/* Mobile */
@media (max-width: 768px) {
    .fm-hdr-row { flex-direction: column; text-align: center; }
    .fm-hdr-title { font-size: 1.25rem; }
    .fm-btn-create { width: 100%; justify-content: center; }
    .fm-filters { gap: 0.5rem; }
    .fm-sort { width: 100%; }
    .fm-sort-tab { flex: 1; text-align: center; }
    .fm-cats { width: 100%; }
    .fm-content { padding: 1rem 0; }
    .fm-card { padding: 0.875rem; }
    .fm-card-title a { font-size: 1rem; }
    .fm-time { display: none; }
    .fm-card-footer { flex-wrap: wrap; }
    .fm-card-stats { margin-left: 0; }
}
</style>
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
