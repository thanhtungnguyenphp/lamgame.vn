@extends('layouts.master')

@section('page_title', $page_title ?? 'Liên hệ - Làm Game')
@section('page_description', $page_description ?? 'Liên hệ với Làm Game để được tư vấn về các khóa học lập trình game')

@section('content')
    <!-- Hero Section -->
    <section class="hero-simple">
        <div class="container">
            <h1>Liên hệ với chúng tôi</h1>
            <p class="lead">Chúng tôi sẵn sàng hỗ trợ bạn trên con đường trở thành game developer</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section-content">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form-block">
                        <h2>Gửi tin nhắn cho chúng tôi</h2>
                        <p>Điền thông tin vào form bên dưới và chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.</p>
                        
                        <form id="contactForm" class="contact-form">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Họ và tên *</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="tel" id="phone" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Chủ đề *</label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Chọn chủ đề</option>
                                        <option value="tu-van-khoa-hoc">Tư vấn khóa học</option>
                                        <option value="dang-ky-hoc">Đăng ký học</option>
                                        <option value="hop-tac">Hợp tác</option>
                                        <option value="khac">Khác</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Nội dung *</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Gửi tin nhắn
                            </button>
                        </form>
                        
                        <div id="contactResult" class="contact-result" style="display: none;"></div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="faq-section">
                        <h2>Câu hỏi thường gặp</h2>
                        <div class="faq-list">
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h4>Làm Game có những khóa học gì?</h4>
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>Chúng tôi cung cấp các khóa học từ cơ bản đến nâng cao: Unity Game Development, Unreal Engine, Game Design, C# Programming, Mobile Game Development, 2D & 3D Game Development.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h4>Thời gian học của mỗi khóa là bao lâu?</h4>
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>Thời gian học dao động từ 2-4 tháng tùy thuộc vào khóa học. Các khóa cơ bản như Game Design kéo dài 2 tháng, trong khi các khóa nâng cao như Unreal Engine có thể lên đến 4 tháng.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h4>Có hỗ trợ việc làm sau khi hoàn thành khóa học không?</h4>
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>Có, chúng tôi có mạng lưới đối tác với hơn 50 công ty game trong nước và quốc tế. Tỷ lệ có việc làm của học viên sau khóa học lên đến 95%.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h4>Tôi chưa có kinh nghiệm lập trình, có thể học được không?</h4>
                                    <i class="fa fa-plus"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>Hoàn toàn được! Chúng tôi có các khóa học từ cơ bản dành cho người mới bắt đầu. Đặc biệt khóa C# Programming và Game Design sẽ giúp bạn xây dựng nền tảng vững chắc.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Contact Details -->
                        <div class="sidebar-block">
                            <h3>Thông tin liên hệ</h3>
                            <div class="contact-details">
                                <div class="contact-item">
                                    <i class="fa fa-map-marker"></i>
                                    <div>
                                        <h4>Địa chỉ</h4>
                                        <p>Tầng 7, Tòa nhà ABC<br>123 Nguyễn Huệ, Quận 1<br>TP. Hồ Chí Minh</p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <i class="fa fa-phone"></i>
                                    <div>
                                        <h4>Điện thoại</h4>
                                        <p>Hotline: <a href="tel:0909123456">0909 123 456</a><br>
                                        Tư vấn: <a href="tel:0908654321">0908 654 321</a></p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <i class="fa fa-envelope"></i>
                                    <div>
                                        <h4>Email</h4>
                                        <p>Chung: <a href="mailto:info@lamgame.vn">info@lamgame.vn</a><br>
                                        Hỗ trợ: <a href="mailto:support@lamgame.vn">support@lamgame.vn</a></p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <i class="fa fa-clock-o"></i>
                                    <div>
                                        <h4>Giờ làm việc</h4>
                                        <p>Thứ 2 - Thứ 6: 8:00 - 20:00<br>
                                        Thứ 7: 9:00 - 17:00<br>
                                        Chủ nhật: Nghỉ</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="sidebar-block">
                            <h3>Theo dõi chúng tôi</h3>
                            <div class="social-links">
                                <a href="#" class="social-link facebook">
                                    <i class="fa fa-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                                <a href="#" class="social-link youtube">
                                    <i class="fa fa-youtube"></i>
                                    <span>YouTube</span>
                                </a>
                                <a href="#" class="social-link discord">
                                    <i class="fa fa-comments"></i>
                                    <span>Discord</span>
                                </a>
                                <a href="#" class="social-link github">
                                    <i class="fa fa-github"></i>
                                    <span>GitHub</span>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="sidebar-block">
                            <h3>Liên kết nhanh</h3>
                            <div class="quick-links">
                                <a href="{{ route('lamgame.course-detail', 'unity') }}">Khóa Unity miễn phí</a>
                                <a href="{{ route('lamgame.gioi-thieu') }}">Về chúng tôi</a>
                                <a href="{{ route('lamgame.blog') }}">Blog & Tin tức</a>
                                <a href="#">Chính sách bảo mật</a>
                                <a href="#">Điều khoản sử dụng</a>
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

        /* Contact Form */
        .contact-form-block {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }
        
        .contact-form-block h2 {
            color: #6a4c93;
            margin-bottom: 1rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }
        
        .contact-form {
            margin-top: 2rem;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6a4c93;
        }
        
        .btn-primary {
            background: #6a4c93;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #5a3c83;
            transform: translateY(-2px);
        }

        /* Contact Result */
        .contact-result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 4px;
        }
        
        .contact-result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .contact-result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* FAQ Section */
        .faq-section {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .faq-section h2 {
            color: #6a4c93;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }
        
        .faq-item {
            border: 1px solid #e1e5e9;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .faq-question {
            padding: 1rem;
            background: #f8f9fa;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .faq-question:hover {
            background: #e9ecef;
        }
        
        .faq-question h4 {
            margin: 0;
            color: #333;
        }
        
        .faq-question i {
            color: #6a4c93;
            transition: transform 0.3s;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(45deg);
        }
        
        .faq-answer {
            padding: 0 1rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s, padding 0.3s;
        }
        
        .faq-item.active .faq-answer {
            padding: 1rem;
            max-height: 200px;
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
        
        .contact-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .contact-item i {
            color: #6a4c93;
            font-size: 1.5rem;
            margin-top: 0.25rem;
            width: 20px;
            flex-shrink: 0;
        }
        
        .contact-item h4 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .contact-item p {
            margin: 0;
            line-height: 1.5;
        }
        
        .contact-item a {
            color: #6a4c93;
            text-decoration: none;
        }
        
        .contact-item a:hover {
            text-decoration: underline;
        }
        
        /* Social Links */
        .social-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border: 1px solid #e1e5e9;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        
        .social-link:hover {
            background: #f8f9fa;
            border-color: #6a4c93;
            color: #6a4c93;
        }
        
        .social-link i {
            font-size: 1.25rem;
            width: 20px;
            text-align: center;
        }
        
        .social-link.facebook:hover { color: #3b5998; border-color: #3b5998; }
        .social-link.youtube:hover { color: #ff0000; border-color: #ff0000; }
        .social-link.discord:hover { color: #7289da; border-color: #7289da; }
        .social-link.github:hover { color: #333; border-color: #333; }
        
        /* Quick Links */
        .quick-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .quick-links a {
            color: #6a4c93;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .quick-links a:hover {
            background: #f8f9fa;
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
            
            .form-row {
                flex-direction: column;
            }
            
            .social-links {
                flex-direction: row;
                flex-wrap: wrap;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Contact Form Handler
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('contactResult');
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang gửi...';
            submitBtn.disabled = true;
            
            // Simulate API call (replace with actual endpoint)
            fetch('{{ route("lamgame.submit-contact") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.className = 'contact-result success';
                    resultDiv.textContent = data.message;
                    resultDiv.style.display = 'block';
                    this.reset();
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                resultDiv.className = 'contact-result error';
                resultDiv.textContent = 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.';
                resultDiv.style.display = 'block';
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // FAQ Toggle
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.closest('.faq-item');
                const isActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    </script>
    @endpush
@endsection
