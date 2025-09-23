@extends('layouts.master')

@section('page_title', $page_title ?? 'Blog & Tin tức - Làm Game')
@section('page_description', $page_description ?? 'Khám phá các bài viết hữu ích về lập trình game, Unity, Unreal Engine và game development')

@section('content')
    <!-- Hero Section -->
    <section class="hero-simple">
        <div class="container">
            <h1>Blog & Tin tức</h1>
            <p class="lead">Kiến thức và cập nhật mới nhất về thế giới game development</p>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="section-content">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Featured Post -->
                    @if($featuredBlog)
                    <div class="featured-post">
                        <div class="post-image">
                            <img src="{{ $featuredBlog->featured_image }}" alt="{{ $featuredBlog->name }}">
                            @if($featuredBlog->category)
                            <div class="post-category">{{ $featuredBlog->category->name }}</div>
                            @endif
                        </div>
                        <div class="post-content">
                            <h2><a href="/blog/{{ $featuredBlog->slug }}">{{ $featuredBlog->name }}</a></h2>
                            <div class="post-meta">
                                <span><i class="fa fa-calendar"></i> {{ $featuredBlog->formatted_date }}</span>
                                <span><i class="fa fa-user"></i> {{ $featuredBlog->author }}</span>
                                <span><i class="fa fa-clock"></i> {{ $featuredBlog->reading_time }} phút đọc</span>
                            </div>
                            <p>{{ $featuredBlog->short_description }}</p>
                            <a href="/blog/{{ $featuredBlog->slug }}" class="btn btn-outline">Đọc tiếp</a>
                        </div>
                    </div>
                    @endif

                    <!-- Blog Posts Grid -->
                    <div class="blog-grid">
                        @forelse($blogs as $blog)
                        @if($featuredBlog && $blog->id == $featuredBlog->id)
                            @continue
                        @endif
                        <div class="blog-post">
                            <div class="post-image">
                                <img src="{{ $blog->featured_image }}" alt="{{ $blog->name }}">
                                @if($blog->category)
                                <div class="post-category">{{ $blog->category->name }}</div>
                                @endif
                            </div>
                            <div class="post-content">
                                <h3><a href="/blog/{{ $blog->slug }}">{{ $blog->name }}</a></h3>
                                <div class="post-meta">
                                    <span><i class="fa fa-calendar"></i> {{ $blog->formatted_date }}</span>
                                    <span><i class="fa fa-clock"></i> {{ $blog->reading_time }} phút</span>
                                </div>
                                <p>{{ $blog->short_description }}</p>
                                <a href="/blog/{{ $blog->slug }}" class="read-more">Đọc tiếp <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                        @empty
                        <div class="no-posts">
                            <p>Chưa có bài viết nào được đăng.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($blogs->hasPages())
                        {{ $blogs->appends(request()->query())->links('pagination.custom') }}
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Search -->
                        <div class="sidebar-block">
                            <h3>Tìm kiếm</h3>
                            <form class="search-form" method="GET" action="{{ route('lamgame.blog') }}">
                                <div class="search-group">
                                    <input type="text" name="search" placeholder="Tìm bài viết..." value="{{ request('search') }}">
                                    <button type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('tag'))
                                    <input type="hidden" name="tag" value="{{ request('tag') }}">
                                @endif
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="sidebar-block">
                            <h3>Danh mục</h3>
                            <div class="category-list">
                                <a href="{{ route('lamgame.blog') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                                    <span>Tất cả</span>
                                    <span class="count">({{ $blogs->total() }})</span>
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('lamgame.blog', ['category' => $category->slug]) }}" 
                                   class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="count">({{ $category->blogs_count }})</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Popular Posts -->
                        <div class="sidebar-block">
                            <h3>Bài viết mới nhất</h3>
                            <div class="popular-posts">
                                @foreach($popularPosts as $post)
                                <div class="popular-post">
                                    <div class="post-thumb">
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->name }}">
                                    </div>
                                    <div class="post-info">
                                        <h4><a href="/blog/{{ $post->slug }}">{{ Str::limit($post->name, 50) }}</a></h4>
                                        <span class="post-date">{{ $post->formatted_date }}</span>
                                        <span class="post-views">{{ $post->reading_time }} phút đọc</span>
                                    </div>
                                </div>
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

                        <!-- Tags -->
                        <div class="sidebar-block">
                            <h3>Tags</h3>
                            <div class="tags-cloud">
                                @foreach($popularTags as $tag)
                                <a href="{{ route('lamgame.blog', ['tag' => $tag->slug]) }}" 
                                   class="tag {{ request('tag') == $tag->slug ? 'active' : '' }}">{{ $tag->name }}</a>
                                @endforeach
                            </div>
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
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-simple h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .lead {
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        /* Section Content */
        .section-content {
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

        /* Featured Post */
        .featured-post {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .featured-post .post-image {
            position: relative;
        }
        
        .featured-post .post-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .post-category {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #6a4c93;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .featured-post .post-content {
            padding: 2rem;
        }
        
        .featured-post h2 {
            margin-bottom: 1rem;
        }
        
        .featured-post h2 a {
            color: #333;
            text-decoration: none;
        }
        
        .featured-post h2 a:hover {
            color: #6a4c93;
        }
        
        .post-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .post-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .post-meta i {
            color: #6a4c93;
        }
        
        .featured-post p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
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
            display: inline-block;
        }
        
        .btn-outline:hover {
            background: #6a4c93;
            color: white;
        }

        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .blog-post {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .blog-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .blog-post .post-image {
            position: relative;
        }
        
        .blog-post .post-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .blog-post .post-content {
            padding: 1.5rem;
        }
        
        .blog-post h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .blog-post h3 a {
            color: #333;
            text-decoration: none;
        }
        
        .blog-post h3 a:hover {
            color: #6a4c93;
        }
        
        .blog-post p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        
        .read-more {
            color: #6a4c93;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .read-more:hover {
            text-decoration: underline;
        }

        /* Blog specific pagination adjustments */
        .pagination-wrapper {
            background: white;
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
        
        /* Search Form */
        .search-group {
            display: flex;
        }
        
        .search-group input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }
        
        .search-group button {
            padding: 0.75rem 1rem;
            background: #6a4c93;
            color: white;
            border: 1px solid #6a4c93;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        .search-group button:hover {
            background: #5a3c83;
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
        
        /* Popular Posts */
        .popular-posts {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .popular-post {
            display: flex;
            gap: 1rem;
        }
        
        .post-thumb img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .post-info h4 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .post-info h4 a {
            color: #333;
            text-decoration: none;
        }
        
        .post-info h4 a:hover {
            color: #6a4c93;
        }
        
        .post-date,
        .post-views {
            display: block;
            font-size: 0.8rem;
            color: #666;
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
        
        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            background: #f8f9fa;
            color: #6a4c93;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .tag:hover,
        .tag.active {
            background: #6a4c93;
            color: white;
            border-color: #6a4c93;
        }
        
        /* Category item active state */
        .category-item.active {
            background: #6a4c93;
            color: white !important;
            border-radius: 4px;
        }
        
        .category-item.active .count {
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* No posts message */
        .no-posts {
            text-align: center;
            padding: 3rem 1rem;
            color: #666;
            font-style: italic;
        }
        
        /* Blog specific styles */

        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-lg-8, .col-lg-4 {
                flex: 1;
            }
            
            .hero-simple h1 {
                font-size: 2rem;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-post .post-image img {
                height: 250px;
            }
            
            .post-meta {
                flex-wrap: wrap;
            }
        }
    </style>
    @endpush
@endsection
