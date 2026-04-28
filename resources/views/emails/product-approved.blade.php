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
            <h1>✅ Sản phẩm đã được duyệt!</h1>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $seller->shop_name }}</strong>,</p>
            <p>Sản phẩm <strong>{{ $product->flat?->name ?? 'N/A' }}</strong> của bạn đã được duyệt và hiển thị trên marketplace.</p>
            <div style="text-align: center;">
                <a href="{{ route('seller.products.index') }}" class="button">Xem sản phẩm</a>
            </div>
            <p>Trân trọng,<br><strong>Đội ngũ Làm Game</strong></p>
        </div>
        <div class="footer">
            <p>© 2026 Làm Game. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
