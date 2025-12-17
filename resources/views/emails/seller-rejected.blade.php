<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; }
        .reason-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #2c5f41; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thông báo về đơn đăng ký Seller</h1>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $seller->shop_name }}</strong>,</p>
            
            <p>Cảm ơn bạn đã quan tâm và đăng ký trở thành seller trên Làm Game.</p>
            
            <p>Rất tiếc, sau khi xem xét, chúng tôi chưa thể chấp nhận đơn đăng ký của bạn vào thời điểm này.</p>
            
            <div class="reason-box">
                <strong>Lý do:</strong><br>
                {{ $reason }}
            </div>
            
            <p><strong>Bạn có thể làm gì tiếp theo?</strong></p>
            <ul>
                <li>Xem lại thông tin đã đăng ký</li>
                <li>Cập nhật thông tin theo yêu cầu</li>
                <li>Đăng ký lại sau khi hoàn thiện</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ route('seller.register') }}" class="button">
                    Đăng ký lại
                </a>
            </div>
            
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi:</p>
            <ul>
                <li>Email: support@lamgame.vn</li>
                <li>Phone: 0908 123 456</li>
            </ul>
            
            <p>Trân trọng,<br><strong>Đội ngũ Làm Game</strong></p>
        </div>
        <div class="footer">
            <p>© 2025 Làm Game. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
