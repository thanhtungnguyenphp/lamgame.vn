@component('mail::message')
# Xin chào {{ $customer->first_name }} {{ $customer->last_name }}!

Cảm ơn bạn đã đăng ký tài khoản tại **LAMGAME** - Nền tảng học lập trình game hàng đầu Việt Nam.

Để hoàn tất đăng ký và kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới:

@component('mail::button', ['url' => $verificationUrl, 'color' => 'success'])
Xác thực tài khoản
@endcomponent

Hoặc copy link sau vào trình duyệt:
{{ $verificationUrl }}

**Lưu ý**: Link xác thực có hiệu lực trong 24 giờ.

Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này.

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
