@component('mail::message')
# 🎯 Ứng viên mới ứng tuyển

@if($applicationStats['is_first_application'])
@component('mail::panel')
🎉 **Đây là ứng viên đầu tiên** cho vị trí này!
@endcomponent
@endif

## Thông tin vị trí

**{{ $jobData['title'] }}**
@if($jobData['company'])
- Công ty: {{ $jobData['company'] }}
@endif
- Mức lương: {{ $jobData['salary'] }}
- Địa điểm: {{ $jobData['location'] }}
- Loại hình: {{ $jobData['job_type'] }}

---

## 👤 Thông tin ứng viên

@component('mail::table')
| Thông tin | Chi tiết |
|:----------|:---------|
| **Họ tên** | {{ $applicantData['name'] }} |
| **Email** | {{ $applicantData['email'] }} |
| **Điện thoại** | {{ $applicantData['phone'] ?: 'Chưa cung cấp' }} |
| **Kinh nghiệm** | {{ $applicantData['experience_level'] }} |
| **Mã đơn** | {{ $applicationCode }} |
| **Thời gian nộp** | {{ $appliedAt->format('d/m/Y H:i') }} |
@endcomponent

@if($applicantData['has_cv'])
### 📄 CV/Resume
**File:** {{ $applicantData['cv_filename'] }}

@component('mail::button', ['url' => $quickActions['download_cv'], 'color' => 'success'])
Tải xuống CV
@endcomponent
@endif

@if($application->cover_letter)
### ✉️ Thư giới thiệu

@component('mail::panel')
{{ Str::limit($application->cover_letter, 300) }}
@if(strlen($application->cover_letter) > 300)

[Xem đầy đủ trong hệ thống]
@endif
@endcomponent
@endif

---

## ⚡ Hành động nhanh

@component('mail::button', ['url' => $quickActions['view_application']])
Xem chi tiết đơn ứng tuyển
@endcomponent

**Liên hệ ứng viên:**
- 📧 Email: [{{ $applicantData['email'] }}]({{ $quickActions['contact_applicant'] }})
- 📞 Điện thoại: [{{ $applicantData['phone'] }}]({{ $quickActions['call_applicant'] }})

**Cập nhật trạng thái:**
- [✅ Lọt vòng]({{ $quickActions['shortlist'] }})
- [❌ Từ chối]({{ $quickActions['reject'] }})

---

## 📊 Thống kê ứng tuyển

@component('mail::table')
| Chỉ số | Số lượng |
|:-------|:---------|
| Tổng đơn | {{ $applicationStats['total'] }} |
| Hôm nay | {{ $applicationStats['today'] }} |
| Chờ xử lý | {{ $applicationStats['pending'] }} |
@endcomponent

---

Trân trọng,<br>
**LAMGAME**<br>
Hệ thống Tuyển dụng

<small>Email này được gửi tự động khi có ứng viên mới. Bạn có thể trả lời trực tiếp email này để liên hệ với ứng viên.</small>
@endcomponent
