@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)
@section('og_type', 'article')
@section('og_image', $blog->featured_image ?? asset('assets/logos/png/logo-square-512.png'))
@section('twitter_card', 'summary_large_image')

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $blog->name }}",
    "description": "{{ Str::limit(strip_tags($blog->short_description ?? $blog->description ?? ''), 200) }}",
    "url": "{{ url()->current() }}",
    @if($blog->src)"image": "{{ asset('storage/' . $blog->src) }}",@endif
    "datePublished": "{{ $blog->published_at ?? $blog->created_at }}",
    "author": {"@type": "Person", "name": "{{ $blog->author ?? 'LAMGAME' }}"},
    "publisher": {"@type": "Organization", "name": "LAMGAME", "url": "https://lamgame.vn"}
}
</script>
@endpush

@if($page_keywords)
@section('meta_keywords', $page_keywords)
@endif

@push('og_extra')
    <meta property="article:published_time" content="{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $blog->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $blog->author ?? 'Làm Game' }}">
    @if($postCategories->count() > 0)
    <meta property="article:section" content="{{ $postCategories->first()->name }}">
    @endif
    @foreach($postTags as $tag)
    <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
@endpush

@push('meta')
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::article($blog) !!}
    </script>
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::breadcrumb([
        ['name' => 'Trang chủ', 'url' => config('app.url')],
        ['name' => 'Blog', 'url' => config('app.url') . '/blog'],
        ['name' => $blog->name, 'url' => config('app.url') . '/blog/' . $blog->slug]
    ]) !!}
    </script>
    @php $faqs = $blog->extractFaqs(); @endphp
    @if(count($faqs) > 0)
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::faq($faqs) !!}
    </script>
    @endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.trackEvent === 'function') {
            window.trackEvent('blog_view', {
                'event_category': 'blog',
                'event_label': '{{ addslashes($blog->name) }}',
                'blog_id': '{{ $blog->id }}',
                'blog_category': '{{ $blog->category->name ?? "general" }}',
                'value': 1
            });
        }
    });
</script>
@endpush

@section('content')
{{-- Reading Progress Bar --}}
<div class="bd-progress" id="readingProgress"></div>

