@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@if($page_keywords)
@section('meta_keywords', $page_keywords)
@endif

@section('content')
    <!-- Hero Section -->
    <section class="hero-simple">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('shop.home.index') }}">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('lamgame.blog') }}">Blog</a>
                <span>/</span>
                <span>{{ $blog->name }}</span>
            </div>
            <h1>{{ $blog->name }}</h1>
        </div>
    </section>

    <!-- Blog Detail Section -->
    <section class="blog-detail-section">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Blog Post -->
                    <article class="blog-post-detail">
                        <!-- Post Header -->
                        <header class="post-header">
                            <div class="post-meta">
                                <div class="meta-item">
                                    <i class="fa fa-calendar"></i>
                                    <span>{{ $blog->formatted_date }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-user"></i>
                                    <span>{{ $blog->author }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-clock"></i>
                                    <span>{{ $blog->reading_time }} phút đọc</span>
                                </div>
                                @if($blog->category)
                                <div class="meta-item">
                                    <i class="fa fa-folder"></i>
                                    <a href="{{ route('lamgame.blog', ['category' => $blog->category->slug]) }}">
                                        {{ $blog->category->name }}
                                    </a>
                                </div>
                                @endif
                            </div>

                            <!-- Categories -->
                            @if($postCategories->count() > 0)
                            <div class="post-categories">
                                @foreach($postCategories as $category)
                                <a href="{{ route('lamgame.blog', ['category' => $category->slug]) }}" class="category-badge">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </header>

                        <!-- Featured Image -->
                        <div class="post-featured-image">
                            <img src="{{ $blog->featured_image }}" alt="{{ $blog->name }}" class="img-fluid">
                        </div>

                        <!-- Post Content -->
                        <div class="post-content">
                            <div class="post-excerpt">
                                <p class="lead">{{ $blog->short_description }}</p>
                            </div>
                            
                            <div class="post-body">
                                {!! $blog->description !!}
                            </div>
                        </div>

                        <!-- Post Tags -->
                        @if($postTags->count() > 0)
                        <div class="post-tags">
                            <h4><i class="fa fa-tags"></i> Tags:</h4>
                            <div class="tags-list">
                                @foreach($postTags as $tag)
                                <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" class="tag">
                                    {{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Social Share -->
                        <div class="social-share">
                            <h4><i class="fa fa-share-alt"></i> Chia sẻ bài viết:</h4>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn-share facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->name) }}" target="_blank" class="btn-share twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="btn-share linkedin">
                                    <i class="fab fa-linkedin-in"></i> LinkedIn
                                </a>
                                <button onclick="copyToClipboard('{{ request()->url() }}')" class="btn-share copy">
                                    <i class="fa fa-link"></i> Copy Link
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- Navigation -->
                    <div class="post-navigation">
                        <a href="{{ route('lamgame.blog') }}" class="btn btn-outline">
                            <i class="fa fa-arrow-left"></i> Trở về Blog
                        </a>
                    </div>

                    <!-- Related Posts -->
                    @if($relatedPosts->count() > 0)
                    <div class="related-posts">
                        <h3>Bài viết liên quan</h3>
                        <div class="related-posts-grid">
                            @foreach($relatedPosts as $relatedPost)
                            <article class="related-post">
                                <div class="post-thumbnail">
                                    <a href="/blog/{{ $relatedPost->slug }}">
                                        <img src="{{ $relatedPost->featured_image }}" alt="{{ $relatedPost->name }}">
                                    </a>
                                    @if($relatedPost->category)
                                    <div class="post-category">{{ $relatedPost->category->name }}</div>
                                    @endif
                                </div>
                                <div class="post-info">
                                    <h4>
                                        <a href="/blog/{{ $relatedPost->slug }}">
                                            {{ Str::limit($relatedPost->name, 60) }}
                                        </a>
                                    </h4>
                                    <div class="post-meta">
                                        <span><i class="fa fa-calendar"></i> {{ $relatedPost->formatted_date }}</span>
                                        <span><i class="fa fa-clock"></i> {{ $relatedPost->reading_time }} phút</span>
                                    </div>
                                    <p>{{ Str::limit($relatedPost->short_description, 100) }}</p>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Categories -->
                        <div class="sidebar-block">
                            <h3>Danh mục</h3>
                            <div class="category-list">
                                <a href="{{ route('lamgame.blog') }}" class="category-item">
                                    <span>Tất cả</span>
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('lamgame.blog', ['category' => $category->slug]) }}" class="category-item">
                                    <span>{{ $category->name }}</span>
                                    <span class="count">({{ $category->blogs_count }})</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Posts -->
                        <div class="sidebar-block">
                            <h3>Bài viết mới nhất</h3>
                            <div class="recent-posts">
                                @foreach($recentPosts as $post)
                                <div class="recent-post">
                                    <div class="post-thumb">
                                        <a href="/blog/{{ $post->slug }}">
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->name }}">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h4>
                                            <a href="/blog/{{ $post->slug }}">
                                                {{ Str::limit($post->name, 50) }}
                                            </a>
                                        </h4>
                                        <span class="post-date">{{ $post->formatted_date }}</span>
                                        <span class="post-reading-time">{{ $post->reading_time }} phút đọc</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="sidebar-block">
                            <h3>Tags</h3>
                            <div class="tags-cloud">
                                @foreach($popularTags as $tag)
                                <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" class="tag">
                                    {{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="sidebar-block">
                            <h3>Đăng ký nhận tin</h3>
                            <p>Nhận thông tin cập nhật mới nhất về game development</p>
                            <form class="newsletter-form">
                                <div class="form-group">
                                    <input type="email" placeholder="Email của bạn" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-envelope"></i> Đăng ký
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        /* Hero Simple */
        .hero-simple {
            background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
            color: white;
            padding: 3rem 0;
        }
        
        .breadcrumb {
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: white;
        }
        
        .breadcrumb span {
            margin: 0 0.5rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .hero-simple h1 {
            font-size: 2.2rem;
            margin-bottom: 0;
        }

        /* Blog Detail Section */
        .blog-detail-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .row {
            display: flex;
            gap: 2rem;
        }
        
        .col-lg-8 {
            flex: 0 0 66.66%;
        }
        
        .col-lg-4 {
            flex: 0 0 33.33%;
        }

        /* Blog Post Detail */
        .blog-post-detail {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        /* Post Header */
        .post-header {
            padding: 2rem 2rem 1rem;
        }
        
        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .meta-item i {
            color: #6a4c93;
        }
        
        .meta-item a {
            color: #6a4c93;
            text-decoration: none;
        }
        
        .meta-item a:hover {
            text-decoration: underline;
        }
        
        .post-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .category-badge {
            background: #6a4c93;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background 0.3s;
        }
        
        .category-badge:hover {
            background: #5a3c83;
        }

        /* Featured Image */
        .post-featured-image {
            width: 100%;
        }
        
        .post-featured-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        /* Post Content */
        .post-content {
            padding: 2rem;
        }
        
        .post-excerpt .lead {
            font-size: 1.25rem;
            font-weight: 500;
            color: #6a4c93;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .post-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }
        
        .post-body p {
            margin-bottom: 1.5rem;
        }

        /* Post Tags */
        .post-tags {
            padding: 0 2rem 2rem;
        }
        
        .post-tags h4 {
            color: #6a4c93;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .post-tags .tag {
            padding: 0.25rem 0.75rem;
            background: #f8f9fa;
            color: #6a4c93;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .post-tags .tag:hover {
            background: #6a4c93;
            color: white;
        }

        /* Social Share */
        .social-share {
            padding: 2rem;
            border-top: 1px solid #eee;
        }
        
        .social-share h4 {
            color: #6a4c93;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .share-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-share {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .btn-share:hover {
            opacity: 0.8;
        }
        
        .btn-share.facebook { background: #1877f2; }
        .btn-share.twitter { background: #1da1f2; }
        .btn-share.linkedin { background: #0077b5; }
        .btn-share.copy { background: #666; }

        /* Post Navigation */
        .post-navigation {
            margin-bottom: 3rem;
        }
        
        .btn-outline {
            border: 2px solid #6a4c93;
            color: #6a4c93;
            background: transparent;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-outline:hover {
            background: #6a4c93;
            color: white;
        }

        /* Related Posts */
        .related-posts {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .related-posts h3 {
            color: #6a4c93;
            margin-bottom: 2rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }
        
        .related-posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .related-post {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .related-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .related-post .post-thumbnail {
            position: relative;
        }
        
        .related-post .post-thumbnail img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .related-post .post-category {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #6a4c93;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .related-post .post-info {
            padding: 1.5rem;
        }
        
        .related-post h4 {
            margin-bottom: 0.5rem;
        }
        
        .related-post h4 a {
            color: #333;
            text-decoration: none;
        }
        
        .related-post h4 a:hover {
            color: #6a4c93;
        }
        
        .related-post .post-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            color: #666;
        }
        
        .related-post p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Sidebar */
        .sidebar-block {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .sidebar-block h3 {
            color: #6a4c93;
            margin-bottom: 1rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }
        
        /* Category List */
        .category-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .category-item:hover {
            background: #f8f9fa;
            color: #6a4c93;
        }
        
        .category-item .count {
            color: #666;
            font-size: 0.9rem;
        }

        /* Recent Posts */
        .recent-posts {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .recent-post {
            display: flex;
            gap: 1rem;
        }
        
        .recent-post .post-thumb img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .recent-post .post-info h4 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .recent-post .post-info h4 a {
            color: #333;
            text-decoration: none;
        }
        
        .recent-post .post-info h4 a:hover {
            color: #6a4c93;
        }
        
        .recent-post .post-date,
        .recent-post .post-reading-time {
            display: block;
            font-size: 0.8rem;
            color: #666;
        }

        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .tags-cloud .tag {
            padding: 0.25rem 0.75rem;
            background: #f8f9fa;
            color: #6a4c93;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .tags-cloud .tag:hover {
            background: #6a4c93;
            color: white;
            border-color: #6a4c93;
        }

        /* Newsletter Form */
        .newsletter-form {
            margin-top: 1rem;
        }
        
        .newsletter-form input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: #6a4c93;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
        }
        
        .btn-primary:hover {
            background: #5a3c83;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-lg-8, .col-lg-4 {
                flex: 1;
            }
            
            .hero-simple h1 {
                font-size: 1.8rem;
            }
            
            .post-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .share-buttons {
                flex-direction: column;
            }
            
            .related-posts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link đã được copy vào clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
    @endpush
@endsection
