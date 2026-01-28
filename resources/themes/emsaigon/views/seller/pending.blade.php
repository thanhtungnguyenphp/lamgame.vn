@extends('shop::layouts.master')

@section('page_title', $page_title)

@section('content')
<div class="seller-pending-page" style="background: #f8f9fa; padding: 5rem 0; min-height: 80vh;">
    <div class="container" style="max-width: 700px; text-align: center;">
        <div class="pending-card" style="background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <!-- Icon -->
            <div style="font-size: 5rem; margin-bottom: 2rem;">⏳</div>

            <!-- Title -->
            <h1 style="color: #2c5f41; font-size: 2rem; font-weight: 800; margin-bottom: 1rem;">
                Đơn đăng ký đang được xem xét
            </h1>

            <!-- Message -->
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;">
                Cảm ơn bạn đã đăng ký trở thành seller trên Làm Game!<br>
                Chúng tôi đang xem xét đơn đăng ký của bạn và sẽ phản hồi trong vòng <strong>24-48 giờ</strong>.
            </p>

            <!-- Shop Info -->
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; text-align: left;">
                <h3 style="color: #2c5f41; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 700;">
                    📝 Thông tin đã đăng ký
                </h3>
                <div style="display: grid; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666;">Tên shop:</span>
                        <strong style="color: #333;">{{ $seller->shop_name }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666;">Email:</span>
                        <strong style="color: #333;">{{ $seller->contact_email }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666;">Loại hình:</span>
                        <strong style="color: #333;">{{ $seller->business_type == 'individual' ? 'Cá nhân' : 'Công ty' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666;">Trạng thái:</span>
                        <span style="background: #ffc107; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                            Đang chờ duyệt
                        </span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; text-align: left;">
                <h3 style="color: #2c5f41; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 700;">
                    ✅ Các bước tiếp theo
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0; color: #333;">
                    <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        1️⃣ Chúng tôi sẽ xem xét thông tin của bạn
                    </li>
                    <li style="padding: 0.5rem 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        2️⃣ Bạn sẽ nhận email thông báo kết quả
                    </li>
                    <li style="padding: 0.5rem 0;">
                        3️⃣ Sau khi được duyệt, bạn có thể bắt đầu bán hàng
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div style="padding: 1.5rem; border: 2px dashed #dee2e6; border-radius: 15px;">
                <p style="color: #666; margin-bottom: 1rem;">
                    Có câu hỏi? Liên hệ với chúng tôi:
                </p>
                <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                    <a href="mailto:support@lamgame.vn" style="color: #2c5f41; text-decoration: none; font-weight: 600;">
                        ✉️ support@lamgame.vn
                    </a>
                    <a href="tel:0908123456" style="color: #2c5f41; text-decoration: none; font-weight: 600;">
                        📞 0908 123 456
                    </a>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ url('/') }}" 
                style="display: inline-block; margin-top: 2rem; padding: 0.75rem 2rem; background: #f8f9fa; color: #2c5f41; border: 2px solid #2c5f41; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                ← Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
