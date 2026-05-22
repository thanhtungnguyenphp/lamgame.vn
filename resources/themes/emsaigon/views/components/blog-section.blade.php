{{-- BLOG SECTION REDESIGN — Featured post + grid layout + reading time --}}
<section class="blog-section" id="blog">
    <div class="blog-section__container">
        <div class="blog-section__header">
            <h2 class="blog-section__title">📝 Blog & Kiến thức</h2>
            <p class="blog-section__subtitle">Bài viết chất lượng từ cộng đồng game developers</p>
        </div>

        @php
            $blogPosts = $blogPosts ?? collect();
            $featured = $blogPosts->first();
            $gridPosts = $blogPosts->skip(1)->take(3);
            
            // Sample data nếu chưa có
            if (!$featured) {
                $featured = (object)[
                    'title' => 'Hướng dẫn tối ưu hóa Performance Unity cho Game Mobile 2026',
                    'slug' => '#',
                    'excerpt' => 'Tổng hợp 15 kỹ thuật tối ưu performance từ cơ bản đến nâng cao: object pooling, LOD, occlusion culling, shader optimization và nhiều hơn nữa.',
                    'image' => 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=800&h=500&fit=crop',
                    'author_name' => 'DevMaster',
                    'author_avatar' => null,
                    'category' => 'Unity',
                    'reading_time' => 8,
                    'created_at' => now()->subDays(1),
                ];
                $gridPosts = collect([
                    (object)['title' => 'Multiplayer Networking: Photon vs Mirror vs Netcode', 'slug' => '#', 'excerpt' => 'So sánh 3 giải pháp networking phổ biến nhất cho Unity multiplayer games...', 'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=225&fit=crop', 'author_name' => 'NetDev', 'author_avatar' => null, 'category' => 'Networking', 'reading_time' => 6, 'created_at' => now()->subDays(3)],
                    (object)['title' => 'Shader Graph: Tạo hiệu ứng nước realistic trong 30 phút', 'slug' => '#', 'excerpt' => 'Step-by-step tạo water shader với reflection, refraction và foam...', 'image' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=225&fit=crop', 'author_name' => 'ShaderWiz', 'author_avatar' => null, 'category' => 'Graphics', 'reading_time' => 5, 'created_at' => now()->subDays(5)],
                    (object)['title' => 'Game Design: Cách thiết kế progression system gây nghiện', 'slug' => '#', 'excerpt' => 'Phân tích progression system của Hades, Vampire Survivors và áp dụng cho game indie...', 'image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=400&h=225&fit=crop', 'author_name' => 'DesignPro', 'author_avatar' => null, 'category' => 'Game Design', 'reading_time' => 7, 'created_at' => now()->subDays(7)],
                ]);
            }
        @endphp

        {{-- Featured Post --}}
        @if($featured)
        <article class="blog-featured">
            <div class="blog-featured__image">
                <img src="{{ $featured->image ?? 'https://images.unsplash.com/photo-1556438064-2d7646166914?w=800&h=500&fit=crop' }}" alt="{{ $featured->title }}" loading="lazy" width="800" height="500">
            </div>
            <div class="blog-featured__content">
                <span class="blog-featured__badge">⭐ Bài viết nổi bật</span>
                <h3 class="blog-featured__title">
                    <a href="{{ is_string($featured->slug) && $featured->slug !== '#' ? route('lamgame.blog.show', $featured->slug) : route('lamgame.blog') }}">{{ $featured->title }}</a>
                </h3>
                <p class="blog-featured__excerpt">{{ $featured->excerpt ?? '' }}</p>
                <div class="blog-featured__meta">
                    <div class="blog-featured__author">
                        <img src="{{ $featured->author_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($featured->author_name ?? 'Author') . '&size=28' }}" alt="{{ $featured->author_name ?? 'Author' }}" width="28" height="28">
                        <span>{{ $featured->author_name ?? 'Author' }}</span>
                    </div>
                    <span>📖 {{ $featured->reading_time ?? 5 }} phút đọc</span>
                </div>
            </div>
        </article>
        @endif

        {{-- Blog Grid --}}
        <div class="blog-grid">
            @foreach($gridPosts as $post)
            <article class="blog-card">
                <div class="blog-card__image">
                    <img src="{{ $post->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=225&fit=crop' }}" alt="{{ $post->title }}" loading="lazy" width="400" height="225">
                    <span class="blog-card__reading-time">{{ $post->reading_time ?? 5 }} min</span>
                </div>
                <div class="blog-card__body">
                    <div class="blog-card__category">{{ $post->category ?? 'General' }}</div>
                    <h3 class="blog-card__title">
                        <a href="{{ is_string($post->slug) && $post->slug !== '#' ? route('lamgame.blog.show', $post->slug) : route('lamgame.blog') }}">{{ $post->title }}</a>
                    </h3>
                    <p class="blog-card__excerpt">{{ $post->excerpt ?? '' }}</p>
                    <div class="blog-card__footer">
                        <div class="blog-card__author">
                            <img src="{{ $post->author_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author_name ?? 'Author') . '&size=24' }}" alt="{{ $post->author_name ?? 'Author' }}" width="24" height="24">
                            <span>{{ $post->author_name ?? 'Author' }}</span>
                        </div>
                        <span class="blog-card__date">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="blog-section__cta">
            <a href="{{ route('lamgame.blog') }}" class="ds-btn ds-btn--primary ds-btn--lg ds-btn--pill">
                Xem tất cả bài viết <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
