@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.seller-pending-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 5rem 0;
    min-height: calc(100vh - 200px);
}
.pending-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    max-width: 700px;
    margin: 0 auto;
    text-align: center;
}
.pending-card h1 {
    color: #2c5f41;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1rem;
}
.pending-card p {
    color: #666;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}
.status-badge {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    background: #ffc107;
    color: #333;
    border-radius: 25px;
    font-weight: 600;
    margin-bottom: 2rem;
}
.btn-outline {
    display: inline-block;
    padding: 0.75rem 2rem;
    border: 2px solid #2c5f41;
    color: #2c5f41;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-outline:hover {
    background: #2c5f41;
    color: white;
}
</style>
@endpush

@section('content')
<div class="seller-pending-page">
    <div class="container">
        <div class="pending-card">
            <div>⏳</div>
            <h1>Đơn đăng ký đang được xem xét</h1>
            <p>
                Cảm ơn bạn đã đăng ký trở thành seller trên Làm Game!<br>
                Chúng tôi đang xem xét đơn đăng ký của bạn và sẽ phản hồi trong vòng <strong>24-48 giờ</strong>.
            </p>

            <!-- Shop Info -->
            <div>
                <h3>
                    📝 Thông tin đã đăng ký
                </h3>
                <div>
                    <div>
                        <span>Tên shop:</span>
                        <strong>{{ $seller->shop_name }}</strong>
                    </div>
                    <div>
                        <span>Email:</span>
                        <strong>{{ $seller->contact_email }}</strong>
                    </div>
                    <div>
                        <span>Loại hình:</span>
                        <strong>{{ $seller->business_type == 'individual' ? 'Cá nhân' : 'Công ty' }}</strong>
                    </div>
                    <div>
                        <span>Trạng thái:</span>
                        <span>
                            Đang chờ duyệt
                        </span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div>
                <h3>
                    ✅ Các bước tiếp theo
                </h3>
                <ul>
                    <li>
                        1️⃣ Chúng tôi sẽ xem xét thông tin của bạn
                    </li>
                    <li>
                        2️⃣ Bạn sẽ nhận email thông báo kết quả
                    </li>
                    <li>
                        3️⃣ Sau khi được duyệt, bạn có thể bắt đầu bán hàng
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <p>
                    Có câu hỏi? Liên hệ với chúng tôi:
                </p>
                <div>
                    <a href="mailto:support@lamgame.vn">
                        ✉️ support@lamgame.vn
                    </a>
                    <a href="tel:0908123456">
                        📞 0908 123 456
                    </a>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('home') }}" 
               >
                ← Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
