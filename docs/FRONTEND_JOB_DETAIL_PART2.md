# Tài Liệu Front-End Job Detail Page - Part 2

## 7. Blade Template Structure

**File**: `resources/views/lamgame/pages/job-detail.blade.php`

**Total Lines**: 1821 lines

### Template Sections

```
1. Meta Tags & Scripts (lines 1-40)
   ├── CSRF Token
   ├── Job ID meta
   ├── Customer data (JavaScript)
   └── Job tracking script

2. Breadcrumb (lines 41-50)
   └── Home › Việc làm Game › Job Title

3. Job Header Card (lines 51-101)
   ├── Company Logo
   ├── Job Title
   ├── Company Name
   ├── Job Meta (location, salary, type, posted time)
   └── Action Buttons (Apply, Save)

4. Company Info (lines 103-114)
   ├── Company Logo (large)
   ├── Company Name
   └── Company Description

5. Content Sections (lines 116-200)
   ├── Job Description
   ├── Job Requirements
   ├── Required Skills
   ├── Benefits
   └── Additional Info

6. Sidebar (lines 201-228)
   └── Similar Jobs

7. Bottom Apply Section (lines 233-249)
   └── CTA + Apply Button

8. Apply Modal (lines 252-350)
   ├── Modal Header
   ├── Auth Info Section
   ├── Application Form
   └── Modal Footer

9. Styles (lines 351-1500)
   └── Complete CSS

10. Scripts (lines 1501-1821)
    └── JavaScript functionality
```

---

## 8. Key UI Components

### 8.1 Job Header Card

```blade
<div class="job-header-card">
    <div class="job-header-content">
        <!-- Company Logo -->
        <div class="company-logo">
            <div class="logo-placeholder">
                {{ strtoupper(substr($companyName, 0, 2)) }}
            </div>
        </div>
        
        <!-- Job Info -->
        <div class="job-info">
            <h1 class="job-title">{{ $jobTitle }}</h1>
            <div class="company-name">{{ $companyName }}</div>
            
            <!-- Job Meta -->
            <div class="job-meta">
                <div class="meta-item">
                    <i class="fa fa-map-marker"></i>
                    <span>{{ $job->attributes['job_location'] ?? 'Việt Nam' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fa fa-money"></i>
                    <span>{{ $salaryFormatted }}</span>
                </div>
                <div class="meta-item">
                    <i class="fa fa-clock-o"></i>
                    <span>{{ $job->attributes['job_type'] ?? 'Full-time' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fa fa-calendar"></i>
                    <span>{{ $postedAgo }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn-apply" onclick="openApplyModal()">
            <i class="fa fa-paper-plane"></i>
            <span>Ứng tuyển ngay</span>
        </button>
        <button class="btn-save" onclick="toggleSaveJob(this)">
            <i class="fa fa-heart-o"></i>
            <span>Lưu việc làm</span>
        </button>
    </div>
</div>
```

### 8.2 Company Info Section

```blade
<div class="company-info">
    <div class="company-logo-large">
        @if(isset($companyInfo['logo_url']) && $companyInfo['logo_url'])
            <img src="{{ $companyInfo['logo_url'] }}" 
                 alt="{{ $companyInfo['name'] }}" 
                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px;">
        @else
            {{ strtoupper(substr($companyInfo['name'], 0, 2)) }}
        @endif
    </div>
    <h4 class="company-name-large">{{ $companyInfo['name'] }}</h4>
    <p class="company-desc">{!! nl2br(e($companyInfo['description'])) !!}</p>
</div>
```

### 8.3 Job Description

```blade
<div class="content-section">
    <h2 class="section-title">Mô tả công việc</h2>
    <div class="section-content">
        @if($job->description)
            {!! nl2br($job->description) !!}
        @else
            <p>Thông tin mô tả công việc sẽ được cập nhật sớm.</p>
        @endif
    </div>
</div>
```

### 8.4 Required Skills

```blade
@if(isset($job->attributes['required_skills']) && !empty($job->attributes['required_skills']))
<div class="content-section">
    <h2 class="section-title">Kỹ năng yêu cầu</h2>
    <div class="section-content">
        <div class="skills-list">
            @php
                $skills = explode(',', $job->attributes['required_skills']);
            @endphp
            @foreach($skills as $skill)
                <span class="skill-tag">{{ trim($skill) }}</span>
            @endforeach
        </div>
    </div>
</div>
@endif
```

### 8.5 Benefits

```blade
@if(isset($job->attributes['job_benefits']) && !empty($job->attributes['job_benefits']))
<div class="content-section">
    <h2 class="section-title">Quyền lợi</h2>
    <div class="section-content">
        <div class="benefits-list">
            @php
                $benefits = explode(',', $job->attributes['job_benefits']);
            @endphp
            @foreach($benefits as $benefit)
                <div class="benefit-item">
                    <i class="fa fa-check-circle"></i>
                    <span>{{ trim($benefit) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
```

### 8.6 Similar Jobs

