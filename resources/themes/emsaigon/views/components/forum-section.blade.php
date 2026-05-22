{{-- FORUM SECTION REDESIGN — Card layout + tag chips + engagement metrics --}}
<section class="forum-section" id="forum">
    <div class="forum-section__header">
        <h2 class="forum-section__title">💬 Cộng đồng Forum</h2>
        <p class="forum-section__subtitle">Thảo luận, hỏi đáp và chia sẻ kinh nghiệm với game developers</p>
    </div>

    {{-- Category Filters --}}
    <div class="forum-section__filters">
        <a href="#" class="forum-section__filter forum-section__filter--active" data-filter="all">Tất cả</a>
        <a href="#" class="forum-section__filter" data-filter="unity">Unity</a>
        <a href="#" class="forum-section__filter" data-filter="unreal">Unreal</a>
        <a href="#" class="forum-section__filter" data-filter="design">Game Design</a>
        <a href="#" class="forum-section__filter" data-filter="career">Việc làm</a>
        <a href="#" class="forum-section__filter" data-filter="general">Tổng hợp</a>
    </div>

    {{-- Forum Cards Grid --}}
    <div class="forum-section__grid">
        @if(isset($forumTopics) && count($forumTopics) > 0)
            @foreach($forumTopics->take(6) as $topic)
                <article class="forum-card">
                    <div class="forum-card__top">
                        <img class="forum-card__avatar" src="{{ $topic->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($topic->user->name ?? 'User') . '&size=40' }}" alt="{{ $topic->user->name ?? 'User' }}" width="40" height="40">
                        <div>
                            <div class="forum-card__author">{{ $topic->user->name ?? 'Anonymous' }}</div>
                            <div class="forum-card__time">{{ $topic->created_at->diffForHumans() }}</div>
                        </div>
                        @if($topic->views_count > 100)
                            <span class="forum-card__trending" title="Trending">🔥</span>
                        @endif
                    </div>
                    <h3 class="forum-card__title">
                        <a href="{{ route('forum.topic.show', $topic->slug) }}">{{ $topic->title }}</a>
                    </h3>
                    <p class="forum-card__excerpt">{{ Str::limit(strip_tags($topic->body), 100) }}</p>
                    <div class="forum-card__tags">
                        @foreach($topic->tags->take(3) as $tag)
                            <span class="forum-card__tag forum-card__tag--{{ $tag->slug ?? 'general' }}">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <div class="forum-card__metrics">
                        <span class="forum-card__metric"><i class="fa fa-eye"></i> {{ $topic->views_count ?? 0 }}</span>
                        <span class="forum-card__metric"><i class="fa fa-comment-o"></i> {{ $topic->replies_count ?? 0 }}</span>
                        <span class="forum-card__metric"><i class="fa fa-heart-o"></i> {{ $topic->likes_count ?? 0 }}</span>
                    </div>
                </article>
            @endforeach
        @else
            {{-- Sample data --}}
            @php
                $sampleTopics = [
                    ['title' => 'Unity vs Unreal Engine 2026: Nên chọn engine nào cho game mobile?', 'author' => 'DevMaster', 'time' => '2 giờ trước', 'excerpt' => 'So sánh chi tiết performance, workflow và ecosystem giữa 2 engine phổ biến nhất...', 'tags' => [['name' => 'Unity', 'class' => 'unity'], ['name' => 'Unreal', 'class' => 'unreal']], 'views' => 523, 'comments' => 47, 'likes' => 89, 'hot' => true],
                    ['title' => 'Kinh nghiệm phỏng vấn Unity Developer tại VNG', 'author' => 'GameHunter', 'time' => '5 giờ trước', 'excerpt' => 'Chia sẻ quy trình phỏng vấn 3 vòng và các câu hỏi thường gặp...', 'tags' => [['name' => 'Career', 'class' => 'career'], ['name' => 'Unity', 'class' => 'unity']], 'views' => 312, 'comments' => 28, 'likes' => 65, 'hot' => true],
                    ['title' => 'Tối ưu hóa draw calls trong Unity cho game 3D mobile', 'author' => 'PerfGuru', 'time' => '1 ngày trước', 'excerpt' => 'Hướng dẫn giảm draw calls từ 500 xuống 50 bằng batching và atlasing...', 'tags' => [['name' => 'Unity', 'class' => 'unity']], 'views' => 198, 'comments' => 15, 'likes' => 42, 'hot' => true],
                    ['title' => 'Game Design Document template cho indie developers', 'author' => 'DesignPro', 'time' => '2 ngày trước', 'excerpt' => 'Template GDD đầy đủ với ví dụ thực tế, phù hợp cho team nhỏ 2-5 người...', 'tags' => [['name' => 'Design', 'class' => 'design']], 'views' => 156, 'comments' => 12, 'likes' => 38, 'hot' => false],
                    ['title' => 'Multiplayer networking với Photon Fusion 2', 'author' => 'NetDev', 'time' => '3 ngày trước', 'excerpt' => 'Setup multiplayer từ đầu: lobby, matchmaking, state sync cho game action...', 'tags' => [['name' => 'Unity', 'class' => 'unity']], 'views' => 89, 'comments' => 8, 'likes' => 21, 'hot' => false],
                    ['title' => 'Lương Game Developer Việt Nam 2026 — Khảo sát thực tế', 'author' => 'CareerDev', 'time' => '4 ngày trước', 'excerpt' => 'Tổng hợp mức lương theo vị trí, kinh nghiệm và công ty từ 200+ responses...', 'tags' => [['name' => 'Career', 'class' => 'career']], 'views' => 445, 'comments' => 56, 'likes' => 112, 'hot' => true],
                ];
            @endphp
            @foreach($sampleTopics as $topic)
                <article class="forum-card">
                    <div class="forum-card__top">
                        <img class="forum-card__avatar" src="https://ui-avatars.com/api/?name={{ urlencode($topic['author']) }}&size=40&background=e8f5ea&color=0a3b09" alt="{{ $topic['author'] }}" width="40" height="40" loading="lazy">
                        <div>
                            <div class="forum-card__author">{{ $topic['author'] }}</div>
                            <div class="forum-card__time">{{ $topic['time'] }}</div>
                        </div>
                        @if($topic['hot'])
                            <span class="forum-card__trending" title="Trending">🔥</span>
                        @endif
                    </div>
                    <h3 class="forum-card__title">
                        <a href="{{ route('forum.index') }}">{{ $topic['title'] }}</a>
                    </h3>
                    <p class="forum-card__excerpt">{{ $topic['excerpt'] }}</p>
                    <div class="forum-card__tags">
                        @foreach($topic['tags'] as $tag)
                            <span class="forum-card__tag forum-card__tag--{{ $tag['class'] }}">{{ $tag['name'] }}</span>
                        @endforeach
                    </div>
                    <div class="forum-card__metrics">
                        <span class="forum-card__metric"><i class="fa fa-eye"></i> {{ $topic['views'] }}</span>
                        <span class="forum-card__metric"><i class="fa fa-comment-o"></i> {{ $topic['comments'] }}</span>
                        <span class="forum-card__metric"><i class="fa fa-heart-o"></i> {{ $topic['likes'] }}</span>
                    </div>
                </article>
            @endforeach
        @endif
    </div>

    <div class="forum-section__cta">
        <a href="{{ route('forum.index') }}" class="ds-btn ds-btn--primary ds-btn--lg ds-btn--pill">
            Xem tất cả thảo luận <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</section>
