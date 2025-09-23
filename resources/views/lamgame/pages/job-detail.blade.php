@extends('layouts.master')

@section('page_title', $page_title ?? 'Chi tiết việc làm - Làm Game')
@section('page_description', $page_description ?? 'Thông tin chi tiết về cơ hội việc làm trong ngành game development')

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
                <!-- Job Header Card -->
                <div class="job-header-card">
                    <div class="job-header-content">
                        <div class="company-logo">
                            <div class="logo-placeholder">
                                {{ strtoupper(substr($companyName, 0, 2)) }}
                            </div>
                        </div>
                        <div class="job-info">
                            <h1 class="job-title">{{ $jobTitle }}</h1>
                            <div class="company-name">{{ $companyName }}</div>
                            <div class="job-meta">
                                <div class="meta-item">
                                    <i class="fa fa-map-marker"></i>
                                    <span>Việt Nam</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-money"></i>
                                    <span>{{ $salaryFormatted }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fa fa-clock-o"></i>
                                    <span>Full-time</span>
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

                <!-- Job Tags -->
                @if(isset($job->attributes['job_type']) || isset($job->attributes['experience_level']) || $job->category_name)
                <div class="job-tags">
                    @if(isset($job->attributes['job_type']))
                        <span class="tag">{{ $job->attributes['job_type'] }}</span>
                    @endif
                    @if(isset($job->attributes['experience_level']))
                        <span class="tag">{{ $job->attributes['experience_level'] }}</span>
                    @endif
                    @if($job->category_name)
                        <span class="tag">{{ $job->category_name }}</span>
                    @endif
                    <span class="tag">Game Development</span>
                </div>
                @endif

                <!-- Main Content Sections -->
                <div class="content-sections">
                    <!-- Job Description -->
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

                    <!-- Job Requirements -->
                    @if($job->short_description)
                    <div class="content-section">
                        <h2 class="section-title">Yêu cầu công việc</h2>
                        <div class="section-content">
                            {!! nl2br($job->short_description) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Benefits -->
                    <div class="content-section">
                        <h2 class="section-title">Quyền lợi</h2>
                        <div class="section-content">
                            <div class="benefits-list">
                                <div class="benefit-item">
                                    <i class="fa fa-check-circle"></i>
                                    <span>Mức lương cạnh tranh: {{ $salaryFormatted }}</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fa fa-check-circle"></i>
                                    <span>Môi trường làm việc chuyên nghiệp</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fa fa-check-circle"></i>
                                    <span>Cơ hội phát triển nghề nghiệp</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fa fa-check-circle"></i>
                                    <span>Bảo hiểm y tế và xã hội đầy đủ</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fa fa-check-circle"></i>
                                    <span>Các hoạt động team building, du lịch</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if(isset($job->attributes['skills_required']) || $job->application_deadline)
                    <div class="content-section">
                        <h2 class="section-title">Thông tin thêm</h2>
                        <div class="section-content">
                            <div class="info-grid">
                                @if(isset($job->attributes['skills_required']))
                                <div class="info-item">
                                    <div class="info-label">Kỹ năng yêu cầu</div>
                                    <div class="info-value">{{ $job->attributes['skills_required'] }}</div>
                                </div>
                                @endif
                                @if($job->application_deadline)
                                <div class="info-item">
                                    <div class="info-label">Hạn ứng tuyển</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($job->application_deadline)->format('d/m/Y') }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Company Info Card -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">Về công ty</h3>
                        <div class="company-info">
                            <div class="company-logo-large">
                                {{ strtoupper(substr($companyName, 0, 2)) }}
                            </div>
                            <h4 class="company-name-large">{{ $companyName }}</h4>
                            <p class="company-desc">Công ty hoạt động trong lĩnh vực phát triển game, mang đến những trải nghiệm giải trí tuyệt vời.</p>
                            <div class="company-stats">
                                <div class="stat">
                                    <div class="stat-number">50+</div>
                                    <div class="stat-label">Nhân viên</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-number">5+</div>
                                    <div class="stat-label">Năm KN</div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <a href="{{ route('lamgame.job-detail', [$similarJob->id, $similarSlug]) }}">
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name">Họ và tên <span class="required">*</span></label>
                        <input type="text" id="applicant_name" name="applicant_name" required placeholder="Nhập họ và tên của bạn">
                    </div>
                    <div class="form-group">
                        <label for="applicant_email">Email <span class="required">*</span></label>
                        <input type="email" id="applicant_email" name="applicant_email" required placeholder="email@example.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="applicant_phone">Số điện thoại <span class="required">*</span></label>
                    <input type="tel" id="applicant_phone" name="applicant_phone" required placeholder="0123456789">
                </div>
                
                <div class="form-group">
                    <label for="applicant_cv">Upload CV <span class="required">*</span></label>
                    <div class="file-upload-area">
                        <input type="file" id="applicant_cv" name="applicant_cv" accept=".pdf,.doc,.docx" required>
                        <div class="file-upload-text">
                            <i class="fa fa-upload"></i>
                            <span>Chọn file CV (PDF, DOC, DOCX)</span>
                        </div>
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

        .company-logo {
            flex-shrink: 0;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
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

        /* Company Info */
        .company-info {
            text-align: center;
        }

        .company-logo-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0 auto 1rem auto;
        }

        .company-name-large {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 0.75rem 0;
        }

        .company-desc {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0 0 1.5rem 0;
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

        .company-logo img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
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

        /* Company Info */
        .company-info {
            text-align: center;
        }

        .company-logo-large img {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .company-info h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .company-info p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
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
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            border-color: #667eea;
            background: #f8f9ff;
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

        /* Modal Buttons */
        .btn-cancel {
            background: #f8f9fa;
            color: #6b7280;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: #e9ecef;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

            .modal-footer {
                flex-direction: column;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Toggle save job functionality
        function toggleSaveJob(button) {
            const icon = button.querySelector('i');
            const text = button.querySelector('span');
            
            if (icon.classList.contains('fa-heart-o')) {
                // Save job
                icon.classList.remove('fa-heart-o');
                icon.classList.add('fa-heart');
                button.classList.add('saved');
                if (text) text.textContent = 'Đã lưu';
                
                // Show success message
                showMessage('Đã lưu việc làm vào danh sách yêu thích!', 'success');
            } else {
                // Unsave job
                icon.classList.remove('fa-heart');
                icon.classList.add('fa-heart-o');
                button.classList.remove('saved');
                if (text) text.textContent = 'Lưu việc làm';
                
                showMessage('Đã xóa khỏi danh sách yêu thích!', 'info');
            }
        }
        
        // Show message function
        function showMessage(message, type = 'info') {
            // Create message element
            const messageEl = document.createElement('div');
            messageEl.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : '#667eea'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            messageEl.textContent = message;
            
            // Add animation styles
            if (!document.querySelector('#messageStyles')) {
                const style = document.createElement('style');
                style.id = 'messageStyles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(messageEl);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                messageEl.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (messageEl.parentNode) {
                        messageEl.parentNode.removeChild(messageEl);
                    }
                }, 300);
            }, 3000);
        }
        
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add click handler for apply buttons to open modal
            document.querySelectorAll('.btn-apply, .btn-apply-bottom').forEach(button => {
                if (button.tagName.toLowerCase() === 'button') {
                    button.addEventListener('click', openApplyModal);
                }
            });
        });

        // Modal functions
        function openApplyModal() {
            const modal = document.getElementById('applyModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeApplyModal() {
            const modal = document.getElementById('applyModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('applyModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeApplyModal();
                }
            });

            // File upload preview
            document.getElementById('cv').addEventListener('change', function(e) {
                const fileName = document.getElementById('fileName');
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileName.textContent = `✓ Đã chọn: ${file.name}`;
                    fileName.style.display = 'block';
                } else {
                    fileName.style.display = 'none';
                }
            });

            // Form submission
            document.getElementById('applyForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.querySelector('.btn-submit');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
                
                // Here you can add AJAX submission or regular form submission
                // For now, we'll simulate a delay
                setTimeout(() => {
                    alert('Cảm ơn bạn đã ứng tuyển! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
                    closeApplyModal();
                    
                    // Reset form
                    this.reset();
                    document.getElementById('fileName').style.display = 'none';
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 2000);
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeApplyModal();
                }
            });
        });
    </script>
    @endpush

    <!-- Apply Modal -->
    <div id="applyModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Ứng tuyển vị trí: {{ $jobTitle }}</h3>
                <button type="button" class="modal-close" onclick="closeApplyModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <form class="apply-form" id="applyForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Họ và tên <span class="required">*</span></label>
                            <input type="text" id="fullName" name="full_name" required placeholder="Nhập họ và tên">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required placeholder="Nhập email của bạn">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" required placeholder="Nhập số điện thoại">
                        </div>
                        
                        <div class="form-group">
                            <label for="experience">Kinh nghiệm</label>
                            <select id="experience" name="experience">
                                <option value="">Chọn kinh nghiệm</option>
                                <option value="fresher">Fresher (0-1 năm)</option>
                                <option value="junior">Junior (1-3 năm)</option>
                                <option value="middle">Middle (3-5 năm)</option>
                                <option value="senior">Senior (5+ năm)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cv">Tải CV <span class="required">*</span></label>
                        <div class="file-upload-area">
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                            <div class="file-upload-text">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div>
                                    <strong>Kéo thả CV vào đây hoặc click để chọn</strong>
                                    <div class="form-help">Hỗ trợ: PDF, DOC, DOCX (tối đa 5MB)</div>
                                </div>
                            </div>
                        </div>
                        <div id="fileName" class="form-help" style="display: none; color: #059669; margin-top: 0.5rem;"></div>
                    </div>

                    <div class="form-group">
                        <label for="coverLetter">Thư giới thiệu (tùy chọn)</label>
                        <textarea id="coverLetter" name="cover_letter" placeholder="Viết vài dòng giới thiệu về bản thân và lý do bạn phù hợp với vị trí này..."></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeApplyModal()">Hủy</button>
                <button type="submit" form="applyForm" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Gửi hồ sơ ứng tuyển
                </button>
            </div>
        </div>
    </div>
@endsection