<div class="bd-page">
    {{-- HERO --}}
    <section class="bd-hero">
        <div class="bd-hero__bg"></div>
        <div class="bd-container bd-hero__inner">
            <div class="bd-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('lamgame.blog') }}">Blog</a>
                <span>/</span>
                <span>{{ Str::limit($blog->name, 40) }}</span>
            </div>
            <div class="bd-hero__meta">
                @if($blog->category)
                <a href="{{ route('lamgame.blog', ['category' => $blog->category->slug]) }}" class="bd-badge">{{ $blog->category->name }}</a>
                @endif
                <span class="bd-meta">{{ $blog->formatted_date }}</span>
                <span class="bd-meta">{{ $blog->reading_time }} phút đọc</span>
            </div>
            <h1 class="bd-hero__title">{{ $blog->name }}</h1>
            @if($blog->short_description)
            <p class="bd-hero__excerpt">{{ Str::limit(strip_tags($blog->short_description), 160) }}</p>
            @endif
        </div>
    </section>

    {{-- FEATURED IMAGE --}}
    <div class="bd-container">
        <div class="bd-featured-img">
            <img src="{{ $blog->featured_image }}" alt="{{ $blog->name }}" width="800" height="420" fetchpriority="high">
        </div>
    </div>

    {{-- CONTENT LAYOUT --}}
    <div class="bd-container">
        <div class="bd-layout">
            {{-- MAIN ARTICLE --}}
            <article class="bd-article">
                {{-- Author Box Top --}}
                <div class="bd-author-top">
                    <div class="bd-avatar">{{ strtoupper(substr($blog->author ?? 'L', 0, 1)) }}</div>
                    <div>
                        <span class="bd-author__name">{{ $blog->author ?? 'LamGame' }}</span>
                        <span class="bd-author__role">Game Developer & Writer</span>
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="bd-content" id="articleContent">
                    {!! $blog->description !!}
                </div>

                {{-- Tags --}}
                @if($postTags->count() > 0)
                <div class="bd-tags">
                    @foreach($postTags as $tag)
                    <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" class="bd-tag">{{ $tag->name }}</a>
                    @endforeach
                </div>
                @endif

                {{-- Reactions --}}
                <div class="bd-reactions">
                    <span class="bd-reactions__label">Bài viết hữu ích?</span>
                    <div class="bd-reactions__btns">
                        <button class="bd-react" data-type="like">👍</button>
                        <button class="bd-react" data-type="love">❤️</button>
                        <button class="bd-react" data-type="fire">🔥</button>
                        <button class="bd-react" data-type="think">🤔</button>
                    </div>
                </div>

                {{-- Social Share --}}
                <div class="bd-share">
                    <span class="bd-share__label">Chia sẻ:</span>
                    <div class="bd-share__btns">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="bd-share__btn bd-share--fb">FB</a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->name) }}" target="_blank" rel="noopener noreferrer" class="bd-share__btn bd-share--tw">X</a>
                        <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->name) }}" target="_blank" rel="noopener noreferrer" class="bd-share__btn bd-share--tg">TG</a>
                        <button onclick="copyToClipboard('{{ request()->url() }}')" class="bd-share__btn bd-share--copy">🔗</button>
                    </div>
                </div>

                {{-- Author Box Bottom --}}
                <div class="bd-author-box">
                    <div class="bd-avatar bd-avatar--lg">{{ strtoupper(substr($blog->author ?? 'L', 0, 1)) }}</div>
                    <div class="bd-author-box__info">
                        <h4>{{ $blog->author ?? 'LamGame' }}</h4>
                        <p>Game Developer & Technical Writer tại LamGame.vn. Chia sẻ kiến thức về game development, Unity, AI tools cho cộng đồng developer Việt Nam.</p>
                    </div>
                </div>
            </article>

            {{-- SIDEBAR (Sticky TOC + Widgets) --}}
            <aside class="bd-sidebar">
                {{-- Table of Contents --}}
                <div class="bd-toc" id="tocWidget">
                    <h3 class="bd-toc__title">📑 Mục lục</h3>
                    <nav class="bd-toc__nav" id="tocNav"></nav>
                </div>

                {{-- Recent Posts --}}
                <div class="bd-widget">
                    <h3 class="bd-widget__title">Bài viết mới</h3>
                    @foreach($recentPosts->take(4) as $post)
                    <a href="/blog/{{ $post->slug }}" class="bd-recent">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->name }}" loading="lazy">
                        <div>
                            <span class="bd-recent__title">{{ Str::limit($post->name, 45) }}</span>
                            <span class="bd-recent__date">{{ $post->formatted_date }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>

    {{-- RELATED POSTS --}}
    @if($relatedPosts->count() > 0)
    <section class="bd-section">
        <div class="bd-container">
            <h2 class="bd-section__title">Bài viết liên quan</h2>
            <div class="bd-related">
                @foreach($relatedPosts as $relatedPost)
                <a href="/blog/{{ $relatedPost->slug }}" class="bd-related__card">
                    <div class="bd-related__img">
                        <img src="{{ $relatedPost->featured_image }}" alt="{{ $relatedPost->name }}" loading="lazy">
                    </div>
                    <div class="bd-related__body">
                        <span class="bd-badge bd-badge--sm">{{ $relatedPost->category->name ?? '' }}</span>
                        <h3>{{ Str::limit($relatedPost->name, 60) }}</h3>
                        <span class="bd-meta">{{ $relatedPost->reading_time }} phút đọc</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="bd-section bd-section--cta">
        <div class="bd-container" style="text-align:center">
            <h2 class="bd-section__title">Đọc thêm bài viết hay</h2>
            <p style="color:#7A8599;margin-bottom:20px">Khám phá kiến thức game dev, tips & tricks từ cộng đồng</p>
            <a href="{{ route('lamgame.blog') }}" class="bd-btn bd-btn--primary">← Về trang Blog</a>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/blog-detail.css') }}">
@endpush

@push('scripts')
<script>
// Reading Progress Bar
window.addEventListener('scroll', function() {
    const article = document.getElementById('articleContent');
    if (!article) return;
    const rect = article.getBoundingClientRect();
    const total = article.scrollHeight;
    const scrolled = Math.max(0, -rect.top);
    const pct = Math.min(100, (scrolled / total) * 100);
    document.getElementById('readingProgress').style.width = pct + '%';
});

// Auto-generate TOC from headings
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('articleContent');
    const tocNav = document.getElementById('tocNav');
    if (!content || !tocNav) return;
    const headings = content.querySelectorAll('h2, h3');
    if (headings.length === 0) {
        document.getElementById('tocWidget').style.display = 'none';
        return;
    }
    headings.forEach(function(h, i) {
        h.id = 'heading-' + i;
        const a = document.createElement('a');
        a.href = '#heading-' + i;
        a.textContent = h.textContent;
        a.className = 'bd-toc__link' + (h.tagName === 'H3' ? ' bd-toc__link--sub' : '');
        tocNav.appendChild(a);
    });
});

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link đã được copy!');
    });
}
</script>
@endpush
