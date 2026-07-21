@extends('layouts.master')

@section('page_title', $page_title ?? 'Chi tiết việc làm - Làm Game')
@section('page_description', $page_description ?? 'Thông tin chi tiết về cơ hội việc làm trong ngành game development')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="job-id" content="{{ $job->id }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">

    {{-- JobPosting Structured Data --}}
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::jobPosting($job) !!}
    </script>

    {{-- BreadcrumbList Structured Data --}}
    <script type="application/ld+json">
    {!! \App\Helpers\StructuredDataHelper::breadcrumb([
        ['name' => 'Trang chủ', 'url' => config('app.url')],
        ['name' => 'Việc làm Game', 'url' => config('app.url') . '/viec-lam-game'],
        ['name' => $job->title ?? $job->name, 'url' => config('app.url') . '/viec-lam/' . $job->url_key]
    ]) !!}
    </script>
    <!-- Highlight Cards CSS -->
    <link rel="stylesheet" href="{{ asset('css/job-detail-highlight.css') }}">
    <link rel="stylesheet" href="{{ asset('css/job-detail-dark.css') }}">
    
    <!-- Editor Content CSS -->
    <link rel="stylesheet" href="{{ asset('css/editor-content.css') }}">
    
    <!-- Customer data for auto-fill -->
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
    
    <!-- Job tracking -->
    <script>
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
    </script>
@endpush

