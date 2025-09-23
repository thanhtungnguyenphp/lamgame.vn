@extends('layouts.master')

@section('page_title', $page_title ?? 'Source Game - Kho Mã Nguồn Game - Làm Game')

@section('page_description', $page_description ?? 'Tổng hợp các source code game từ cổ điển đến hiện đại. Tải miễn phí để học tập và nghiên cứu.')

@section('content')
    <!-- Hero Section -->
    <section class="source-hero">
        <div class="container">
            <div class="hero-content">
                <h1>🎮 Kho Source Game</h1>
                <p class="hero-subtitle">
                    Tổng hợp những source code game từ cổ điển đến hiện đại. 
                    Tải về miễn phí để học tập, nghiên cứu và phát triển kỹ năng game development.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Source codes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50k+</div>
                        <div class="stat-label">Lượt tải</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Game engines</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-form">
                <h2>Tìm kiếm Source Game</h2>
                <div class="search-controls">
                    <div class="search-input-group">
                        <input type="text" id="searchInput" placeholder="Nhập tên game, engine, hoặc từ khóa...">
                        <button class="btn btn-primary" onclick="searchSources()">
                            <i class="fa fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                    
                    <div class="filter-controls">
                        <select id="engineFilter">
                            <option value="">Tất cả Engine</option>
                            <option value="unity">Unity</option>
                            <option value="unreal">Unreal Engine</option>
                            <option value="godot">Godot</option>
                            <option value="cocos">Cocos2D</option>
                        </select>
                        
                        <select id="languageFilter">
                            <option value="">Tất cả Language</option>
                            <option value="csharp">C#</option>
                            <option value="cpp">C++</option>
                            <option value="javascript">JavaScript</option>
                            <option value="python">Python</option>
                        </select>
                        
                        <select id="sortBy">
                            <option value="popular">Phổ biến nhất</option>
                            <option value="newest">Mới nhất</option>
                            <option value="rating">Đánh giá cao</option>
                            <option value="downloads">Nhiều tải nhất</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Featured Sources Section -->
    <section class="featured-sources">
        <div class="container">
            <h2 class="section-title">Source Games Nổi Bật</h2>
            <div class="sources-grid">
                @foreach($featuredSources as $source)
                <div class="source-card" data-category="{{ $source['category'] }}">
                    <div class="source-image">
                        <img src="{{ $source['preview_image'] }}" alt="{{ $source['title'] }}" />
                        <div class="source-overlay">
                            <div class="source-engine">{{ $source['engine'] }}</div>
                            <div class="source-language">{{ $source['language'] }}</div>
                        </div>
                    </div>
                    
                    <div class="source-content">
                        @if(isset($source['href']) && $source['href'])
                            <h3 class="source-title"><a href="{{ $source['href'] }}" title="{{ $source['title'] }}">{{ $source['title'] }}</a></h3>
                        @else
                            <h3 class="source-title">{{ $source['title'] }}</h3>
                        @endif
                        <p class="source-description">{{ $source['description'] }}</p>
                        
                        <div class="source-meta">
                            <div class="meta-item">
                                <span class="meta-icon">📁</span>
                                <span>{{ $source['size'] }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">⭐</span>
                                <span>{{ $source['rating'] }}/5</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-icon">⬇️</span>
                                <span>{{ $source['downloads'] }}</span>
                            </div>
                        </div>
                        
                        <div class="source-actions">
                            @if(isset($source['href']) && $source['href'])
                                <a href="{{ $source['href'] }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i> Xem chi tiết
                                </a>
                                <button class="btn btn-outline" onclick="downloadSource({{ $source['id'] }})">
                                    <i class="fa fa-download"></i> Tải về
                                </button>
                            @else
                                <button class="btn btn-primary" onclick="downloadSource({{ $source['id'] }})">
                                    <i class="fa fa-download"></i> Tải về
                                </button>
                                <button class="btn btn-outline" onclick="previewSource({{ $source['id'] }})">
                                    <i class="fa fa-eye"></i> Xem trước
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- How to Use Section -->
    <section class="how-to-use">
        <div class="container">
            <h2 class="section-title">Cách sử dụng Source Game</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3>Tìm kiếm</h3>
                    <p>Duyệt qua danh mục hoặc tìm kiếm source game phù hợp với mục đích học tập của bạn.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3>Tải về</h3>
                    <p>Nhấn nút "Tải về" để download source code hoàn chỉnh về máy tính của bạn.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3>Nghiên cứu</h3>
                    <p>Mở project trong Unity/Unreal, phân tích code và học hỏi các kỹ thuật lập trình game.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3>Thực hành</h3>
                    <p>Chỉnh sửa, mở rộng tính năng hoặc sử dụng làm base cho dự án game của riêng bạn.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contribution Section -->
    <section class="contribution-section">
        <div class="container">
            <div class="contribution-content">
                <h2>Đóng góp Source Game</h2>
                <p>Bạn có source game hay muốn chia sẻ với cộng đồng? Hãy gửi cho chúng tôi!</p>
                <div class="contribution-benefits">
                    <div class="benefit">
                        <span class="benefit-icon">🏆</span>
                        <span>Được ghi nhận tác giả</span>
                    </div>
                    <div class="benefit">
                        <span class="benefit-icon">🎯</span>
                        <span>Giúp đỡ cộng đồng</span>
                    </div>
                    <div class="benefit">
                        <span class="benefit-icon">✨</span>
                        <span>Nâng cao uy tín</span>
                    </div>
                </div>
                <button class="btn btn-primary btn-large" onclick="showContributionForm()">
                    <i class="fa fa-plus"></i> Đóng góp Source Game
                </button>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        /* Mobile-first approach for hero section */
        .source-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0 30px;
            text-align: center;
        }
        
        .source-hero h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.3;
        }
        
        .hero-subtitle {
            font-size: 1rem;
            max-width: 90%;
            margin: 0 auto 25px;
            opacity: 0.9;
            line-height: 1.5;
            padding: 0 15px;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
            min-width: 80px;
        }
        
        .stat-number {
            font-size: 1.6rem;
            font-weight: bold;
            color: #ffd700;
            line-height: 1.2;
        }
        
        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 3px;
        }
        
        /* Desktop styles */
        @media (min-width: 769px) {
            .source-hero {
                padding: 80px 0;
            }
            
            .source-hero h1 {
                font-size: 3rem;
                margin-bottom: 20px;
                font-weight: 800;
            }
            
            .hero-subtitle {
                font-size: 1.3rem;
                max-width: 700px;
                margin: 0 auto 40px;
                padding: 0;
            }
            
            .hero-stats {
                gap: 60px;
                margin-top: 50px;
                flex-wrap: nowrap;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .stat-label {
                font-size: 1rem;
                margin-top: 5px;
            }
        }
        
        /* Mobile-first section padding */
        .search-section, .featured-sources, .how-to-use, .contribution-section {
            padding: 30px 0;
        }
        
        /* Desktop section padding */
        @media (min-width: 769px) {
            .search-section, .featured-sources, .how-to-use, .contribution-section {
                padding: 50px 0;
            }
        }
        
        .sources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .source-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .source-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .source-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .source-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .source-card:hover .source-image img {
            transform: scale(1.05);
        }
        
        .source-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        
        .source-engine, .source-language {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .source-content {
            padding: 25px;
        }
        
        .source-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .source-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .source-title a:hover {
            color: #667eea;
            text-decoration: none;
        }
        
        .source-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        
        .source-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .source-actions {
            display: flex;
            gap: 10px;
        }
        
        .source-actions .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-outline:hover {
            background: #667eea;
            color: white;
        }
        
        .search-section {
            background: white;
        }
        
        .search-form {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 0 15px;
        }
        
        .search-form h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5rem;
        }
        
        /* Desktop search form styles */
        @media (min-width: 769px) {
            .search-form {
                padding: 0;
            }
            
            .search-form h2 {
                margin-bottom: 30px;
                font-size: 2rem;
            }
        }
        
        .search-input-group {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .search-input-group input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .search-input-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .filter-controls {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .filter-controls select {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-size: 0.9rem;
        }
        
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .step-card {
            text-align: center;
            padding: 40px 20px;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .step-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .contribution-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }
        
        .contribution-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .contribution-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .contribution-benefits {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .benefit {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }
        
        .benefit-icon {
            font-size: 1.5rem;
        }
        
        .btn-large {
            padding: 15px 40px;
            font-size: 1.2rem;
        }
        
        /* Mobile and tablet responsive design */
        @media (max-width: 768px) {
            .search-input-group {
                flex-direction: column;
            }
            
            .filter-controls {
                flex-direction: column;
            }
            
            .sources-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .source-card {
                margin: 0 10px;
            }
            
            .contribution-benefits {
                flex-direction: column;
                gap: 20px;
            }
            
            .steps-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        @media (max-width: 480px) {
            /* Extra small devices optimization */
            .source-hero {
                padding: 30px 0 20px;
            }
            
            .source-hero h1 {
                font-size: 1.6rem;
                margin-bottom: 12px;
            }
            
            .hero-subtitle {
                font-size: 0.9rem;
                margin-bottom: 20px;
            }
            
            .hero-stats {
                margin-top: 15px;
                gap: 15px;
            }
            
            .stat-number {
                font-size: 1.4rem;
            }
            
            .search-form {
                padding: 0 10px;
            }
            
            .search-form h2 {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }
            
            .filter-controls select {
                width: 100%;
            }
            
            .source-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .source-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .meta-item {
                justify-content: center;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        }
        
        function downloadSource(id) {
            console.log('Download source:', id);
            // Implement download logic
            alert('Tính năng tải xuống đang được phát triển!');
        }
        
        function previewSource(id) {
            console.log('Preview source:', id);
            // Implement preview logic
            alert('Tính năng xem trước đang được phát triển!');
        }
        
        function searchSources() {
            const searchTerm = document.getElementById('searchInput').value;
            console.log('Search for:', searchTerm);
            // Implement search logic
        }
        
        function showContributionForm() {
            alert('Form đóng góp đang được phát triển! Vui lòng liên hệ qua email để gửi source game.');
        }
        
        // Filter controls
        document.addEventListener('DOMContentLoaded', function() {
            const engineFilter = document.getElementById('engineFilter');
            const languageFilter = document.getElementById('languageFilter');
            const sortBy = document.getElementById('sortBy');
            
            [engineFilter, languageFilter, sortBy].forEach(select => {
                select.addEventListener('change', function() {
                    console.log('Filter changed:', this.id, this.value);
                    // Implement filtering logic
                });
            });
        });
    </script>
    @endpush
@endsection
