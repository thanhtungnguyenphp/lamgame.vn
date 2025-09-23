{{-- EMSAIGON THEME VIEW LOADED --}}
@extends('layouts.master')

@section('page_title', 'EMSAIGON • Khóa Học Chăm Sóc Cổ Vai Gáy & Mắt - Khởi Nghiệp Dễ Dàng')

@section('page_description', 'Khóa học thực hành 100% từ Em Saigon Beauty & Wellness – Khởi nghiệp với vốn ít, thu nhập ngay. Ưu đãi 50% chỉ còn 25 triệu. Khai giảng 15/09/2025.')

@section('content')
    <!-- Hero Section -->
    <section class="hero loading" id="top">
        <div class="bg"></div>
        <div class="container">
            <div>
                <span class="badge">
                    <small>🔥 Ưu đãi 50% • Chỉ 50 suất đầu tiên</small>
                </span>
                <h1>KHÓA HỌC CHĂM SÓC CỔ VAI GÁY & MẮT – KHỚI NGHIỆP DỄ DÀNG, THU NHẬP NGAY!</h1>
                <p class="sub">
                    Khóa học thực hành <strong>100%</strong> từ <strong>Em Saigon Beauty & Wellness</strong>, 
                    giúp phụ nữ khởi nghiệp với vốn ít và làm nghề chuyên nghiệp ngay sau khi học. 
                    Kết hợp <strong>y thuật Ấn Độ</strong> và <strong>công nghệ chống lão hóa tiên tiến</strong>.
                </p>
                <div class="hero-cta">
                    <button class="btn btn-primary" onclick="scrollToSection('#dang-ky');trackCTA('hero_register')">
                        🎯 Đăng ký ngay – Giữ suất ưu đãi 50%
                    </button>
                    <button class="btn btn-outline" onclick="scrollToSection('#dang-ky');trackCTA('hero_consultation')">
                        💬 Nhận tư vấn miễn phí
                    </button>
                </div>
                <div class="meta">
                    <span>📅 Khai giảng: 15/09/2025</span>
                    <span>⏰ Thời lượng: 8 buổi • 24 giờ thực hành</span>
                    <span>🎁 Quà tặng: Trị giá 25 triệu</span>
                </div>
            </div>
            <div class="hero-visual">
                <div class="mock">
                    <img src="{{ asset('themes/shop/emsaigon/images/thmhnhspa/image0.jpeg') }}" alt="Em Saigon Spa Training" />
                </div>
            </div>
        </div>
    </section>

    <!-- Problems & Solutions -->
    <section id="gioi-thieu" class="loading">
        <div class="section-wrap">
            <h2 class="section-title">Vấn đề của bạn & Giải pháp từ EMSAIGON</h2>
            <p class="section-subtitle">
                Chúng tôi hiểu những khó khăn bạn đang gặp phải và có giải pháp phù hợp
            </p>
            <div class="grid ps">
                <div class="card">
                    <h4>❌ Những nỗi lo thường gặp</h4>
                    <ul class="list">
                        <li>Lo lắng vì <strong>thiếu kỹ năng chuyên môn</strong> để khởi nghiệp ngành làm đẹp</li>
                        <li>Khó khăn tìm nghề <strong>vừa linh hoạt thời gian vừa có thu nhập ổn định</strong></li>
                        <li>Muốn nâng cấp dịch vụ spa/salon nhưng <strong>không biết cách tạo sự khác biệt</strong></li>
                        <li>Thiếu tự tin khi bắt đầu nghề mới do <strong>không có chứng chỉ hoặc hỗ trợ thực hành</strong></li>
                    </ul>
                </div>
                <div class="card">
                    <h4>✅ Giải pháp toàn diện trong một khóa học</h4>
                    <ul class="list">
                        <li><strong>100% thực hành</strong> – tự tin làm nghề ngay sau khi học</li>
                        <li><strong>Kỹ thuật độc quyền</strong> kết hợp thư giãn, trị liệu và phục hồi năng lượng</li>
                        <li>Ứng dụng <strong>y thuật Ấn Độ & công nghệ chống lão hóa</strong> hiện đại</li>
                        <li><strong>Chứng chỉ nghề hợp pháp</strong> + hỗ trợ khởi nghiệp mini Homespa</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section id="loi-ich" class="loading">
        <div class="section-wrap">
            <h2 class="section-title">Lợi ích nổi bật của khóa học</h2>
            <p class="section-subtitle">
                6 giá trị cốt lõi giúp bạn thành công trong ngành chăm sóc sức khỏe và sắc đẹp
            </p>
            <div class="grid benefits">
                <div class="card benefit">
                    <div class="icon">💯</div>
                    <div>
                        <h4>100% Thực hành</h4>
                        <p class="muted">Đi thẳng vào kỹ năng, ra nghề ngay. Không lý thuyết suông, chỉ thực hành có giám sát.</p>
                    </div>
                </div>
                <div class="card benefit">
                    <div class="icon">🔥</div>
                    <div>
                        <h4>Kỹ thuật độc quyền</h4>
                        <p class="muted">Thư giãn – trị liệu – phục hồi năng lượng. Tạo trải nghiệm khác biệt cho khách hàng.</p>
                    </div>
                </div>
                <div class="card benefit">
                    <div class="icon">🧘</div>
                    <div>
                        <h4>Y thuật Ấn Độ & Công nghệ chống lão hóa</h4>
                        <p class="muted">Kết hợp truyền thống và hiện đại, tạo hiệu quả vượt trội cho khách hàng.</p>
                    </div>
                </div>
                <div class="card benefit">
                    <div class="icon">🏆</div>
                    <div>
                        <h4>Chứng chỉ hợp pháp</h4>
                        <p class="muted">Tăng uy tín & cơ hội hợp tác với spa, resort, khách sạn cao cấp.</p>
                    </div>
                </div>
                <div class="card benefit">
                    <div class="icon">🏠</div>
                    <div>
                        <h4>Khởi nghiệp mini Homespa</h4>
                        <p class="muted">Hỗ trợ trọn gói từ set-up đến vận hành, marketing và quản lý khách hàng.</p>
                    </div>
                </div>
                <div class="card benefit">
                    <div class="icon">⭐</div>
                    <div>
                        <h4>Nâng cấp dịch vụ sẵn có</h4>
                        <p class="muted">Tạo dấu ấn khác biệt cho spa/salon, tăng giá trị dịch vụ và lòng trung thành khách hàng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Information -->
    <section id="khoa-hoc" class="loading">
        <div class="section-wrap info">
            <div>
                <h2 class="section-title">Thông tin khóa học</h2>
                <div class="price-grid mt-24">
                    <div class="price-item">
                        <strong>📅 Khai giảng</strong><br>
                        15/09/2025
                    </div>
                    <div class="price-item">
                        <strong>⏰ Thời lượng</strong><br>
                        8 buổi • 24 giờ/2 tuần
                    </div>
                    <div class="price-item">
                        <strong>💰 Học phí gốc</strong><br>
                        <s>50.000.000đ</s>
                    </div>
                    <div class="price-item highlight">
                        <strong>🔥 Ưu đãi 50%</strong><br>
                        25.000.000đ
                    </div>
                    <div class="price-item">
                        <strong>👥 Nhóm 3 người</strong><br>
                        22.500.000đ/người
                    </div>
                    <div class="price-item">
                        <strong>🎁 Quà tặng</strong><br>
                        Trị giá 25.000.000đ
                    </div>
                </div>
                
                <div class="highlight mt-24">
                    <strong>⚡ Lưu ý quan trọng:</strong> Ưu đãi 50% chỉ giới hạn cho <strong>50 suất đầu tiên</strong>. 
                    Đăng ký sớm để giữ chỗ và nhận tư vấn 1-1 từ chuyên gia.
                </div>
                
                <div class="mt-24">
                    <button class="btn btn-primary" onclick="scrollToSection('#dang-ky');trackCTA('course_register')">
                        🎯 Giữ suất ưu đãi ngay
                    </button>
                </div>
            </div>
            
            <div class="card">
                <h3>🎯 Phù hợp cho ai?</h3>
                <ul class="list">
                    <li><strong>Phụ nữ</strong> muốn học nghề để tăng thu nhập và chủ động thời gian</li>
                    <li><strong>Chủ spa/salon/yoga/gym</strong> muốn nâng cấp dịch vụ và thu hút khách hàng</li>
                    <li><strong>Người muốn chăm sóc gia đình</strong> với kỹ thuật chuyên nghiệp</li>
                    <li><strong>Người mới khởi nghiệp</strong> trong ngành chăm sóc sức khỏe và sắc đẹp</li>
                </ul>
                
                <h3 class="mt-16">🎁 Bạn nhận được gì?</h3>
                <ul class="list">
                    <li>Thực hành trực tiếp cùng giảng viên – <strong>tiêu chuẩn nghề</strong></li>
                    <li>Quy trình & SOP dịch vụ theo định hướng <strong>ESG Xanh – Nhân văn – Số hóa</strong></li>
                    <li>Hỗ trợ <strong>khởi nghiệp mini Homespa</strong> hoặc nhượng quyền</li>
                    <li><strong>Thiết bị chăm sóc Body cao cấp</strong>, hỗ trợ giải tắc cơ sâu</li>
                    <li>Toàn bộ <strong>dụng cụ & sản phẩm</strong> phục vụ khóa học và làm nghề</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Image Gallery -->
    <section id="hinh-anh" class="loading">
        <div class="section-wrap">
            <h2 class="section-title">Hình ảnh hoạt động</h2>
            <p class="section-subtitle">
                Khám phá không gian học tập và những hoạt động thực tế tại Em Saigon
            </p>
            <div class="gallery">
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/hnhcholandingpagett/DSC09237.JPEG') }}" alt="Không gian học tập" />
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/hnhcholandingpagett/IMG_3123.JPG') }}" alt="Thực hành kỹ thuật" />
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/hnhcholandingpagett/IMG_6466.JPG') }}" alt="Giảng viên hướng dẫn" />
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/thmhnhspa/image1.jpeg') }}" alt="Spa Em Saigon" />
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/thmhnhspa/image2.jpeg') }}" alt="Thiết bị chuyên nghiệp" />
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('themes/shop/emsaigon/images/thmhnhspa/image3.jpeg') }}" alt="Dịch vụ chăm sóc" />
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Story -->
    <section class="loading">
        <div class="section-wrap">
            <div class="grid" style="grid-template-columns:1fr 1fr;gap:40px;align-items:center">
                <div class="card">
                    <h2 class="section-title" style="text-align:left;margin-bottom:20px">Câu chuyện thương hiệu</h2>
                    <p>
                        <strong>Em Saigon Beauty & Wellness</strong> – ESG Spa Mini: làm đẹp từ trái tim, 
                        phát triển bằng giá trị, lan tỏa bằng yêu thương. 
                    </p>
                    <p>
                        Mô hình <em style="color:var(--green);font-weight:bold">Xanh – Nhân văn – Số hóa</em> 
                        hướng tới việc trao quyền kinh tế cho phụ nữ, chuẩn hóa nghề & vận hành, 
                        và tạo tác động xã hội tích cực.
                    </p>
                    <p class="muted" style="font-style:italic;font-size:18px;margin-top:20px">
                        "Khi một người phụ nữ được trao quyền, cả cộng đồng sẽ phát triển."
                    </p>
                </div>
                <div class="hero-visual">
                    <div class="mock">
                        <img src="{{ asset('themes/shop/emsaigon/images/thmhnhspa/image4.jpeg') }}" alt="Em Saigon Brand Story">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration & Survey Form -->
    <section id="dang-ky" class="loading">
        <div class="section-wrap">
            <h2 class="section-title">Đăng ký & Khảo sát</h2>
            <p class="section-subtitle">
                Vui lòng điền thông tin để nhận tư vấn miễn phí và giữ suất ưu đãi 50%
            </p>
            
            <form class="form" onsubmit="handleSubmit(event)" aria-label="Form đăng ký khóa học">
                <!-- Basic Information -->
                <div class="field">
                    <label for="name">Họ và tên *</label>
                    <input id="name" name="name" required placeholder="Nguyễn Thị A" />
                </div>
                <div class="field">
                    <label for="email">Email *</label>
                    <input id="email" name="email" type="email" required placeholder="ban@example.com" />
                </div>
                <div class="field">
                    <label for="phone">Số điện thoại</label>
                    <input id="phone" name="phone" type="tel" placeholder="09xx xxx xxx" />
                </div>
                <div class="field">
                    <label for="city">Tỉnh/Thành phố</label>
                    <input id="city" name="city" placeholder="TP.HCM" />
                </div>

                <!-- Survey Questions -->
                <div class="field full">
                    <label>Bạn quan tâm nhất đến khiá cạnh nào của khóa học?</label>
                    <div class="choices">
                        <label class="chip">
                            <input type="checkbox" name="interest" value="technique" />
                            <span>Kỹ thuật chăm sóc</span>
                        </label>
                        <label class="chip">
                            <input type="checkbox" name="interest" value="startup" />
                            <span>Khởi nghiệp vốn ít</span>
                        </label>
                        <label class="chip">
                            <input type="checkbox" name="interest" value="certificate" />
                            <span>Chứng chỉ nghề</span>
                        </label>
                        <label class="chip">
                            <input type="checkbox" name="interest" value="technology" />
                            <span>Công nghệ chống lão hóa</span>
                        </label>
                    </div>
                </div>

                <!-- Agreement -->
                <div class="field full">
                    <label class="chip">
                        <input type="checkbox" name="agreement" required />
                        <span>Tôi đồng ý nhận thông tin từ EMSAIGON và có thể huỷ bất kỳ lúc nào</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="field full">
                    <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;font-size:18px;padding:20px">
                        🎯 Hoàn tất đăng ký – Giữ suất ưu đãi 50%
                    </button>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
        // Form submission handler
        function handleSubmit(event) {
            event.preventDefault();
            
            // Track registration attempt
            trackRegistration();
            
            // Collect form data
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            // Here you would normally send data to your server
            console.log('Form data:', data);
            
            // Show success message
            alert('Cảm ơn bạn đã đăng ký! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.');
            
            // Optional: Reset form
            event.target.reset();
        }

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = '0s';
                    entry.target.classList.add('loading');
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });

        // Chip selection enhancement
        document.querySelectorAll('.chip input').forEach(input => {
            input.addEventListener('change', function() {
                this.parentElement.classList.toggle('selected', this.checked);
            });
        });
    </script>
    @endpush
@endsection
