XÁC NHẬN NHẬN HỒ SƠ ỨNG TUYỂN
=====================================

Xin chào {{ $applicantName }},

Chúng tôi đã nhận được hồ sơ ứng tuyển của bạn và rất vui mừng về sự quan tâm của bạn đến vị trí việc làm tại {{ $companyName }}.

THÔNG TIN ỨNG TUYỂN:
--------------------
Vị trí: {{ $jobTitle }}
Công ty: {{ $companyName }}
Mức lương: {{ $jobData['salary'] }}
Địa điểm: {{ $jobData['location'] }}
Thời gian ứng tuyển: {{ $appliedAt->format('d/m/Y H:i') }}

@if($applicationCode)
MÃ ĐƠN ỨNG TUYỂN: {{ $applicationCode }}
Vui lòng lưu mã này để tra cứu trạng thái đơn ứng tuyển.
@endif

CÁC BƯỚC TIẾP THEO:
------------------
Thời gian xem xét: {{ $nextSteps['review_time'] }}

Quy trình:
@foreach($nextSteps['what_happens_next'] as $step)
- {{ $step }}
@endforeach

Cách liên hệ: Chúng tôi sẽ liên hệ với bạn qua {{ $nextSteps['contact_method'] }}

LỜI KHUYÊN TRONG THỜI GIAN CHỜ:
-------------------------------
@foreach($nextSteps['tips'] as $tip)
- {{ $tip }}
@endforeach

CẦN HỖ TRỢ?
-----------
Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:
Email: {{ config('mail.contact.address', config('mail.from.address')) }}

---
{{ config('mail.from.name', 'Làm Game') }}
Nền tảng việc làm cho ngành Game Development tại Việt Nam
{{ config('app.url') }}

© {{ date('Y') }} {{ config('mail.from.name', 'Làm Game') }}. All rights reserved.

Email này được gửi tự động từ hệ thống. Vui lòng không phản hồi trực tiếp email này.