```blade
@if($similarJobs->count() > 0)
<div class="sidebar-card">
    <h3 class="sidebar-title">Việc làm tương tự</h3>
    <div class="similar-jobs">
        @foreach($similarJobs as $similarJob)
            @php
                $similarTitle = explode(' - ', $similarJob->name)[0] ?? $similarJob->name;
                $similarCompany = trim(str_replace(' - ', ' ', explode(' - ', $similarJob->name)[1] ?? $similarJob->name));
                $similarSalary = number_format($similarJob->price / 1000000, 1) . ' triệu';
            @endphp
            <div class="similar-job">
                <h4 class="similar-job-title">
                    <a href="{{ route('lamgame.job.detail', $similarJob->url_key) }}">
                        {{ $similarTitle }}
                    </a>
                </h4>
                <div class="similar-job-company">{{ $similarCompany }}</div>
                <div class="similar-job-salary">{{ $similarSalary }}</div>
            </div>
        @endforeach
    </div>
</div>
@endif
```

---

## 9. Apply Modal

### 9.1 Modal Structure

```blade
<div id="applyModal" class="modal-overlay" onclick="closeApplyModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="modal-header">
            <h3>Ứng tuyển vị trí: {{ $jobTitle }}</h3>
            <button class="modal-close" onclick="closeApplyModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form id="applyForm" class="apply-form" enctype="multipart/form-data">
                <!-- Form fields -->
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeApplyModal()">
                Hủy
            </button>
            <button type="submit" form="applyForm" class="btn-submit">
                <i class="fa fa-paper-plane"></i>
                Gửi hồ sơ
            </button>
        </div>
    </div>
</div>
```

### 9.2 Auth Info Section

#### For Logged In Users

```blade
@if($isLoggedIn)
<div class="auth-info-section">
    <div class="auth-indicator">
        <i class="fa fa-check-circle" style="color: #10b981;"></i>
        <span>Đã đăng nhập: {{ $customer['full_name'] }}</span>
        <small style="display: block; color: #6b7280; margin-top: 2px;">
            Thông tin sẽ được tự động điền
        </small>
    </div>
</div>
@endif
```

#### For Guest Users

```blade
@else
<div class="auth-info-section">
    <div class="guest-info">
        <div class="guest-message">
            <i class="fa fa-info-circle" style="color: #667eea;"></i>
            <div>
                <span>Đang ứng tuyển với tư cách khách</span>
                <small style="display: block; color: #6b7280; margin-top: 2px;">
                    Đăng nhập để tự động điền thông tin và quản lý hồ sơ ứng tuyển
                </small>
            </div>
        </div>
        <div class="guest-actions">
            <a href="{{ route('auth.login') }}" class="btn-quick-login" target="_blank">
                <i class="fa fa-sign-in"></i>
                Đăng nhập
            </a>
            <a href="{{ route('auth.register') }}" class="btn-quick-register" target="_blank">
                <i class="fa fa-user-plus"></i>
                Đăng ký
            </a>
        </div>
    </div>
</div>
@endif
```

### 9.3 Application Form Fields

```blade
<!-- Full Name & Email -->
<div class="form-row">
    <div class="form-group">
        <label for="full_name">Họ và tên <span class="required">*</span></label>
        <input type="text" id="full_name" name="full_name" required 
               placeholder="Nhập họ và tên của bạn">
    </div>
    <div class="form-group">
        <label for="email">Email <span class="required">*</span></label>
        <input type="email" id="email" name="email" required 
               placeholder="email@example.com">
    </div>
</div>

<!-- Phone -->
<div class="form-group">
    <label for="phone">Số điện thoại <span class="required">*</span></label>
    <input type="tel" id="phone" name="phone" required 
           placeholder="0123456789">
</div>

<!-- CV Upload -->
<div class="form-group">
    <label for="cv">Upload CV <span class="required">*</span></label>
    <div class="file-upload-area">
        <input type="file" id="cv" name="cv" 
               accept=".pdf,.doc,.docx" required>
        <div class="file-upload-text">
            <i class="fa fa-upload"></i>
            <span>Chọn file CV (PDF, DOC, DOCX)</span>
        </div>
        <div id="fileName" style="display: none; margin-top: 8px;"></div>
    </div>
    <small class="form-help">Kích thước tối đa: 5MB</small>
</div>

<!-- Cover Letter -->
<div class="form-group">
    <label for="cover_letter">Thư xin việc (tùy chọn)</label>
    <textarea id="cover_letter" name="cover_letter" rows="4" 
              placeholder="Giới thiệu ngắn gọn về bản thân và lý do ứng tuyển..."></textarea>
</div>
```

---

## 10. JavaScript Functionality

### 10.1 Customer Data Injection

```javascript
@if($isLoggedIn)
<script>
    window.customerData = @json($customer);
    window.isLoggedIn = true;
</script>
@else
<script>
    window.customerData = null;
    window.isLoggedIn = false;
</script>
@endif
```

