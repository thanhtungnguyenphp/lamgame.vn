ỨNG VIÊN MỚI - {{ strtoupper($applicantData['name']) }}
===============================================

🎯 VỊ TRÍ: {{ $jobData['title'] }}
@if($applicationStats['is_first_application'])
⭐ ỨNG VIÊN ĐẦU TIÊN CHO VỊ TRÍ NÀY!
@endif

THÔNG TIN ỨNG VIÊN:
-------------------
Tên: {{ $applicantData['name'] }}
Email: {{ $applicantData['email'] }}
Điện thoại: {{ $applicantData['phone'] }}
Kinh nghiệm: {{ $applicantData['experience_level'] }}
CV: {{ $applicantData['has_cv'] ? ($applicantData['cv_filename'] ?? 'Đã upload') : 'Chưa upload' }}
Mã đơn: {{ $applicationCode }}
Thời gian: {{ $appliedAt->format('d/m/Y H:i') }}

THÔNG TIN CÔNG VIỆC:
--------------------
Vị trí: {{ $jobData['title'] }}
Công ty: {{ $jobData['company'] }}
Mức lương: {{ $jobData['salary'] }}
Địa điểm: {{ $jobData['location'] }}
Loại hình: {{ $jobData['job_type'] }}

THỐNG KÊ ỨNG TUYỂN:
------------------
- Tổng ứng viên: {{ $applicationStats['total'] }}
- Ứng viên hôm nay: {{ $applicationStats['today'] }}  
- Đang chờ duyệt: {{ $applicationStats['pending'] }}

@if($applicationStats['is_first_application'])
🎉 Đây là ứng viên đầu tiên cho vị trí này!
@else
📈 Có tổng cộng {{ $applicationStats['total'] }} ứng viên đã ứng tuyển vị trí này.
@endif

@if($applicantData['cover_letter'])
THƯ GIỚI THIỆU:
--------------
{{ $applicantData['cover_letter'] }}
@endif

HÀNH ĐỘNG NHANH:
----------------
👁 Xem chi tiết: {{ $quickActions['view_application'] }}
@if($applicantData['has_cv'])
📄 Tải CV: {{ $quickActions['download_cv'] }}
@endif
📧 Gửi email: {{ $quickActions['contact_applicant'] }}
📞 Gọi điện: {{ $quickActions['call_applicant'] }}
✅ Shortlist: {{ $quickActions['shortlist'] }}
❌ Từ chối: {{ $quickActions['reject'] }}

QUẢN LÝ:
--------
🏛 Admin Panel: {{ $quickActions['view_application'] }}

LỚI CHÚ:
--------
- Bạn có thể trả lời trực tiếp email này để liên hệ với ứng viên
- Email được gửi tự động khi có ứng viên mới ứng tuyển
- Các action link phía trên sẽ đưa bạn đến admin panel để xử lý

---
{{ config('mail.from.name', 'Làm Game') }} - Hệ thống tuyển dụng
{{ config('app.url') }}

© {{ date('Y') }} {{ config('mail.from.name', 'Làm Game') }}. All rights reserved.