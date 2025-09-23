<!-- Source Game Header Section -->
<section style="background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 2rem 0;">
    <div class="container">
        <div class="source-game-header">
            <div class="game-meta">
                <div class="game-badges">
                    @if($sourceCategory ?? null)
                        <span class="badge badge-category">📱 {{ $sourceCategory }}</span>
                    @endif
                    @if($gameEngine ?? null)
                        <span class="badge badge-engine">⚙️ {{ $gameEngine }}</span>
                    @endif
                    @if($programmingLanguage ?? null)
                        <span class="badge badge-lang">💻 {{ $programmingLanguage }}</span>
                    @endif
                </div>
                <h1 class="game-title">{{ $product->name }}</h1>
                <div class="game-stats">
                    @if($rating ?? null)
                        <div class="stat-item">
                            <span class="stat-icon">⭐</span>
                            <span class="stat-value">{{ $rating }}</span>
                            <span class="stat-label">Rating</span>
                        </div>
                    @endif
                    <div class="stat-item">
                        <span class="stat-icon">⬇️</span>
                        <span class="stat-value">{{ $downloads ?? '0' }}</span>
                        <span class="stat-label">Downloads</span>
                    </div>
                    @if($fileSize ?? null)
                        <div class="stat-item">
                            <span class="stat-icon">📦</span>
                            <span class="stat-value">{{ $fileSize }}</span>
                            <span class="stat-label">File Size</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Product Section -->
<section style="padding: 3rem 0; background: white;">
    <div class="container">
        <v-product>
            <!-- Loading State -->
            <div class="source-game-loading">
                <div class="loading-gallery">
                    <div class="shimmer main-preview" style="height: 300px; border-radius: 10px;"></div>
                    <div class="loading-thumbnails" style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="shimmer thumbnail-preview" style="width: 80px; height: 60px; border-radius: 10px;"></div>
                        @endfor
                    </div>
                </div>
                <div class="loading-details">
                    <div class="shimmer title-preview" style="height: 40px; width: 70%; border-radius: 10px;"></div>
                    <div class="shimmer price-preview" style="height: 50px; width: 50%; margin-top: 1rem; border-radius: 10px;"></div>
                    <div class="shimmer description-preview" style="height: 80px; width: 100%; margin-top: 1rem; border-radius: 10px;"></div>
                    <div class="shimmer actions-preview" style="height: 60px; width: 80%; margin-top: 1rem; border-radius: 10px;"></div>
                </div>
                <div class="loading-info">
                    <div class="shimmer info-preview" style="height: 200px; width: 100%; border-radius: 10px;"></div>
                </div>
            </div>
        </v-product>
    </div>
</section>

