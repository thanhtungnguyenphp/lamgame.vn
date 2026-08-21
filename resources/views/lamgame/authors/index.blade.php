@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@section('content')
<div class="authors-page">
    <div class="container">
        <header class="authors-page__header">
            <h1>Đội ngũ tác giả</h1>
            <p>Gặp gỡ những chuyên gia và tác giả đằng sau các bài viết chất lượng tại LamGame.vn</p>
        </header>
        
        @if($authors->count())
            <div class="authors-grid">
                @foreach($authors as $author)
                    <a href="{{ route('authors.show', $author->slug) }}" class="author-card">
                        <div class="author-card__avatar">
                            @if($author->avatar)
                                <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}" width="80" height="80">
                            @else
                                <div class="author-card__avatar-placeholder">
                                    {{ strtoupper(substr($author->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            @if($author->is_verified)
                                <span class="author-card__verified">✓</span>
                            @endif
                        </div>
                        
                        <div class="author-card__info">
                            <h2>{{ $author->name }}</h2>
                            
                            @if($author->title)
                                <p class="author-card__title">{{ $author->title }}</p>
                            @endif
                            
                            <div class="author-card__stats">
                                <span>{{ $author->blogs_count }} bài viết</span>
                                @if($author->experience_years)
                                    <span>{{ $author->experience_years }}+ năm KN</span>
                                @endif
                            </div>
                            
                            @if($author->is_staff)
                                <span class="badge badge--staff">Đội ngũ LamGame</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            
            {{ $authors->links() }}
        @else
            <p class="no-authors">Chưa có tác giả nào.</p>
        @endif
    </div>
</div>

<style>
.authors-page {
    padding: 2rem 0;
}

.authors-page__header {
    text-align: center;
    margin-bottom: 3rem;
}

.authors-page__header h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.authors-page__header p {
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.authors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.author-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border: 1px solid #eee;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.author-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.author-card__avatar {
    position: relative;
    flex-shrink: 0;
}

.author-card__avatar img,
.author-card__avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
}

.author-card__avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
}

.author-card__verified {
    position: absolute;
    bottom: 2px;
    right: 2px;
    background: #10b981;
    color: white;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: 2px solid white;
}

.author-card__info h2 {
    margin: 0 0 0.25rem;
    font-size: 1.1rem;
}

.author-card__title {
    color: #666;
    font-size: 0.9rem;
    margin: 0 0 0.5rem;
}

.author-card__stats {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 0.5rem;
}

.badge--staff {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
}

@media (max-width: 640px) {
    .author-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .author-card__stats {
        justify-content: center;
    }
}
</style>
@endsection
