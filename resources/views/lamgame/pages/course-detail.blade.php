@extends('layouts.master')

@section('page_title', $page_title ?? 'Chi tiết khóa học - Làm Game')
@section('page_description', $page_description ?? 'Thông tin chi tiết về khóa học lập trình game')

@section('content')
    <!-- Hero Section with Course Info -->
    <section class="course-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="course-info">
                        <div class="course-badge">{{ $course['category'] ?? 'Unity' }}</div>
                        <h1>{{ $course['title'] ?? 'Unity Game Development - Từ cơ bản đến nâng cao' }}</h1>
                        <p class="course-description">{{ $course['description'] ?? 'Học cách tạo game 2D và 3D với Unity Engine từ những kiến thức cơ bản đến các kỹ thuật nâng cao' }}</p>
                        
                        <div class="course-meta">
                            <div class="meta-item">
                                <i class="fa fa-clock-o"></i>
                                <span>{{ $course['duration'] ?? '12 tuần' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fa fa-play-circle-o"></i>
                                <span>{{ $course['lessons'] ?? '48' }} bài học</span>
                            </div>
                            <div class="meta-item">
                                <i class="fa fa-users"></i>
                                <span>{{ $course['students'] ?? '1,234' }} học viên</span>
                            </div>
                            <div class="meta-item">
                                <i class="fa fa-star"></i>
                                <span>{{ $course['rating'] ?? '4.8' }}/5 ({{ $course['reviews'] ?? '156' }} đánh giá)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="course-card">
                        <div class="course-image">
                            <img src="{{ $course['image'] ?? 'https://via.placeholder.com/400x300?text=Unity+Course' }}" alt="Unity Course">
                            <div class="play-button">
                                <i class="fa fa-play"></i>
                            </div>
                        </div>
                        
                        <div class="course-price">
                            @if(isset($course['original_price']))
                                <span class="original-price">{{ number_format($course['original_price']) }}đ</span>
                            @endif
                            <span class="current-price">{{ $course['price'] ?? 'Miễn phí' }}</span>
                        </div>
                        
                        <div class="course-actions">
                            <a href="#" class="btn btn-primary btn-enroll">
                                <i class="fa fa-graduation-cap"></i> Đăng ký ngay
                            </a>
                            <a href="#" class="btn btn-outline btn-wishlist">
                                <i class="fa fa-heart-o"></i> Yêu thích
                            </a>
                        </div>
                        
                        <div class="course-includes">
                            <h4>Khóa học bao gồm:</h4>
                            <ul>
                                <li><i class="fa fa-check"></i> Video HD chất lượng cao</li>
                                <li><i class="fa fa-check"></i> Tài liệu PDF đầy đủ</li>
                                <li><i class="fa fa-check"></i> Source code mẫu</li>
                                <li><i class="fa fa-check"></i> Hỗ trợ 1-1 từ instructor</li>
                                <li><i class="fa fa-check"></i> Certificate hoàn thành</li>
                                <li><i class="fa fa-check"></i> Truy cập trọn đời</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Content -->
    <section class="course-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="course-tabs">
                        <nav class="tab-nav">
                            <a href="#overview" class="tab-link active">Tổng quan</a>
                            <a href="#curriculum" class="tab-link">Chương trình</a>
                            <a href="#instructor" class="tab-link">Giảng viên</a>
                            <a href="#reviews" class="tab-link">Đánh giá</a>
                        </nav>

                        <!-- Overview Tab -->
                        <div id="overview" class="tab-content active">
                            <div class="content-section">
                                <h2>Bạn sẽ học được gì?</h2>
                                <div class="learning-objectives">
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Nắm vững Unity Interface và các tool cơ bản</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Tạo game 2D với sprites, animation và physics</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Phát triển game 3D với models, textures và lighting</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Lập trình C# cho game logic và mechanics</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>UI/UX design cho game interface</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Audio implementation và sound effects</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Build và deploy game lên các platform</span>
                                    </div>
                                    <div class="objective-item">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Optimization và performance tuning</span>
                                    </div>
                                </div>
                            </div>

                            <div class="content-section">
                                <h2>Mô tả khóa học</h2>
                                <div class="course-description">
                                    <p>Unity Game Development là khóa học toàn diện dành cho những ai muốn bắt đầu sự nghiệp trong ngành phát triển game. Khóa học được thiết kế từ cơ bản đến nâng cao, phù hợp với cả người mới bắt đầu và những developer có kinh nghiệm muốn nâng cao kỹ năng Unity.</p>
                                    
                                    <p>Trong 12 tuần học, bạn sẽ được hướng dẫn step-by-step từ việc làm quen với Unity Interface, học C# programming, tạo game 2D/3D, cho đến việc optimize và publish game lên các platform như PC, Mobile, và Web.</p>
                                    
                                    <p>Đặc biệt, khóa học tập trung vào thực hành với các project thực tế. Bạn sẽ tạo ra ít nhất 5 game hoàn chỉnh bao gồm: Platform Game, RPG Game, Racing Game, Puzzle Game, và Multiplayer Game.</p>
                                    
                                    <p>Sau khi hoàn thành khóa học, bạn sẽ có đủ kiến thức và kỹ năng để tự tin ứng tuyển vào các vị trí Unity Developer tại các công ty game, hoặc tự phát triển game indie của riêng mình.</p>
                                </div>
                            </div>

                            <div class="content-section">
                                <h2>Yêu cầu</h2>
                                <div class="requirements">
                                    <ul>
                                        <li>Máy tính Windows/Mac có cấu hình trung bình trở lên</li>
                                        <li>Đã cài đặt Unity Hub và Unity Editor (hướng dẫn chi tiết trong khóa học)</li>
                                        <li>Kiến thức cơ bản về máy tính và internet</li>
                                        <li>Không cần kiến thức lập trình trước (sẽ dạy từ đầu)</li>
                                        <li>Tinh thần học hỏi và kiên trì</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Curriculum Tab -->
                        <div id="curriculum" class="tab-content">
                            <div class="curriculum-section">
                                <h2>Chương trình học chi tiết</h2>
                                
                                <div class="chapter">
                                    <div class="chapter-header">
                                        <h3><i class="fa fa-play-circle"></i> Chapter 1: Unity Fundamentals</h3>
                                        <span class="chapter-info">8 bài học • 2h 30p</span>
                                    </div>
                                    <div class="chapter-content">
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-play-circle-o"></i>
                                                Giới thiệu Unity và Game Development
                                            </div>
                                            <span class="lesson-duration">15:30</span>
                                        </div>
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-play-circle-o"></i>
                                                Cài đặt Unity Hub và Unity Editor
                                            </div>
                                            <span class="lesson-duration">12:45</span>
                                        </div>
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-play-circle-o"></i>
                                                Unity Interface và Windows
                                            </div>
                                            <span class="lesson-duration">20:15</span>
                                        </div>
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-file-text-o"></i>
                                                Assets, GameObjects và Components
                                            </div>
                                            <span class="lesson-duration">18:20</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="chapter">
                                    <div class="chapter-header">
                                        <h3><i class="fa fa-play-circle"></i> Chapter 2: C# Programming Basics</h3>
                                        <span class="chapter-info">10 bài học • 3h 45p</span>
                                    </div>
                                    <div class="chapter-content">
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-play-circle-o"></i>
                                                C# Variables và Data Types
                                            </div>
                                            <span class="lesson-duration">22:30</span>
                                        </div>
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-play-circle-o"></i>
                                                Functions và Methods
                                            </div>
                                            <span class="lesson-duration">25:15</span>
                                        </div>
                                        <div class="lesson">
                                            <div class="lesson-title">
                                                <i class="fa fa-lock"></i>
                                                Classes và Objects
                                            </div>
                                            <span class="lesson-duration">30:45</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="chapter">
                                    <div class="chapter-header">
                                        <h3><i class="fa fa-play-circle"></i> Chapter 3: 2D Game Development</h3>
                                        <span class="chapter-info">12 bài học • 4h 20p</span>
                                    </div>
                                </div>

                                <div class="chapter">
                                    <div class="chapter-header">
                                        <h3><i class="fa fa-play-circle"></i> Chapter 4: 3D Game Development</h3>
                                        <span class="chapter-info">15 bài học • 5h 15p</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructor Tab -->
                        <div id="instructor" class="tab-content">
                            <div class="instructor-section">
                                <div class="instructor-card">
                                    <div class="instructor-avatar">
                                        <img src="https://via.placeholder.com/120x120?text=Instructor" alt="Instructor">
                                    </div>
                                    <div class="instructor-info">
                                        <h3>Nguyễn Văn A</h3>
                                        <p class="instructor-title">Senior Unity Developer</p>
                                        <div class="instructor-stats">
                                            <div class="stat">
                                                <i class="fa fa-star"></i>
                                                <span>4.9 Rating</span>
                                            </div>
                                            <div class="stat">
                                                <i class="fa fa-users"></i>
                                                <span>5,234 Học viên</span>
                                            </div>
                                            <div class="stat">
                                                <i class="fa fa-play-circle"></i>
                                                <span>12 Khóa học</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="instructor-bio">
                                    <h4>Về giảng viên</h4>
                                    <p>Nguyễn Văn A là Senior Unity Developer với hơn 8 năm kinh nghiệm trong ngành game development. Anh đã tham gia phát triển hơn 20 game mobile và PC thành công, bao gồm các game top-grossing trên App Store và Google Play.</p>
                                    
                                    <p>Với passion về giáo dục, anh đã đào tạo hơn 5000 học viên trở thành Unity Developer trong 5 năm qua. Phương pháp giảng dạy của anh tập trung vào thực hành và project-based learning, giúp học viên nhanh chóng áp dụng kiến thức vào thực tế.</p>
                                    
                                    <h4>Kinh nghiệm</h4>
                                    <ul>
                                        <li>Senior Unity Developer tại ABC Game Studio (2018-2023)</li>
                                        <li>Unity Developer tại XYZ Entertainment (2015-2018)</li>
                                        <li>Freelance Game Developer (2013-2015)</li>
                                    </ul>
                                    
                                    <h4>Chứng chỉ</h4>
                                    <ul>
                                        <li>Unity Certified Expert</li>
                                        <li>Unity Certified Instructor</li>
                                        <li>Google Play Academy Graduate</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div id="reviews" class="tab-content">
                            <div class="reviews-section">
                                <div class="reviews-summary">
                                    <div class="rating-overview">
                                        <div class="rating-score">
                                            <span class="score">4.8</span>
                                            <div class="stars">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <span class="total-reviews">156 đánh giá</span>
                                        </div>
                                        
                                        <div class="rating-breakdown">
                                            <div class="rating-bar">
                                                <span>5 <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="fill" style="width: 78%"></div>
                                                </div>
                                                <span>78%</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span>4 <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="fill" style="width: 15%"></div>
                                                </div>
                                                <span>15%</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span>3 <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="fill" style="width: 5%"></div>
                                                </div>
                                                <span>5%</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span>2 <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="fill" style="width: 1%"></div>
                                                </div>
                                                <span>1%</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span>1 <i class="fa fa-star"></i></span>
                                                <div class="bar">
                                                    <div class="fill" style="width: 1%"></div>
                                                </div>
                                                <span>1%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="reviews-list">
                                    <div class="review-item">
                                        <div class="reviewer-info">
                                            <img src="https://via.placeholder.com/50x50?text=User" alt="User">
                                            <div class="reviewer-details">
                                                <h5>Trần Văn B</h5>
                                                <div class="review-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <span class="review-date">2 tuần trước</span>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <p>Khóa học rất chất lượng và chi tiết. Giảng viên giải thích rõ ràng, dễ hiểu. Sau khi hoàn thành khóa học, mình đã tự tin tạo được game đầu tiên và đang apply vào các công ty game.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="review-item">
                                        <div class="reviewer-info">
                                            <img src="https://via.placeholder.com/50x50?text=User" alt="User">
                                            <div class="reviewer-details">
                                                <h5>Lê Thị C</h5>
                                                <div class="review-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <span class="review-date">1 tháng trước</span>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <p>Từ người không biết gì về lập trình, sau 3 tháng học tôi đã có thể tạo ra game 2D hoàn chỉnh. Support từ giảng viên rất tốt, response nhanh và nhiệt tình.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Related Courses -->
                        <div class="sidebar-block">
                            <h3>Khóa học liên quan</h3>
                            <div class="related-courses">
                                <div class="related-course">
                                    <img src="https://via.placeholder.com/80x60?text=Unreal" alt="Unreal Course">
                                    <div class="course-info">
                                        <h4><a href="#">Unreal Engine 5 Complete Course</a></h4>
                                        <div class="course-meta">
                                            <span class="price">2,500,000đ</span>
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <span>4.7</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="related-course">
                                    <img src="https://via.placeholder.com/80x60?text=C%23" alt="C# Course">
                                    <div class="course-info">
                                        <h4><a href="#">C# Programming for Beginners</a></h4>
                                        <div class="course-meta">
                                            <span class="price">1,200,000đ</span>
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <span>4.9</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="related-course">
                                    <img src="https://via.placeholder.com/80x60?text=Mobile" alt="Mobile Course">
                                    <div class="course-info">
                                        <h4><a href="#">Mobile Game Development</a></h4>
                                        <div class="course-meta">
                                            <span class="price">1,800,000đ</span>
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <span>4.6</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        /* Course Hero */
        .course-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
        
        .course-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .course-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .course-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.5;
        }
        
        .course-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .meta-item i {
            color: #ffd700;
        }
        
        /* Course Card */
        .course-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: sticky;
            top: 2rem;
        }
        
        .course-image {
            position: relative;
        }
        
        .course-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .play-button:hover {
            background: rgba(0,0,0,0.9);
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .course-price {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .original-price {
            color: #999;
            text-decoration: line-through;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        .current-price {
            color: #333;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .course-actions {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-enroll {
            background: #667eea;
            color: white;
            padding: 1rem;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-enroll:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .btn-wishlist {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            padding: 0.75rem;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-wishlist:hover {
            background: #667eea;
            color: white;
        }
        
        .course-includes {
            padding: 1.5rem;
            background: #f8f9fa;
        }
        
        .course-includes h4 {
            margin-bottom: 1rem;
            color: #333;
        }
        
        .course-includes ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .course-includes li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .course-includes i {
            color: #28a745;
        }
        
        /* Course Content */
        .course-content {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .course-tabs {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tab-nav {
            display: flex;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
        }
        
        .tab-link {
            padding: 1.5rem 2rem;
            text-decoration: none;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .tab-link:hover,
        .tab-link.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: white;
        }
        
        .tab-content {
            display: none;
            padding: 2rem;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Content Sections */
        .content-section {
            margin-bottom: 3rem;
        }
        
        .content-section h2 {
            color: #333;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        
        .learning-objectives {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .objective-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        
        .objective-item i {
            color: #28a745;
            font-size: 1.2rem;
        }
        
        .course-description p {
            margin-bottom: 1.5rem;
            line-height: 1.6;
            color: #555;
        }
        
        .requirements ul {
            padding-left: 1.5rem;
        }
        
        .requirements li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        /* Curriculum */
        .curriculum-section h2 {
            color: #333;
            margin-bottom: 2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        
        .chapter {
            margin-bottom: 2rem;
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .chapter-header {
            background: #f8f9fa;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        
        .chapter-header h3 {
            margin: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .chapter-info {
            color: #666;
            font-size: 0.9rem;
        }
        
        .chapter-content {
            padding: 1rem;
        }
        
        .lesson {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .lesson:hover {
            background: #f8f9fa;
        }
        
        .lesson-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #333;
        }
        
        .lesson-title i {
            color: #667eea;
        }
        
        .lesson-duration {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Instructor */
        .instructor-card {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .instructor-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .instructor-info h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .instructor-title {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .instructor-stats {
            display: flex;
            gap: 1.5rem;
        }
        
        .stat {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.9rem;
        }
        
        .stat i {
            color: #ffd700;
        }
        
        .instructor-bio h4 {
            color: #333;
            margin: 2rem 0 1rem 0;
        }
        
        .instructor-bio p {
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        
        .instructor-bio ul {
            padding-left: 1.5rem;
        }
        
        .instructor-bio li {
            margin-bottom: 0.5rem;
        }
        
        /* Reviews */
        .reviews-summary {
            margin-bottom: 2rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .rating-overview {
            display: flex;
            gap: 3rem;
        }
        
        .rating-score {
            text-align: center;
        }
        
        .score {
            font-size: 3rem;
            font-weight: bold;
            color: #333;
            display: block;
        }
        
        .stars {
            margin: 0.5rem 0;
        }
        
        .stars i {
            color: #ffd700;
            font-size: 1.2rem;
        }
        
        .total-reviews {
            color: #666;
            font-size: 0.9rem;
        }
        
        .rating-breakdown {
            flex: 1;
        }
        
        .rating-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .rating-bar span:first-child {
            width: 30px;
            font-size: 0.9rem;
        }
        
        .bar {
            flex: 1;
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .fill {
            height: 100%;
            background: #ffd700;
        }
        
        .rating-bar span:last-child {
            width: 40px;
            text-align: right;
            font-size: 0.9rem;
            color: #666;
        }
        
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .review-item {
            padding: 1.5rem;
            border: 1px solid #eee;
            border-radius: 8px;
            background: white;
        }
        
        .reviewer-info {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .reviewer-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .reviewer-details h5 {
            margin-bottom: 0.25rem;
            color: #333;
        }
        
        .review-stars {
            margin-bottom: 0.25rem;
        }
        
        .review-stars i {
            color: #ffd700;
        }
        
        .review-date {
            color: #666;
            font-size: 0.8rem;
        }
        
        .review-content p {
            line-height: 1.6;
            color: #555;
            margin: 0;
        }
        
        /* Sidebar */
        .sidebar-block {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .sidebar-block h3 {
            color: #333;
            margin-bottom: 1rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        
        .related-courses {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .related-course {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #eee;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .related-course:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .related-course img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .related-course .course-info {
            flex: 1;
        }
        
        .related-course h4 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .related-course h4 a {
            color: #333;
            text-decoration: none;
        }
        
        .related-course h4 a:hover {
            color: #667eea;
        }
        
        .related-course .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .related-course .price {
            color: #667eea;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .related-course .rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .related-course .rating i {
            color: #ffd700;
            font-size: 0.8rem;
        }
        
        .related-course .rating span {
            font-size: 0.8rem;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }
            
            .col-lg-8, .col-lg-4 {
                flex: 1;
            }
            
            .course-hero h1 {
                font-size: 2rem;
            }
            
            .course-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .tab-nav {
                flex-wrap: wrap;
            }
            
            .tab-link {
                padding: 1rem;
                flex: 1;
                text-align: center;
            }
            
            .learning-objectives {
                grid-template-columns: 1fr;
            }
            
            .rating-overview {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
            
            .instructor-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Tab functionality
        document.querySelectorAll('.tab-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetTab = this.getAttribute('href').substring(1);
                
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });

        // Chapter expand/collapse (if needed)
        document.querySelectorAll('.chapter-header').forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const isVisible = content.style.display !== 'none';
                
                content.style.display = isVisible ? 'none' : 'block';
            });
        });
    </script>
    @endpush
@endsection
