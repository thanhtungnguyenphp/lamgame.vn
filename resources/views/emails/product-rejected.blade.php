<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #c0392b 0%, #96281b 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; }
        .reason { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #2c5f41; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sản phẩm chưa được duyệt</h1>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $seller->shop_name }}</strong>,</p>
            <p>Sản phẩm <strong>{{ $product->flat?->name ?? 'N/A' }}</strong> chưa đạt yêu cầu để hiển thị trên marketplace.</p>
            <div class="reason">
                <strong>Lý do:</strong><br>{{ $reason }}
            </div>
            <p>Vui lòng chỉnh sửa và gửi lại để được duyệt.</p>
            <div style="text-align: center;">
                <a href="{{ route('seller.products.edit', $product->id) }}" class="button">Chỉnh sửa sản phẩm</a>
            </div>
            <p>Trân trọng,<br><strong>Đội ngũ Làm Game</strong></p>
        </div>
        <div class="footer">
            <p>© 2026 Làm Game. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
