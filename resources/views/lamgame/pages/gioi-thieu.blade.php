@extends('layouts.master')

@section('page_title', $page_title ?? 'Giới thiệu - Làm Game')
@section('page_description', $page_description ?? 'Tìm hiểu về Làm Game - nền tảng học lập trình game hàng đầu Việt Nam')

@section('content')
    <!-- Hero Section -->
    <section class="hero-simple">
        <div class="container">
            <h1>Giới thiệu về Làm Game</h1>
            <p class="lead">Nền tảng học lập trình game hàng đầu Việt Nam</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About Us -->
                    <div class="content-block">
                        <h2>Chúng tôi là ai?</h2>
                        <p>
                            <strong>Làm Game</strong> là một nền tảng giáo dục trực tuyến chuyên cung cấp các khóa học lập trình game chất lượng cao tại Việt Nam. 
                            Được thành lập với sứ mệnh đào tạo thế hệ game developer tài năng, chúng tôi cam kết mang đến những khóa học thực tế 
                            và cập nhật nhất trong lĩnh vực phát triển game.
                        </p>
                        <p>
                            Với đội ngũ giảng viên giàu kinh nghiệm từ các studio game hàng đầu trong và ngoài nước, 
                            Làm Game tự hào là nơi học tập uy tín cho những ai đam mê tạo ra những tựa game độc đáo và hấp dẫn.
                        </p>
                    </div>

                    <!-- Mission -->
                    <div class="content-block">
                        <h2>Sứ mệnh</h2>
                        <div class="mission-grid">
                            <div class="mission-item">
                                <div class="mission-icon">🎯</div>
                                <h4>Đào tạo chất lượng</h4>
                                <p>Cung cấp kiến thức sâu sắc và kỹ năng thực tế để học viên có thể tự tin bước vào ngành game</p>
                            </div>
                            <div class="mission-item">
                                <div class="mission-icon">🚀</div>
                                <h4>Công nghệ tiên tiến</h4>
                                <p>Luôn cập nhật những công nghệ và công cụ mới nhất trong ngành phát triển game</p>
                            </div>
                            <div class="mission-item">
                                <div class="mission-icon">🤝</div>
                                <h4>Cộng đồng mạnh mẽ</h4>
                                <p>Xây dựng cộng đồng game developer Việt Nam năng động và hỗ trợ lẫn nhau</p>
                            </div>
                        </div>
                    </div>

                    <!-- What We Offer -->
                    <div class="content-block">
                        <h2>Chúng tôi cung cấp gì?</h2>
                        <div class="features-list">
                            <div class="feature-item">
                                <i class="fa fa-graduation-cap"></i>
                                <div>
                                    <h4>Khóa học đa dạng</h4>
                                    <p>Từ Unity, Unreal Engine đến Game Design và Mobile Development</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-users"></i>
                                <div>
                                    <h4>Giảng viên chuyên nghiệp</h4>
                                    <p>Đội ngũ giảng viên có kinh nghiệm thực tế tại các studio game hàng đầu</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-laptop"></i>
                                <div>
                                    <h4>Học thực hành</h4>
                                    <p>100% thời gian học là thực hành trên các dự án game thật</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-certificate"></i>
                                <div>
                                    <h4>Chứng chỉ uy tín</h4>
                                    <p>Chứng chỉ được công nhận bởi các công ty game trong nước</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <i class="fa fa-briefcase"></i>
                                <div>
                                    <h4>Hỗ trợ việc làm</h4>
                                    <p>Kết nối với các công ty game và hỗ trợ tìm việc sau khóa học</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="content-block stats-block">
                        <h2>Con số ấn tượng</h2>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Học viên đã tốt nghiệp</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">200+</div>
                                <div class="stat-label">Game đã phát hành</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">95%</div>
                                <div class="stat-label">Tỷ lệ có việc làm</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Đối tác công ty</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Contact Info -->
                        <div class="sidebar-block">
                            <h3>Thông tin liên hệ</h3>
                            <div class="contact-info">
                                <p><i class="fa fa-map-marker"></i> Tòa nhà E.Town Central, 11 Đoàn Văn Bơ, P13, Q4, TP.HCM</p>
                                <p><i class="fa fa-phone"></i> 09.1111.8300</p>
                                <p><i class="fa fa-envelope"></i> salegamevui@gmail.com</p>
                                <p><i class="fa fa-youtube"></i> <a href="https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw" target="_blank">YouTube Channel</a></p>
                            </div>
                        </div>

                        <!-- Popular Courses -->
                        <div class="sidebar-block">
                            <h3>Khóa học phổ biến</h3>
                            <div class="popular-courses">
                                <a href="{{ route('lamgame.course-detail', 'unity') }}" class="course-link">
                                    <i class="fa fa-gamepad"></i> Unity Game Development
                                </a>
                                <a href="{{ route('lamgame.course-detail', 'unreal') }}" class="course-link">
                                    <i class="fa fa-cube"></i> Unreal Engine
                                </a>
                                <a href="{{ route('lamgame.course-detail', 'game-design') }}" class="course-link">
                                    <i class="fa fa-paint-brush"></i> Game Design
                                </a>
                                <a href="{{ route('lamgame.course-detail', 'mobile') }}" class="course-link">
                                    <i class="fa fa-mobile-alt"></i> Mobile Game Development
                                </a>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="sidebar-block cta-block">
                            <h3>Sẵn sàng bắt đầu?</h3>
                            <p>Tham gia cộng đồng game developer Việt Nam ngay hôm nay!</p>
                            <a href="{{ route('lamgame.lien-he') }}" class="btn btn-primary">Liên hệ tư vấn</a>
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

        /* Content Blocks */
        .content-block {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .content-block h2 {
            color: #6a4c93;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }

        /* Mission Grid */
        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .mission-item {
            text-align: center;
            padding: 1.5rem;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
        }
        
        .mission-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Features List */
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .feature-item i {
            font-size: 1.5rem;
            color: #6a4c93;
            margin-top: 0.25rem;
        }
        
        .feature-item h4 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        /* Stats */
        .stats-block {
            background: #6a4c93;
            color: white;
        }
        
        .stats-block h2 {
            color: white;
            border-bottom-color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
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
        
        .contact-info p {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .contact-info i {
            color: #6a4c93;
            width: 20px;
        }
        
        .course-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            text-decoration: none;
            color: #333;
            border: 1px solid #e1e5e9;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .course-link:hover {
            background: #f8f9fa;
            border-color: #6a4c93;
            color: #6a4c93;
        }
        
        .cta-block {
            background: linear-gradient(135deg, #6a4c93, #9b59b6);
            color: white;
            text-align: center;
        }
        
        .cta-block h3 {
            color: white;
            border-bottom-color: white;
        }
        
        .cta-block .btn {
            background: white;
            color: #6a4c93;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 1rem;
        }
        
        .cta-block .btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
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
                font-size: 2rem;
            }
            
            .mission-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    @endpush
@endsection