@section('content')
<div class="job-detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb-nav">
                <a href="{{ url('/') }}" class="breadcrumb-link">Trang chủ</a>
                <span class="breadcrumb-separator">›</span>
                <a href="{{ route('lamgame.viec-lam-game') }}" class="breadcrumb-link">Việc làm Game</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current">{{ $jobTitle }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <!-- Main Column -->
                <div class="main-column">
                    <!-- Job Header Card -->
                    <div class="job-header-card">
                        <div class="job-header-content">
                            <div class="job-info">
                                <h1 class="job-title">{{ $jobTitle }}</h1>
                                <div class="company-name">
                                    @if($job->company_id)
                                    <a href="{{ route('lamgame.company.profile', $job->company_id) }}" style="color:inherit;text-decoration:none;border-bottom:1px dashed rgba(255,255,255,.3)">{{ $companyName }}</a>
                                    @else
                                    {{ $companyName }}
                                    @endif
                                </div>
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

                                <!-- Job Quick Info: Skills & Benefits Combined -->
                                @if(
                                    (isset($job->attributes['required_skills']) && !empty($job->attributes['required_skills'])) ||
                                    (isset($job->attributes['job_benefits']) && !empty($job->attributes['job_benefits']))
                                )
                                <div class="job-quick-info">
                                    <!-- Skills Row -->
                                    @if(isset($job->attributes['required_skills']) && !empty($job->attributes['required_skills']))
                                    @php
                                        $skills = array_map('trim', explode(',', $job->attributes['required_skills']));
                                        $visibleSkills = array_slice($skills, 0, 4);
                                        $hiddenSkillsCount = max(0, count($skills) - 4);
                                    @endphp
                                    <div class="quick-info-row">
                                        <span class="quick-info-label">Kỹ năng:</span>
                                        <div class="quick-info-content">
                                            @foreach($visibleSkills as $skill)
                                                <span class="info-pill">{{ $skill }}</span>
                                            @endforeach
                                            @if($hiddenSkillsCount > 0)
                                                <button type="button" class="show-more-link" onclick="toggleQuickSkills(this)" data-count="{{ $hiddenSkillsCount }}">
                                                    +{{ $hiddenSkillsCount }} kỹ năng
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($hiddenSkillsCount > 0)
                                    <div class="quick-info-content hidden-skills" style="display: none; padding-left: 82px;">
                                        @foreach(array_slice($skills, 4) as $skill)
                                            <span class="info-pill">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                    @endif

                                    <!-- Benefits Row -->
                                    @if(isset($job->attributes['job_benefits']) && !empty($job->attributes['job_benefits']))
                                    @php
                                        $benefits = array_map('trim', explode(',', $job->attributes['job_benefits']));
                                        $visibleBenefits = array_slice($benefits, 0, 4);
                                        $hiddenBenefitsCount = max(0, count($benefits) - 4);
                                    @endphp
                                    <div class="quick-info-row">
                                        <span class="quick-info-label">Phúc lợi:</span>
                                        <div class="quick-info-content">
                                            @foreach($visibleBenefits as $benefit)
                                                <span class="info-pill">{{ $benefit }}</span>
                                            @endforeach
                                            @if($hiddenBenefitsCount > 0)
                                                <button type="button" class="show-more-link" onclick="toggleQuickBenefits(this)" data-count="{{ $hiddenBenefitsCount }}">
                                                    +{{ $hiddenBenefitsCount }} phúc lợi
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($hiddenBenefitsCount > 0)
                                    <div class="quick-info-content hidden-benefits" style="display: none; padding-left: 82px;">
                                        @foreach(array_slice($benefits, 4) as $benefit)
                                            <span class="info-pill">{{ $benefit }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                @endif
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

                    <!-- Main Content Sections -->
                    <div class="content-sections">
                        <!-- Job Description -->
                        <div class="content-section">
                            <h2 class="section-title">Mô tả công việc</h2>
                            <div class="section-content editor-content">
                                @if($job->description)
                                    {!! \App\Helpers\HtmlSanitizer::sanitize($job->description) !!}
                                @else
                                    <p>Thông tin mô tả công việc sẽ được cập nhật sớm.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Info -->
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Similar Jobs Card -->
                    @if($similarJobs->count() > 0)
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Việc làm tương tự</h3>
                        <div class="similar-jobs">
                            @foreach($similarJobs as $similarJob)
                                @php
                                    $similarTitle = explode(' - ', $similarJob->name)[0] ?? $similarJob->name;
                                    $similarCompany = trim(str_replace(' - ', ' ', explode(' - ', $similarJob->name)[1] ?? $similarJob->name));
                                    $similarSalary = number_format($similarJob->price / 1000000, 1) . ' triệu';
                                    $similarSlug = \Str::slug($similarTitle);
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
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Apply Section -->
    <div class="bottom-apply-section">
        <div class="container">
            <div class="bottom-apply-content">
                <div class="apply-cta">
                    <h3>Sẵn sàng ứng tuyển?</h3>
                    <p>Gửi hồ sơ ngay hôm nay!</p>
                </div>
                <div class="apply-action">
                    <button class="btn-apply-bottom" onclick="openApplyModal()">
                        <i class="fa fa-paper-plane"></i>
                        <span>Ứng tuyển ngay</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
<div id="applyModal" class="modal-overlay" onclick="closeApplyModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Ứng tuyển vị trí: {{ $jobTitle }}</h3>
            <button class="modal-close" onclick="closeApplyModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <form id="applyForm" class="apply-form" enctype="multipart/form-data">
                <!-- Auth info section -->
                @if($isLoggedIn)
                <div class="auth-info-section">
                    <div class="auth-indicator">
                        <i class="fa fa-check-circle" style="color: #10b981;"></i>
                        <span>Đã đăng nhập: {{ $customer['full_name'] }}</span>
                        <small style="display: block; color: #6b7280; margin-top: 2px;">Thông tin sẽ được tự động điền</small>
                    </div>
                </div>
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" required placeholder="Nhập họ và tên của bạn">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required placeholder="email@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" required placeholder="0123456789">
                </div>

                <div class="form-group">
                    <label for="cv">Upload CV <span class="required">*</span></label>
                    <div class="file-upload-area">
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        <div class="file-upload-text">
                            <i class="fa fa-upload"></i>
                            <span>Chọn file CV (PDF, DOC, DOCX)</span>
                        </div>
                        <div id="fileName" style="display: none; margin-top: 8px;"></div>
                    </div>
                    <small class="form-help">Kích thước tối đa: 5MB</small>
                </div>

                <div class="form-group">
                    <label for="cover_letter">Thư xin việc (tùy chọn)</label>
                    <textarea id="cover_letter" name="cover_letter" rows="4" placeholder="Giới thiệu ngắn gọn về bản thân và lý do ứng tuyển..."></textarea>
                </div>
            </form>
        </div>

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

    @push('styles')
    <style>
        /* CSS Reset and Base Styles */
        * {
            box-sizing: border-box;
        }

        /* Mobile-first Job Detail Page */
        .job-detail-page {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background: #f8f9fa;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.9rem;
        }

        .breadcrumb-link {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #5a67d8;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #6c757d;
            font-size: 1.1rem;
        }

        .breadcrumb-current {
            color: #6c757d;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
            background: #fff;
        }

        .content-wrapper {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
        }

        .main-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Job Header Card */
        .job-header-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .job-header-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .job-info {
            flex: 1;
            min-width: 0;
        }

        .job-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 0.5rem 0;
            line-height: 1.3;
        }

        .company-name {
            font-size: 1.1rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .job-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .meta-item i {
            color: #667eea;
            font-size: 0.9rem;
        }

        /* Job Quick Info - Combined Skills & Benefits */
        .job-quick-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .quick-info-row {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .quick-info-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            min-width: 70px;
            flex-shrink: 0;
            line-height: 1.75;
        }

        .quick-info-content {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            flex: 1;
            align-items: center;
        }

        .quick-info-content.hidden-skills,
        .quick-info-content.hidden-benefits {
            padding-left: 82px;
            margin-top: -0.25rem;
        }

        .info-pill {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            line-height: 1.2;
        }

        .info-pill:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .show-more-link {
            color: #667eea;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s ease;
            background: none;
            border: none;
            padding: 0.375rem 0.5rem;
            text-decoration: none;
            line-height: 1.2;
        }

        .show-more-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
        }

        .btn-apply {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            color: white;
            text-decoration: none;
        }

        .btn-save {
            background: white;
            color: #667eea;
            border: 2px solid #e2e8f0;
            padding: 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-save:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .btn-save.saved {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Job Tags */
        .job-tags {
            margin: 1.5rem 0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .tag {
            background: #f0f4ff;
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }

        /* Content Sections */
        .content-sections {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .content-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f3f4;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 1rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .section-content {
            line-height: 1.7;
            color: #4a5568;
        }

        .section-content p {
            margin: 0;
        }

        /* Skills List */
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .skill-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        /* Benefits List */
        .benefits-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .benefit-item i {
            color: #10b981;
            font-size: 1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            gap: 1rem;
        }

        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #6b7280;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f3f4;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 1rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .company-stats {
            display: flex;
            justify-content: space-around;
            gap: 1rem;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 1.4rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Similar Jobs */
        .similar-jobs {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .similar-job {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .similar-job:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .similar-job-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
        }

        .similar-job-title a {
            color: #1a1a1a;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .similar-job-title a:hover {
            color: #667eea;
        }

        .similar-job-company {
            color: #667eea;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .similar-job-salary {
            color: #10b981;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Bottom Apply Section */
        .bottom-apply-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 3rem 0;
            color: white;
        }

        .bottom-apply-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .apply-cta h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .apply-cta p {
            margin: 0;
            opacity: 0.9;
        }

        .btn-apply-bottom {
            background: white;
            color: #667eea;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .btn-apply-bottom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
            color: #667eea;
            text-decoration: none;
        }

        /* Job Header */
        .job-header {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .job-info {
            flex: 1;
        }

        .job-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .company-name {
            font-size: 1.1rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 0.95rem;
            color: #666;
        }

        .job-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .job-meta i {
            color: #667eea;
        }

        /* Apply Section */
        .apply-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .bottom-apply {
            margin-top: 3rem;
            margin-bottom: 0;
            padding-top: 2rem;
            border-top: 2px solid #f8f9fa;
        }

        .btn-apply-large {
            background: #667eea;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-apply-large:hover {
            background: #5a67d8;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .btn-save-large {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-save-large:hover {
            background: #667eea;
            color: white;
        }

        /* Job Tags */
        .job-tags-section {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .job-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Job Sections */
        .job-section {
            margin-bottom: 2.5rem;
        }

        .job-section h2 {
            color: #333;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }

        .job-description,
        .job-requirements {
            color: #555;
            line-height: 1.7;
            font-size: 1rem;
        }

        .job-benefits ul {
            list-style: none;
            padding: 0;
        }

        .job-benefits li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            color: #555;
        }

        .job-benefits i {
            color: #28a745;
            font-size: 1.1rem;
        }

        .additional-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }

        .info-item strong {
            color: #333;
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Sidebar */
        .sidebar-block {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .sidebar-block h3 {
            color: #333;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #667eea;
        }

        .company-stats {
            display: flex;
            justify-content: space-around;
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        /* Similar Jobs */
        .similar-job-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .similar-job-item:last-child {
            border-bottom: none;
        }

        .similar-job-item h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .similar-job-item h4 a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .similar-job-item h4 a:hover {
            color: #667eea;
        }

        .similar-company {
            color: #667eea;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .similar-salary {
            color: #28a745;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Quick Apply */
        .quick-apply {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .quick-apply h3 {
            color: white;
            border-bottom-color: rgba(255,255,255,0.3);
        }

        .quick-apply p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 1rem;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.2);
        }

        .form-group label {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
        }

        .btn-full {
            width: 100%;
            padding: 1rem;
            background: white;
            color: #667eea;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-full:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
        }

        /* Desktop and Tablet Styles */
        @media (min-width: 769px) {
            .container {
                padding: 0 2rem;
            }

            .content-wrapper {
                grid-template-columns: 2fr 1fr;
                gap: 3rem;
            }

            .main-column {
                min-width: 0;
            }

            .job-header-content {
                gap: 1.5rem;
            }

            .logo-placeholder {
                width: 80px;
                height: 80px;
                font-size: 1.5rem;
            }

            .job-title {
                font-size: 2rem;
            }

            .job-meta {
                grid-template-columns: repeat(4, 1fr);
            }

            .action-buttons {
                grid-template-columns: 1fr auto;
            }
        }

        /* Tablet Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem 0;
            }

            .job-header-card,
            .content-section,
            .sidebar-card {
                padding: 1.25rem;
            }

            .bottom-apply-content {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .company-stats {
                flex-direction: row;
                justify-content: center;
                gap: 2rem;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .container {
                padding: 0 1rem;
            }

            .main-content {
                padding: 1rem 0;
            }

            .job-header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .job-title {
                font-size: 1.4rem;
            }

            .job-meta {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                justify-items: center;
            }

            .action-buttons {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .btn-save span {
                display: none;
            }

            .breadcrumb-nav {
                font-size: 0.8rem;
            }

            .breadcrumb-separator {
                margin: 0 0.25rem;
            }

            .bottom-apply-section {
                padding: 2rem 0;
            }

            .apply-cta h3 {
                font-size: 1.2rem;
            }

            /* Job Quick Info Mobile */
            .job-quick-info {
                padding: 0.875rem 1rem;
            }
            
            .quick-info-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .quick-info-label {
                min-width: auto;
            }
            
            .quick-info-content.hidden-skills,
            .quick-info-content.hidden-benefits {
                padding-left: 0;
            }
        }

        /* Apply Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-container {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2rem 1rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .modal-header h3 {
            color: #1a1a1a;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            flex: 1;
            padding-right: 1rem;
        }

        .modal-close {
            background: #f8f9fa;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #6b7280;
        }

        .modal-close:hover {
            background: #e9ecef;
            color: #374151;
        }

        .modal-body {
            padding: 1.5rem 2rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            padding: 1rem 2rem 2rem;
            justify-content: flex-end;
        }

        /* Apply Form */
        .apply-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Auth Info Section */
        .auth-info-section {
            background: #f8f9ff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .auth-indicator {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .guest-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .guest-message {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            flex: 1;
        }

        .guest-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .btn-quick-login,
        .btn-quick-register {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            border: 1px solid;
            white-space: nowrap;
        }

        .btn-quick-login {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .btn-quick-login:hover {
            background: #5a67d8;
            border-color: #5a67d8;
            color: white;
            text-decoration: none;
        }

        .btn-quick-register {
            background: white;
            color: #667eea;
            border-color: #667eea;
        }

        .btn-quick-register:hover {
            background: #f8f9ff;
            color: #5a67d8;
            border-color: #5a67d8;
            text-decoration: none;
        }

        .auth-indicator i,
        .guest-message i {
            margin-top: 2px;
        }

        /* Responsive adjustments for guest actions */
        @media (max-width: 768px) {
            .guest-info {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .guest-actions {
                justify-content: center;
            }

            .btn-quick-login,
            .btn-quick-register {
                flex: 1;
                justify-content: center;
                padding: 0.5rem 1rem;
            }
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 0.875rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
            background: white;
            color: #374151;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .form-group input:-ms-input-placeholder,
        .form-group textarea:-ms-input-placeholder {
            color: #9ca3af;
        }

        .form-group input::-webkit-input-placeholder,
        .form-group textarea::-webkit-input-placeholder {
            color: #9ca3af;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* File Upload */
        .file-upload-area {
            position: relative;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.2s ease;
            background: #fafafa;
        }

            .file-upload-area:hover {
                background-color: #f8f9ff;
                border-color: #3b82f6;
            }

            .file-upload-area.drag-over {
                background-color: #f0f9ff;
                border-color: #3b82f6;
                border-style: solid;
                transform: scale(1.02);
            }

            .file-upload-area {
                transition: all 0.3s ease;
                cursor: pointer;
            }

            /* Error states */
            input.error,
            textarea.error,
            select.error {
                border-color: #dc2626 !important;
                background-color: #fef2f2;
                color: #374151;
            }

            /* Readonly states */
            input[readonly],
            textarea[readonly],
            select[readonly] {
                background-color: #f8f9fa !important;
                border-color: #e2e8f0 !important;
                color: #6b7280 !important;
                cursor: not-allowed;
            }

            input[readonly]:focus,
            textarea[readonly]:focus,
            select[readonly]:focus {
                box-shadow: none !important;
                border-color: #e2e8f0 !important;
            }

            .field-error {
                color: #dc2626;
                font-size: 12px;
                margin-top: 4px;
                display: block;
                font-weight: 500;
            }

        .file-upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-weight: 500;
        }

        .file-upload-text i {
            font-size: 2rem;
            color: #9ca3af;
        }

        .form-help {
            color: #6b7280;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        /* File upload success state */
        #fileName {
            padding: 0.5rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            margin-top: 0.5rem;
        }

        #fileName div {
            font-weight: 600;
            color: #059669 !important;
        }

        /* Modal Buttons */
        .btn-cancel {
            background: #f8f9fa;
            color: #6b7280 !important;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .btn-cancel:hover {
            background: #e9ecef;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white !important;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Mobile Modal */
        @media (max-width: 768px) {
            .modal-container {
                margin: 0.5rem;
                max-height: 95vh;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            .modal-footer {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
                justify-content: center;
                padding: 1rem 2rem;
            }

            .file-upload-area {
                padding: 1.5rem 1rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('js/job-detail-highlight.js') }}"></script>
    <script>
        // REMOVED DUPLICATE SCRIPT - Form submission is handled by job-detail-modal.js
        // This fixes the duplicate submission issue
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Job detail page initialized - using external modal script');
        });
    </script>
    <!-- Load job modal script -->
    <script src="{{ asset('js/job-detail-modal.js') }}"></script>
    @endpush

@endsection