<!-- Enhanced Product Tabs Section -->
<section style="background: #f8f9fa; padding: 3rem 0;">
    <div class="container">
        <div class="source-game-tabs">
            <div class="tab-navigation">
                <button class="tab-button active" onclick="showTab(event, 'description')">
                    <span class="tab-icon">📖</span>
                    <span class="tab-text">Mô tả chi tiết</span>
                </button>
                <button class="tab-button" onclick="showTab(event, 'features')">
                    <span class="tab-icon">✨</span>
                    <span class="tab-text">Tính năng</span>
                </button>
                <button class="tab-button" onclick="showTab(event, 'technical')">
                    <span class="tab-icon">⚙️</span>
                    <span class="tab-text">Kỹ thuật</span>
                </button>
                <button class="tab-button" onclick="showTab(event, 'installation')">
                    <span class="tab-icon">🔧</span>
                    <span class="tab-text">Cài đặt</span>
                </button>
                <button class="tab-button" onclick="showTab(event, 'reviews')">
                    <span class="tab-icon">⭐</span>
                    <span class="tab-text">Đánh giá ({{ $reviewHelper->getTotalFeedback($product) }})</span>
                </button>
            </div>
            
            <div class="tab-panels">
                <!-- Description Tab -->
                <div id="description-panel" class="tab-panel active">
                    <div class="panel-content">
                        <h3>📖 Mô tả chi tiết</h3>
                        <div class="description-content">
                            {!! $product->description !!}
                        </div>
                        
                        <!-- What's Included Section -->
                        <div class="whats-included">
                            <h4>📦 Bao gồm trong gói:</h4>
                            <ul class="included-list">
                                <li>✅ Toàn bộ source code</li>
                                <li>✅ Tài liệu hướng dẫn</li>
                                <li>✅ Asset và resources</li>
                                <li>✅ Hỗ trợ kỹ thuật cơ bản</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Features Tab -->
                <div id="features-panel" class="tab-panel">
                    <div class="panel-content">
                        <h3>✨ Tính năng chính</h3>
                        <div class="features-grid">
                            <div class="feature-card">
                                <div class="feature-icon">🎮</div>
                                <h4>Gameplay hoàn chỉnh</h4>
                                <p>Cơ chế chơi đầy đủ và mượt mà</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">🎨</div>
                                <h4>UI/UX chuyên nghiệp</h4>
                                <p>Giao diện đẹp và dễ sử dụng</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">📱</div>
                                <h4>Responsive design</h4>
                                <p>Hỗ trợ nhiều thiết bị và kích thước màn hình</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">⚡</div>
                                <h4>Tối ưu hiệu suất</h4>
                                <p>Code sạch, chạy nhanh và ổn định</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Tab -->
                <div id="technical-panel" class="tab-panel">
                    <div class="panel-content">
                        <h3>⚙️ Thông số kỹ thuật</h3>
                        <div class="tech-specs">
                            @if($gameEngine ?? null)
                                <div class="spec-row">
                                    <span class="spec-label">Game Engine:</span>
                                    <span class="spec-value">{{ $gameEngine }}</span>
                                </div>
                            @endif
                            @if($programmingLanguage ?? null)
                                <div class="spec-row">
                                    <span class="spec-label">Programming Language:</span>
                                    <span class="spec-value">{{ $programmingLanguage }}</span>
                                </div>
                            @endif
                            @if($fileSize ?? null)
                                <div class="spec-row">
                                    <span class="spec-label">File Size:</span>
                                    <span class="spec-value">{{ $fileSize }}</span>
                                </div>
                            @endif
                            <div class="spec-row">
                                <span class="spec-label">Product Type:</span>
                                <span class="spec-value">Downloadable Source Code</span>
                            </div>
                            <div class="spec-row">
                                <span class="spec-label">License:</span>
                                <span class="spec-value">Commercial Use Allowed</span>
                            </div>
                        </div>
                        
                        <!-- System Requirements -->
                        <div class="system-requirements">
                            <h4>💻 Yêu cầu hệ thống:</h4>
                            <div class="requirements-grid">
                                <div class="req-column">
                                    <h5>Minimum:</h5>
                                    <ul>
                                        <li>{{ $gameEngine ?? 'Game Engine' }} latest version</li>
                                        <li>4GB RAM</li>
                                        <li>2GB free disk space</li>
                                        <li>Basic programming knowledge</li>
                                    </ul>
                                </div>
                                <div class="req-column">
                                    <h5>Recommended:</h5>
                                    <ul>
                                        <li>{{ $gameEngine ?? 'Game Engine' }} Pro version</li>
                                        <li>8GB+ RAM</li>
                                        <li>5GB+ free disk space</li>
                                        <li>Intermediate programming skills</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Installation Tab -->
                <div id="installation-panel" class="tab-panel">
                    <div class="panel-content">
                        <h3>🔧 Hướng dẫn cài đặt</h3>
                        <div class="installation-steps">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h4>Tải xuống file</h4>
                                    <p>Sau khi thanh toán, tải file source code từ email hoặc tài khoản của bạn.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h4>Giải nén file</h4>
                                    <p>Giải nén file đã tải về một thư mục trên máy tính của bạn.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h4>Mở {{ $gameEngine ?? 'Game Engine' }}</h4>
                                    <p>Khởi động {{ $gameEngine ?? 'game engine' }} và mở project từ thư mục đã giải nén.</p>
                                </div>
                            </div>
                            <div class="step">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h4>Build và test</h4>
                                    <p>Build project và test game để đảm bảo mọi thứ hoạt động tốt.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Support Info -->
                        <div class="support-info">
                            <h4>🆘 Cần hỗ trợ?</h4>
                            <p>Nếu gặp vấn đề trong quá trình cài đặt, hãy liên hệ với chúng tôi:</p>
                            <div class="support-contacts">
                                <a href="mailto:support@emsaigon.com" class="support-link">
                                    <span class="support-icon">✉️</span>
                                    <span>support@emsaigon.com</span>
                                </a>
                                <a href="tel:0908123456" class="support-link">
                                    <span class="support-icon">📞</span>
                                    <span>0908 123 456</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-panel" class="tab-panel">
                    <div class="panel-content">
                        <h3>⭐ Đánh giá từ khách hàng</h3>
                        @if ($reviewHelper->getTotalFeedback($product))
                            <div class="reviews-overview">
                                <div class="rating-summary">
                                    <div class="rating-score-large">{{ number_format($avgRatings, 1) }}</div>
                                    <div class="rating-stars-large">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $avgRatings ? 'filled' : 'empty' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="rating-text">Dựa trên {{ $reviewHelper->getTotalFeedback($product) }} đánh giá</p>
                                </div>
                            </div>
                            <div class="reviews-placeholder">
                                <p>💭 Chi tiết đánh giá sẽ được hiển thị ở đây trong tương lai.</p>
                            </div>
                        @else
                            <div class="no-reviews-state">
                                <div class="no-reviews-icon">💭</div>
                                <h4>Chưa có đánh giá nào</h4>
                                <p>Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@pushOnce('styles')
    <style>
        /* Source Game Header Styles */
        .source-game-header {
            text-align: center;
        }
        
        .game-badges {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .game-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .game-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            min-width: 100px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-icon {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Enhanced Product Tabs */
        .source-game-tabs {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .tab-navigation {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            flex-wrap: wrap;
        }
        
        .tab-button {
            background: none;
            border: none;
            padding: 1.5rem 1rem;
            cursor: pointer;
            font-size: 1rem;
            color: #666;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            flex: 1;
            min-width: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tab-button.active {
            color: #2c5f41;
            background: white;
            border-bottom-color: #2c5f41;
            font-weight: 600;
            box-shadow: inset 0 -3px 0 #2c5f41;
        }
        
        .tab-button:hover {
            color: #2c5f41;
            background: rgba(44, 95, 65, 0.05);
        }
        
        .tab-icon {
            font-size: 1.2rem;
        }
        
        .tab-text {
            font-size: 0.9rem;
        }
        
        .tab-panels {
            min-height: 400px;
        }
        
        .tab-panel {
            display: none;
        }
        
        .tab-panel.active {
            display: block;
        }
        
        .panel-content {
            padding: 2.5rem;
        }
        
        .panel-content h3 {
            color: #2c5f41;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .feature-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(44, 95, 65, 0.1);
            border-color: #2c5f41;
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .feature-card h4 {
            color: #2c5f41;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .feature-card p {
            color: #666;
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Technical Specs */
        .tech-specs {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .spec-row:last-child {
            border-bottom: none;
        }
        
        .spec-label {
            font-weight: 600;
            color: #2c5f41;
        }
        
        .spec-value {
            color: #666;
            font-weight: 500;
        }
        
        /* System Requirements */
        .system-requirements {
            margin-top: 2rem;
        }
        
        .system-requirements h4 {
            color: #2c5f41;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .requirements-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .req-column {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
        }
        
        .req-column h5 {
            color: #2c5f41;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .req-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .req-column li {
            padding: 0.5rem 0;
            color: #666;
            border-bottom: 1px solid #dee2e6;
        }
        
        .req-column li:last-child {
            border-bottom: none;
        }
        
        .req-column li::before {
            content: "✓";
            color: #2c5f41;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        
        /* Installation Steps */
        .installation-steps {
            margin-bottom: 2rem;
        }
        
        .step {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: flex-start;
        }
        
        .step-number {
            background: #2c5f41;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .step-content h4 {
            color: #2c5f41;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .step-content p {
            color: #666;
            margin: 0;
            line-height: 1.6;
        }
        
        /* Support Info */
        .support-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            border-radius: 15px;
            border-left: 5px solid #2c5f41;
        }
        
        .support-info h4 {
            color: #2c5f41;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .support-contacts {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .support-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: white;
            border-radius: 10px;
            text-decoration: none;
            color: #2c5f41;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid #e9ecef;
        }
        
        .support-link:hover {
            background: #2c5f41;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 95, 65, 0.3);
        }
        
        .support-icon {
            font-size: 1.1rem;
        }
        
        /* What's Included */
        .whats-included {
            margin-top: 2rem;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
        }
        
        .whats-included h4 {
            color: #2c5f41;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .included-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .included-list li {
            padding: 0.5rem 0;
            color: #666;
            border-bottom: 1px solid #dee2e6;
            font-weight: 500;
        }
        
        .included-list li:last-child {
            border-bottom: none;
        }
        
        /* Reviews */
        .reviews-overview {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .rating-summary {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            display: inline-block;
        }
        
        .rating-score-large {
            font-size: 3rem;
            font-weight: 800;
            color: #2c5f41;
            margin-bottom: 0.5rem;
        }
        
        .rating-stars-large {
            margin-bottom: 1rem;
        }
        
        .rating-stars-large .star {
            font-size: 1.5rem;
            margin: 0 0.1rem;
        }
        
        .rating-stars-large .star.filled {
            color: #ffc107;
        }
        
        .rating-stars-large .star.empty {
            color: #ddd;
        }
        
        .rating-text {
            color: #666;
            margin: 0;
            font-size: 1.1rem;
        }
        
        .no-reviews-state {
            text-align: center;
            padding: 3rem 2rem;
        }
        
        .no-reviews-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        
        .no-reviews-state h4 {
            color: #2c5f41;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .no-reviews-state p {
            color: #666;
            margin: 0;
        }
        
        .reviews-placeholder {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        /* Shimmer Effects */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .source-game-loading {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .game-title {
                font-size: 2rem;
            }
            
            .game-stats {
                gap: 1rem;
            }
            
            .stat-item {
                min-width: 80px;
                padding: 0.75rem;
            }
            
            .tab-navigation {
                flex-direction: column;
            }
            
            .tab-button {
                flex: none;
                flex-direction: row;
                justify-content: center;
            }
            
            .panel-content {
                padding: 1.5rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .requirements-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .support-contacts {
                flex-direction: column;
            }
            
            .game-badges {
                gap: 0.5rem;
            }
            
            .badge {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
            
            .source-game-loading {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <!-- Tab JavaScript -->
    <script>
        function showTab(event, tabName) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected panel and mark button as active
            document.getElementById(tabName + '-panel').classList.add('active');
            event.target.closest('.tab-button').classList.add('active');
        }
    </script>
@endpushOnce
