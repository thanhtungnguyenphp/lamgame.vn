<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; }
        .button { display: inline-block; padding: 12px 30px; background: #2c5f41; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Chúc mừng!</h1>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $seller->shop_name }}</strong>,</p>
            
            <p>Chúng tôi vui mừng thông báo rằng tài khoản seller của bạn đã được <strong>kích hoạt thành công</strong>!</p>
            
            <p>Bạn có thể bắt đầu:</p>
            <ul>
                <li>✅ Upload source code game</li>
                <li>✅ Quản lý sản phẩm</li>
                <li>✅ Theo dõi doanh thu</li>
                <li>✅ Rút tiền về tài khoản</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ route('seller.dashboard') }}" class="button">
                    Truy cập Dashboard
                </a>
            </div>
            
            <p><strong>Thông tin shop của bạn:</strong></p>
            <ul>
                <li>Tên shop: {{ $seller->shop_name }}</li>
                <li>Email: {{ $seller->contact_email }}</li>
                <li>Loại hình: {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}</li>
            </ul>
            
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:</p>
            <ul>
                <li>Email: support@lamgame.vn</li>
                <li>Phone: 0908 123 456</li>
            </ul>
            
            <p>Chúc bạn kinh doanh thành công!</p>
            
            <p>Trân trọng,<br><strong>Đội ngũ Làm Game</strong></p>
        </div>
        <div class="footer">
            <p>© 2025 Làm Game. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
