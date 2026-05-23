@component('shop::emails.layout')
    <div style="margin-bottom: 24px;">
        <p style="font-weight: 700; font-size: 18px; color: #F5F7FA; line-height: 24px; margin: 0 0 16px;">
            Xin chào {{ $customer->name }}, 👋
        </p>

        <p style="font-size: 15px; color: #B7C0D1; line-height: 24px; margin: 0;">
            Mật khẩu tài khoản của bạn đã được cập nhật thành công.
        </p>
    </div>

    <p style="font-size: 15px; color: #B7C0D1; line-height: 24px; margin-bottom: 24px;">
        Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ ngay với chúng tôi để bảo vệ tài khoản.
    </p>

    <div style="text-align: center; margin-bottom: 24px;">
        <a href="https://lamgame.vn/auth/login"
           style="display: inline-block; padding: 12px 32px; background: #7C5CFF; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px;">
            Đăng nhập ngay
        </a>
    </div>

    <hr style="border: none; border-top: 1px solid rgba(124,92,255,0.1); margin: 24px 0;">

    <p style="font-size: 12px; color: #7A8599; margin: 0;">
        Cần hỗ trợ? Liên hệ <a href="mailto:support@lamgame.vn" style="color: #7C5CFF; text-decoration: none;">support@lamgame.vn</a>
    </p>
@endcomponent
