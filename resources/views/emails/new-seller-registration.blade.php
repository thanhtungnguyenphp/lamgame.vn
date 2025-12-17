<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; }
        .info-box { background: white; border: 1px solid #dee2e6; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .button { display: inline-block; padding: 12px 30px; background: #2c5f41; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Seller mới đăng ký</h1>
        </div>
        <div class="content">
            <p>Xin chào Admin,</p>
            
            <p>Có một seller mới vừa đăng ký trên hệ thống và đang chờ duyệt.</p>
            
            <div class="info-box">
                <h3>Thông tin Seller:</h3>
                <ul>
                    <li><strong>Shop Name:</strong> {{ $seller->shop_name }}</li>
                    <li><strong>Shop Slug:</strong> {{ $seller->shop_slug }}</li>
                    <li><strong>Email:</strong> {{ $seller->contact_email }}</li>
                    <li><strong>Phone:</strong> {{ $seller->contact_phone ?: 'N/A' }}</li>
                    <li><strong>Loại hình:</strong> {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}</li>
                    @if($seller->business_type == 'company')
                    <li><strong>Mã số thuế:</strong> {{ $seller->tax_id }}</li>
                    @endif
                    <li><strong>Ngân hàng:</strong> {{ $seller->bank_name }}</li>
                    <li><strong>Số TK:</strong> {{ $seller->bank_account }}</li>
                    <li><strong>Chủ TK:</strong> {{ $seller->bank_holder }}</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h3>Thông tin Customer:</h3>
                <ul>
                    <li><strong>Tên:</strong> {{ $seller->customer->first_name }} {{ $seller->customer->last_name }}</li>
                    <li><strong>Email:</strong> {{ $seller->customer->email }}</li>
                    <li><strong>Phone:</strong> {{ $seller->customer->phone ?: 'N/A' }}</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.sellers.show', $seller->id) }}" class="button">
                    Xem chi tiết & Duyệt
                </a>
            </div>
            
            <p><small>Email này được gửi tự động từ hệ thống.</small></p>
        </div>
        <div class="footer">
            <p>© 2025 Làm Game Admin Panel</p>
        </div>
    </div>
</body>
</html>
