{{-- News Section --}}
<section class="wc26-news" id="news">
    <div class="container">
        <h2 class="wc26-section__title">📰 Tin tức & Phân tích</h2>
        <p class="wc26-section__desc">Cập nhật mới nhất về World Cup 2026</p>

        <div class="wc26-news__grid" id="wc26-news-grid">
            {{-- Loaded via API or static content --}}
            @if(isset($articles) && $articles->count())
                @foreach($articles->take(6) as $article)
                <article class="wc26-news__card">
                    @if($article->image_url)
                    <img class="wc26-news__img" src="{{ $article->image_url }}" alt="{{ $article->title }}" loading="lazy">
                    @endif
                    <div class="wc26-news__body">
                        <span class="wc26-news__source">{{ $article->source ?? 'LamGame' }}</span>
                        <h3 class="wc26-news__title">
                            @if($article->source_url)
                                <a href="{{ $article->source_url }}" target="_blank" rel="noopener">{{ $article->title }}</a>
                            @else
                                {{ $article->title }}
                            @endif
                        </h3>
                        <p class="wc26-news__summary">{{ Str::limit($article->summary, 120) }}</p>
                        <time class="wc26-news__time" datetime="{{ $article->created_at->toIso8601String() }}">
                            {{ $article->created_at->diffForHumans() }}
                        </time>
                    </div>
                </article>
                @endforeach
            @else
                <div class="wc26-news__placeholder">
                    <p>🔄 Tin tức sẽ được cập nhật tự động khi giải đấu bắt đầu.</p>
                    <p>Theo dõi trang này để nhận thông tin mới nhất!</p>
                </div>
            @endif
        </div>

        @if(isset($articles) && $articles->count() > 6)
        <div class="wc26-news__more">
            <a href="/bong-da" class="wc26-btn wc26-btn--outline">Xem thêm tin tức →</a>
        </div>
        @endif
    </div>
</section>
