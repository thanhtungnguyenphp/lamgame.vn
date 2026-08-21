@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@push('schema_markup')
<script type="application/ld+json">
{!! json_encode($author->getSchemaOrgData(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="author-profile">
    <div class="container">
        <div class="author-profile__header">
            <div class="author-profile__avatar">
                @if($author->avatar)
                    <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}" width="120" height="120">
                @else
                    <div class="author-profile__avatar-placeholder">
                        {{ strtoupper(substr($author->name, 0, 1)) }}
                    </div>
                @endif
                
                @if($author->is_verified)
                    <span class="author-profile__verified" title="Tác giả đã xác minh">✓</span>
                @endif
            </div>
            
            <div class="author-profile__info">
                <h1>{{ $author->name }}</h1>
                
                @if($author->title)
                    <p class="author-profile__title">{{ $author->title }}</p>
                @endif
                
                <div class="author-profile__meta">
                    @if($author->experience_years)
                        <span>{{ $author->experience_years }}+ năm kinh nghiệm</span>
                    @endif
                    
                    @if($author->is_staff)
                        <span class="badge badge--staff">Đội ngũ LamGame</span>
                    @endif
                </div>
                
                @if($author->expertise && count($author->expertise))
                    <div class="author-profile__expertise">
                        @foreach($author->expertise as $skill)
                            <span class="tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif
                
                @if($author->social_links)
                    <div class="author-profile__social">
                        @if(!empty($author->social_links['github']))
                            <a href="{{ $author->social_links['github'] }}" target="_blank" rel="noopener" title="GitHub">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                        @endif
                        @if(!empty($author->social_links['linkedin']))
                            <a href="{{ $author->social_links['linkedin'] }}" target="_blank" rel="noopener" title="LinkedIn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        @endif
                        @if(!empty($author->social_links['twitter']))
                            <a href="{{ $author->social_links['twitter'] }}" target="_blank" rel="noopener" title="Twitter">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif
                        @if($author->website)
                            <a href="{{ $author->website }}" target="_blank" rel="noopener" title="Website">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        @if($author->bio)
            <div class="author-profile__bio">
                <h2>Giới thiệu</h2>
                <div class="prose">
                    {!! nl2br(e($author->bio)) !!}
                </div>
            </div>
        @endif
        
        <div class="author-profile__articles">
            <h2>Bài viết của {{ $author->name }} ({{ $articles->total() }})</h2>
            
            @if($articles->count())
                <div class="articles-grid">
                    @foreach($articles as $article)
                        <article class="article-card">
                            @if($article->image)
                                <a href="{{ route('blog.show', $article->slug) }}" class="article-card__image">
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->name }}" loading="lazy">
                                </a>
                            @endif
                            <div class="article-card__content">
                                <h3>
                                    <a href="{{ route('blog.show', $article->slug) }}">{{ $article->name }}</a>
                                </h3>
                                @if($article->short_description)
                                    <p>{{ \Str::limit($article->short_description, 120) }}</p>
                                @endif
                                <time datetime="{{ $article->created_at->toISOString() }}">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </time>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                {{ $articles->links() }}
            @else
                <p class="no-articles">Chưa có bài viết nào.</p>
            @endif
        </div>
    </div>
</div>

<style>
.author-profile {
    padding: 2rem 0;
}

.author-profile__header {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.author-profile__avatar {
    position: relative;
    flex-shrink: 0;
}

.author-profile__avatar img,
.author-profile__avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

.author-profile__avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
}

.author-profile__verified {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: #10b981;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 3px solid white;
}

.author-profile__info h1 {
    margin: 0 0 0.5rem;
    font-size: 1.75rem;
}

.author-profile__title {
    color: #666;
    margin: 0 0 0.5rem;
}

.author-profile__meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #666;
}

.badge--staff {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.author-profile__expertise {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.author-profile__expertise .tag {
    background: #f3f4f6;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
}

.author-profile__social {
    display: flex;
    gap: 1rem;
}

.author-profile__social a {
    color: #666;
    transition: color 0.2s;
}

.author-profile__social a:hover {
    color: #333;
}

.author-profile__bio {
    margin-bottom: 2rem;
}

.author-profile__bio h2 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.author-profile__articles h2 {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.article-card {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.article-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.article-card__image img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.article-card__content {
    padding: 1rem;
}

.article-card__content h3 {
    margin: 0 0 0.5rem;
    font-size: 1rem;
}

.article-card__content h3 a {
    color: inherit;
    text-decoration: none;
}

.article-card__content h3 a:hover {
    color: #667eea;
}

.article-card__content p {
    color: #666;
    font-size: 0.9rem;
    margin: 0 0 0.5rem;
}

.article-card__content time {
    color: #999;
    font-size: 0.85rem;
}

@media (max-width: 640px) {
    .author-profile__header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .author-profile__meta,
    .author-profile__expertise,
    .author-profile__social {
        justify-content: center;
    }
}
</style>
@endsection
