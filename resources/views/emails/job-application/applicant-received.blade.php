@component('mail::message')
# Xin chào {{ $applicantName }}!

Chúng tôi đã nhận được hồ sơ ứng tuyển của bạn cho vị trí **{{ $jobTitle }}** tại {{ $companyName ?: 'LAMGAME' }}.

@component('mail::panel')
**Mã đơn ứng tuyển:** {{ $applicationCode }}  
**Thời gian nộp:** {{ $appliedAt->format('d/m/Y H:i') }}  
**Vị trí:** {{ $jobTitle }}
@endcomponent

## ✅ Hồ sơ của bạn đã được gửi thành công

Chúng tôi xác nhận đã nhận được:
- ✓ Thông tin cá nhân
- ✓ CV/Resume
@if($application->cover_letter)
- ✓ Thư giới thiệu
@endif

## 📋 Các bước tiếp theo

1. **Xem xét hồ sơ** - Đội ngũ tuyển dụng sẽ xem xét hồ sơ của bạn trong vòng {{ $nextSteps['review_time'] }}
2. **Liên hệ phỏng vấn** - Nếu hồ sơ phù hợp, chúng tôi sẽ liên hệ qua {{ $nextSteps['contact_method'] }}
3. **Thông báo kết quả** - Bạn sẽ nhận được email thông báo về kết quả

## 💡 Lời khuyên

@foreach($nextSteps['tips'] as $tip)
- {{ $tip }}
@endforeach

---

**Lưu ý quan trọng:**
- Vui lòng kiểm tra email thường xuyên (kể cả thư mục spam)
- Giữ điện thoại liên lạc để chúng tôi có thể liên hệ
- Mã đơn ứng tuyển của bạn: **{{ $applicationCode }}**

@component('mail::button', ['url' => config('app.url') . '/viec-lam'])
Xem thêm việc làm khác
@endcomponent

Chúc bạn may mắn!

Trân trọng,<br>
**{{ $companyName ?: 'LAMGAME' }}**<br>
Đội ngũ Tuyển dụng

---

<small>Email này được gửi tự động. Vui lòng không trả lời email này. Nếu có thắc mắc, vui lòng liên hệ: {{ config('mail.from.address') }}</small>
@endcomponent
