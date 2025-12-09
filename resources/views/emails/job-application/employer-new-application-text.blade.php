🎯 ỨNG VIÊN MỚI ỨNG TUYỂN

@if($applicationStats['is_first_application'])
========================================
🎉 ĐÂY LÀ ỨNG VIÊN ĐẦU TIÊN cho vị trí này!
========================================
@endif

THÔNG TIN VỊ TRÍ
----------------------------------------
{{ $jobData['title'] }}
@if($jobData['company'])
Công ty: {{ $jobData['company'] }}
@endif
Mức lương: {{ $jobData['salary'] }}
Địa điểm: {{ $jobData['location'] }}
Loại hình: {{ $jobData['job_type'] }}

========================================

👤 THÔNG TIN ỨNG VIÊN
----------------------------------------
Họ tên: {{ $applicantData['name'] }}
Email: {{ $applicantData['email'] }}
Điện thoại: {{ $applicantData['phone'] ?: 'Chưa cung cấp' }}
Kinh nghiệm: {{ $applicantData['experience_level'] }}
Mã đơn: {{ $applicationCode }}
Thời gian nộp: {{ $appliedAt->format('d/m/Y H:i') }}

@if($applicantData['has_cv'])
📄 CV/RESUME
File: {{ $applicantData['cv_filename'] }}
Tải xuống: {{ $quickActions['download_cv'] }}
@endif

@if($application->cover_letter)
✉️ THƯ GIỚI THIỆU
----------------------------------------
{{ Str::limit($application->cover_letter, 300) }}
@if(strlen($application->cover_letter) > 300)
[Xem đầy đủ trong hệ thống]
@endif
@endif

========================================

⚡ HÀNH ĐỘNG NHANH

Xem chi tiết đơn ứng tuyển:
{{ $quickActions['view_application'] }}

LIÊN HỆ ỨNG VIÊN:
- Email: {{ $applicantData['email'] }}
- Điện thoại: {{ $applicantData['phone'] }}

CẬP NHẬT TRẠNG THÁI:
- Lọt vòng: {{ $quickActions['shortlist'] }}
- Từ chối: {{ $quickActions['reject'] }}

========================================

📊 THỐNG KÊ ỨNG TUYỂN
----------------------------------------
Tổng đơn: {{ $applicationStats['total'] }}
Hôm nay: {{ $applicationStats['today'] }}
Chờ xử lý: {{ $applicationStats['pending'] }}

========================================

Trân trọng,
LAMGAME
Hệ thống Tuyển dụng

---
Email này được gửi tự động khi có ứng viên mới.
Bạn có thể trả lời trực tiếp email này để liên hệ với ứng viên.
