@component('shop::emails.layout')
    <div style="margin-bottom: 24px;">
        <p style="font-weight: 700; font-size: 18px; color: #F5F7FA; line-height: 24px; margin: 0 0 16px;">
            Xin chào {{ $userName }}, 👋
        </p>

        <p style="font-size: 15px; color: #B7C0D1; line-height: 24px; margin: 0 0 8px;">
            Bạn nhận được email này vì chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
        </p>
    </div>

    <p style="font-size: 15px; color: #B7C0D1; line-height: 24px; margin-bottom: 28px;">
        Nhấn nút bên dưới để đặt lại mật khẩu:
    </p>

    <div style="text-align: center; margin-bottom: 28px;">
        <a href="{{ route('shop.customers.reset_password.create', $token) }}"
           style="display: inline-block; padding: 14px 36px; background: #7C5CFF; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 15px;">
            Đặt lại mật khẩu
        </a>
    </div>

    <p style="font-size: 13px; color: #7A8599; line-height: 20px; margin: 0;">
        Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Link sẽ hết hạn sau 60 phút.
    </p>

    <hr style="border: none; border-top: 1px solid rgba(124,92,255,0.1); margin: 24px 0;">

    <p style="font-size: 12px; color: #7A8599; margin: 0;">
        Cần hỗ trợ? Liên hệ <a href="mailto:support@lamgame.vn" style="color: #7C5CFF; text-decoration: none;">support@lamgame.vn</a>
    </p>
@endcomponent
