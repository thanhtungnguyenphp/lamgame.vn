Xin chào {{ $applicantName }}!

Chúng tôi đã nhận được hồ sơ ứng tuyển của bạn cho vị trí {{ $jobTitle }} tại {{ $companyName ?: 'LAMGAME' }}.

========================================
MÃ ĐƠN ỨNG TUYỂN: {{ $applicationCode }}
THỜI GIAN NỘP: {{ $appliedAt->format('d/m/Y H:i') }}
VỊ TRÍ: {{ $jobTitle }}
========================================

✅ HỒ SƠ CỦA BẠN ĐÃ ĐƯỢC GỬI THÀNH CÔNG

Chúng tôi xác nhận đã nhận được:
- Thông tin cá nhân
- CV/Resume
@if($application->cover_letter)
- Thư giới thiệu
@endif

📋 CÁC BƯỚC TIẾP THEO

1. Xem xét hồ sơ - Đội ngũ tuyển dụng sẽ xem xét hồ sơ của bạn trong vòng {{ $nextSteps['review_time'] }}

2. Liên hệ phỏng vấn - Nếu hồ sơ phù hợp, chúng tôi sẽ liên hệ qua {{ $nextSteps['contact_method'] }}

3. Thông báo kết quả - Bạn sẽ nhận được email thông báo về kết quả

💡 LỜI KHUYÊN

@foreach($nextSteps['tips'] as $tip)
- {{ $tip }}
@endforeach

========================================

LƯU Ý QUAN TRỌNG:
- Vui lòng kiểm tra email thường xuyên (kể cả thư mục spam)
- Giữ điện thoại liên lạc để chúng tôi có thể liên hệ
- Mã đơn ứng tuyển của bạn: {{ $applicationCode }}

Xem thêm việc làm khác: {{ config('app.url') }}/viec-lam

Chúc bạn may mắn!

Trân trọng,
{{ $companyName ?: 'LAMGAME' }}
Đội ngũ Tuyển dụng

---
Email này được gửi tự động. Vui lòng không trả lời email này.
Nếu có thắc mắc, vui lòng liên hệ: {{ config('mail.from.address') }}