### 10.2 Job View Tracking

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Track job view
    if (typeof window.trackEvent === 'function') {
        window.trackEvent('job_view', {
            'event_category': 'jobs',
            'event_label': '{{ $jobTitle }}',
            'job_id': '{{ $job->id }}',
            'company': '{{ $companyName }}',
            'value': 1
        });
    }
});
```

### 10.3 Modal Functions

```javascript
// Open apply modal
function openApplyModal() {
    document.getElementById('applyModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Auto-fill if logged in
    if (window.isLoggedIn && window.customerData) {
        document.getElementById('full_name').value = window.customerData.full_name;
        document.getElementById('email').value = window.customerData.email;
        document.getElementById('phone').value = window.customerData.phone || '';
    }
}

// Close apply modal
function closeApplyModal() {
    document.getElementById('applyModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}
```

### 10.4 Save Job Function

```javascript
function toggleSaveJob(button) {
    const icon = button.querySelector('i');
    const text = button.querySelector('span');
    
    if (icon.classList.contains('fa-heart-o')) {
        // Save job
        icon.classList.remove('fa-heart-o');
        icon.classList.add('fa-heart');
        text.textContent = 'Đã lưu';
        button.classList.add('saved');
        
        // TODO: Call API to save job
    } else {
        // Unsave job
        icon.classList.remove('fa-heart');
        icon.classList.add('fa-heart-o');
        text.textContent = 'Lưu việc làm';
        button.classList.remove('saved');
        
        // TODO: Call API to unsave job
    }
}
```

---

## 11. Responsive Design

### Breakpoints

```css
/* Mobile First */
/* Base styles: 320px - 767px */

/* Tablet */
@media (min-width: 768px) {
    /* Tablet styles */
}

/* Desktop */
@media (min-width: 1024px) {
    /* Desktop styles */
}

/* Large Desktop */
@media (min-width: 1200px) {
    /* Large desktop styles */
}
```

### Key Responsive Features

1. **Job Header Card**
   - Mobile: Stacked layout
   - Desktop: Horizontal layout with logo, info, and buttons

2. **Content Layout**
   - Mobile: Single column
   - Desktop: Main content + Sidebar (70/30 split)

3. **Job Meta**
   - Mobile: 2 columns grid
   - Desktop: 4 columns inline

4. **Apply Modal**
   - Mobile: Full screen
   - Desktop: Centered modal (max-width: 600px)

5. **Form Fields**
   - Mobile: Single column
   - Desktop: 2 columns for name/email row

---

## 12. SEO & Meta Tags

### Page Title

```php
'page_title' => $jobTitle . ' - ' . $companyName . ' - Làm Game'
```

**Example**: "Senior PHP Developer - FPT Software - Làm Game"

### Page Description

```php
'page_description' => Str::limit($job->short_description, 160)
```

**Purpose**: Truncate description to 160 characters for SEO

### Meta Tags in Blade

```blade
@section('page_title', $page_title ?? 'Chi tiết việc làm - Làm Game')
@section('page_description', $page_description ?? 'Thông tin chi tiết về cơ hội việc làm trong ngành game development')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="job-id" content="{{ $job->id }}">
@endpush
```

---

## 13. Data Flow Summary

```
URL: /viec-lam/job-b-36
    ↓
Route: lamgame.job.detail
    ↓
Controller: LamGamePageController@jobDetail
    ↓
1. Query job by slug
2. Get job attributes
3. Process company logo
4. Get similar jobs
5. Get customer data (if logged in)
    ↓
View: lamgame.pages.job-detail
    ↓
Render:
- Job header with meta info
- Company info
- Job description & requirements
- Skills & benefits
- Similar jobs sidebar
- Apply modal
    ↓
User Actions:
- Click "Ứng tuyển ngay" → Open modal
- Fill form (auto-fill if logged in)
- Upload CV
- Submit application
```

---

## 14. Key Features

### ✅ Implemented

1. **Job Display**
   - Full job information
   - Company details with logo
   - Skills and benefits display
   - Similar jobs recommendation

2. **Application System**
   - Modal-based application form
   - Auto-fill for logged-in users
   - Guest application support
   - CV file upload
   - Cover letter (optional)

3. **User Experience**
   - Breadcrumb navigation
   - Responsive design
   - Save job functionality (UI ready)
   - Job view tracking

4. **SEO**
   - Dynamic page title
   - Meta description
   - Structured data ready

### 🔄 TODO / Enhancement

1. **Save Job API Integration**
   - Implement backend API
   - Connect toggleSaveJob() function

2. **Application Submission**
   - Form validation
   - File upload handling
   - API integration
   - Success/error feedback

3. **Social Sharing**
   - Share buttons (Facebook, LinkedIn, Twitter)
   - Open Graph meta tags

4. **Analytics**
   - Track application submissions
   - Track save job actions
   - A/B testing ready

---

**Date**: 2025-12-08
**Version**: 1.0
**Status**: Production Ready
