@extends('layouts.master')

@section('page_title', $page_title ?? __('lamgame.community.page_title'))

@section('page_description', $page_description ?? 'Tham gia cộng đồng game developer Việt Nam. Chia sẻ kinh nghiệm, tìm kiếm đồng đội và học hỏi từ những chuyên gia.')

@section('content')
    <!-- Community Hero Section -->
    <section class="community-hero">
        <div class="container">
            <div class="hero-content">
                <h1>{{ __('lamgame.community.hero') }}</h1>
                <p class="hero-subtitle">
                    Nơi kết nối các game developer Việt Nam. Chia sẻ kinh nghiệm, thảo luận kỹ thuật, 
                    tìm kiếm đồng đội và cùng nhau phát triển trong ngành công nghiệp game.
                </p>
                <div class="community-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ number_format($siteMetrics['registered_users'] ?? 0) }}+</div>
                        <div class="stat-label">{{ __('lamgame.community.members') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $siteMetrics['forum_posts'] ?? 0 }}+</div>
                        <div class="stat-label">{{ __('lamgame.community.posts') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $siteMetrics['blog_posts'] ?? 0 }}+</div>
                        <div class="stat-label">Tutorial</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
        <div class="container">
            <div class="actions-grid">
                <div class="action-card featured" onclick="location.href='{{ route('lamgame.chia-se-y-tuong') }}'">
                    <div class="action-icon">💡</div>
                    <h3>{{ __('lamgame.community.share_idea') }}</h3>
                    <p>{{ __('lamgame.community.share_idea_d') }}</p>
                    <span class="action-badge">Hot</span>
                </div>
                <div class="action-card" onclick="showNewPostForm('thao-luan')">
                    <div class="action-icon">💬</div>
                    <h3>{{ __('lamgame.community.new_discuss') }}</h3>
                    <p>{{ __('lamgame.community.new_discuss_d') }}</p>
                </div>
                <div class="action-card" onclick="showNewPostForm('tim-team')">
                    <div class="action-icon">👥</div>
                    <h3>{{ __('lamgame.community.find_team') }}</h3>
                    <p>{{ __('lamgame.community.find_team_d') }}</p>
                </div>
                <div class="action-card" onclick="showNewPostForm('showcase')">
                    <div class="action-icon">🎯</div>
                    <h3>{{ __('lamgame.community.showcase') }}</h3>
                    <p>{{ __('lamgame.community.showcase_d') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories & Latest Posts -->
    <section class="community-content">
        <div class="container">
            <div class="content-grid">
                <!-- Categories Sidebar -->
                <div class="categories-sidebar">
                    <h3>{{ __('lamgame.community.cats') }}</h3>
                    <div class="categories-list">
                        @foreach($categories as $key => $category)
                        <div class="category-item {{ $key === 'chia-se-y-tuong' ? 'featured' : '' }}" 
                             onclick="filterByCategory('{{ $key }}')">
                            <span class="category-icon">
                                @switch($key)
                                    @case('thao-luan') 💭 @break
                                    @case('chia-se-y-tuong') 💡 @break
                                    @case('tim-team') 👥 @break
                                    @case('review-khoa-hoc') 📚 @break
                                    @case('ho-tro-ky-thuat') 🛠️ @break
                                    @case('showcase') 🎯 @break
                                    @default 📝
                                @endswitch
                            </span>
                            <div class="category-info">
                                <div class="category-name">{{ $category }}</div>
                                @if($key === 'chia-se-y-tuong')
                                <div class="category-highlight">Nổi bật</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Community Guidelines -->
                    <div class="guidelines-box">
                        <h4>{{ __('lamgame.community.rules') }}</h4>
                        <ul>
                            <li>{{ __('lamgame.community.rule1') }}</li>
                            <li>{{ __('lamgame.community.rule2') }}</li>
                            <li>{{ __('lamgame.community.rule3') }}</li>
                            <li>{{ __('lamgame.community.rule4') }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Posts Feed -->
                <div class="posts-feed">
                    <div class="feed-header">
                        <h2>{{ __('lamgame.community.latest') }}</h2>
                        <div class="feed-filters">
                            <select id="sortFilter" onchange="sortPosts(this.value)">
                                <option value="newest">{{ __('lamgame.community.sort_newest') }}</option>
                                <option value="popular">{{ __('lamgame.community.sort_popular') }}</option>
                                <option value="trending">{{ __('lamgame.community.sort_trending') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="posts-list">
                        @foreach($posts as $post)
                        <div class="post-card" data-category="{{ strtolower(str_replace(' ', '-', $post['category'])) }}">
                            <div class="post-header">
                                <div class="post-category {{ $post['category'] === 'Chia sẻ ý tưởng' ? 'featured-category' : '' }}">
                                    @if($post['category'] === 'Chia sẻ ý tưởng') 💡 @endif
                                    {{ $post['category'] }}
                                </div>
                                <div class="post-time">{{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}</div>
                            </div>
                            
                            <h3 class="post-title">
                                <a href="{{ route('forum.posts.show', $post['id']) }}">{{ $post['title'] }}</a>
                            </h3>
                            
                            <p class="post-excerpt">{{ $post['excerpt'] }}</p>
                            
                            <div class="post-meta">
                                <div class="post-author">
                                    <span class="author-icon">👤</span>
                                    <span>{{ $post['author'] }}</span>
                                </div>
                                <div class="post-stats">
                                    <span class="stat-item">
                                        <span class="stat-icon">👁️</span>
                                        <span>{{ $post['views'] }}</span>
                                    </span>
                                    <span class="stat-item">
                                        <span class="stat-icon">💬</span>
                                        <span>{{ $post['replies'] }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            @if($post['category'] === 'Chia sẻ ý tưởng')
                            <div class="idea-badge">
                                <span class="badge-text">{{ __('lamgame.community.badge_idea') }}</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Load More Button -->
                    <div class="load-more-section">
                        <button class="btn btn-outline" onclick="loadMorePosts()">
                            Xem thêm bài viết
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Community Features -->
    <section class="community-features">
        <div class="container">
            <h2 class="section-title">{{ __('lamgame.community.features') }}</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>{{ __('lamgame.community.points') }}</h3>
                    <p>{{ __('lamgame.community.points_d') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3>Mentorship</h3>
                    <p>{{ __('lamgame.community.mentor_d') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>{{ __('lamgame.community.events') }}</h3>
                    <p>{{ __('lamgame.community.events_d') }}</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💼</div>
                    <h3>Job board</h3>
                    <p>{{ __('lamgame.community.jobs_d') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Community CTA -->
    <section class="join-community">
        <div class="container">
            <div class="join-content">
                <h2>{{ __('lamgame.community.cta') }}</h2>
                <p>{{ __('lamgame.community.cta_sub') }}</p>
                <div class="join-actions">
                    <button class="btn btn-primary btn-large" onclick="showRegistrationForm()">
                        🚀 Tham gia ngay
                    </button>
                    <button class="btn btn-outline btn-large" onclick="location.href='{{ route('lamgame.chia-se-y-tuong') }}'">
                        💡 Xem ý tưởng game
                    </button>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .community-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .community-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 800;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 40px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .community-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 50px;
        }
        
        .quick-actions {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .action-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .action-card.featured {
            border: 3px solid #ffd700;
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        }
        
        .action-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .action-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .action-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff6b35;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .community-content {
            padding: 80px 0;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
        }
        
        .categories-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .categories-sidebar h3 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .categories-list {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .category-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }
        
        .category-item:hover {
            background: #f8f9fa;
        }
        
        .category-item.featured {
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
            border: 2px solid #ffd700;
        }
        
        .category-icon {
            font-size: 1.5rem;
        }
        
        .category-name {
            font-weight: 600;
            color: #333;
        }
        
        .category-highlight {
            color: #ff6b35;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .guidelines-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .guidelines-box h4 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .guidelines-box ul {
            list-style: none;
            padding: 0;
        }
        
        .guidelines-box li {
            padding: 8px 0;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        
        .guidelines-box li:before {
            content: '✓';
            color: #667eea;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .feed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .feed-header h2 {
            margin: 0;
            color: #333;
        }
        
        .feed-filters select {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
        }
        
        .post-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .post-category {
            background: #e2e8f0;
            color: #4a5568;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .post-category.featured-category {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #744210;
            font-weight: bold;
        }
        
        .post-time {
            color: #a0aec0;
            font-size: 0.9rem;
        }
        
        .post-title a {
            color: #333;
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .post-title a:hover {
            color: #667eea;
        }
        
        .post-excerpt {
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
        }
        
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .post-author {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-weight: 500;
        }
        
        .post-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #a0aec0;
            font-size: 0.9rem;
        }
        
        .idea-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #744210;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(255, 215, 0, 0.4);
        }
        
        .load-more-section {
            text-align: center;
            margin-top: 40px;
        }
        
        .community-features {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .join-community {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .join-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .join-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .join-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: #ff6b35;
            color: white;
        }
        
        .btn-primary:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline:hover {
            background: white;
            color: #667eea;
        }
        
        .btn-large {
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .community-hero h1 {
                font-size: 2rem;
            }
            
            .community-stats {
                flex-direction: column;
                gap: 30px;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .categories-sidebar {
                position: static;
            }
            
            .join-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function filterByCategory(category) {
            console.log('Filter by category:', category);
            const posts = document.querySelectorAll('.post-card');
            
            posts.forEach(post => {
                if (category === 'all' || post.dataset.category === category) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
            
            // Update active category
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.category-item').classList.add('active');
        }
        
        function sortPosts(sortBy) {
            console.log('Sort posts by:', sortBy);
            // Implement sorting logic here
        }
        
        function loadMorePosts() {
            console.log('Loading more posts...');
            // Implement pagination/infinite scroll here
            alert('Tính năng load more đang được phát triển!');
        }
        
        function showNewPostForm(category) {
            console.log('Show new post form for category:', category);
            alert('Form tạo bài viết mới đang được phát triển!');
        }
        
        function showRegistrationForm() {
            alert('Form đăng ký đang được phát triển! Vui lòng liên hệ để tham gia cộng đồng.');
        }
        
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add search functionality if needed
            console.log('Community page loaded');
        });
    </script>
    @endpush
@endsection
