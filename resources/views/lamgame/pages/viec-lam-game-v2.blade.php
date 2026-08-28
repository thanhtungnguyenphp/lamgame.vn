@extends('layouts.master')

@section('page_title', $page_title ?? 'Việc làm Game - Cơ hội nghề nghiệp ngành Game')
@section('page_description', $page_description ?? 'Khám phá cơ hội việc làm Game Developer, Game Designer, 3D Artist tại Việt Nam. Kết nối với các studio game hàng đầu.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Việc làm Game Developer",
    "description": "Tìm kiếm cơ hội việc làm trong ngành game development tại Việt Nam",
    "url": "{{ url('/viec-lam-game') }}",
    "isPartOf": {
        "@type": "WebSite",
        "name": "LamGame.vn",
        "url": "{{ url('/') }}"
    }
}
</script>
@endpush

@section('content')
<div class="lg-jobs">
    {{-- Hero Section --}}
    <section class="lg-jobs__hero">
        <div class="lg-jobs__hero-bg"></div>
        <div class="lg-v2-container">
            <div class="lg-jobs__hero-content">
                <span class="lg-jobs__badge">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    Game Industry Careers
                </span>
                <h1 class="lg-jobs__title">
                    Tìm <span class="lg-jobs__title-accent">việc làm Game</span> phù hợp với bạn
                </h1>
                <p class="lg-jobs__subtitle">
                    Kết nối với các studio game hàng đầu Việt Nam. Khám phá cơ hội nghề nghiệp trong ngành công nghiệp game đang phát triển mạnh mẽ.
                </p>
                
                {{-- Hero Stats --}}
                <div class="lg-jobs__stats">
                    <div class="lg-jobs__stat">
                        <span class="lg-jobs__stat-value">{{ $totalJobs }}</span>
                        <span class="lg-jobs__stat-label">Việc làm đang tuyển</span>
                    </div>
                    <div class="lg-jobs__stat-divider"></div>
                    <div class="lg-jobs__stat">
                        <span class="lg-jobs__stat-value">{{ isset($topCompanies) ? $topCompanies->count() : 0 }}+</span>
                        <span class="lg-jobs__stat-label">Studios & Companies</span>
                    </div>
                    <div class="lg-jobs__stat-divider"></div>
                    <div class="lg-jobs__stat">
                        <span class="lg-jobs__stat-value">15-50M</span>
                        <span class="lg-jobs__stat-label">Mức lương phổ biến</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Search Section --}}
    <section class="lg-jobs__search">
        <div class="lg-v2-container">
            <form class="lg-jobs__search-form" method="GET" id="jobSearchForm">
                <div class="lg-jobs__search-main">
                    <div class="lg-jobs__search-input-wrap">
                        <svg class="lg-jobs__search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input 
                            type="text" 
                            name="keyword" 
                            class="lg-jobs__search-input"
                            placeholder="Tìm kiếm: Unity Developer, Game Designer, 3D Artist..." 
                            value="{{ $searchParams['keyword'] ?? '' }}"
                            autocomplete="off"
                        >
                        @if($searchParams['keyword'] ?? false)
                        <button type="button" class="lg-jobs__search-clear" onclick="this.previousElementSibling.value=''; this.style.display='none';">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 6L6 18M6 6l12 12"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                    <button type="submit" class="lg-jobs__search-btn">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        Tìm kiếm
                    </button>
                </div>

                {{-- Quick Filters --}}
                <div class="lg-jobs__filters">
                    <div class="lg-jobs__filter-group">
                        <label class="lg-jobs__filter-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Địa điểm
                        </label>
                        <select name="location" class="lg-jobs__filter-select">
                            <option value="">Tất cả</option>
                            <option value="ho-chi-minh" {{ ($searchParams['location'] ?? '') == 'ho-chi-minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                            <option value="ha-noi" {{ ($searchParams['location'] ?? '') == 'ha-noi' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="da-nang" {{ ($searchParams['location'] ?? '') == 'da-nang' ? 'selected' : '' }}>Đà Nẵng</option>
                            <option value="remote" {{ ($searchParams['location'] ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                    </div>
                    
                    <div class="lg-jobs__filter-group">
                        <label class="lg-jobs__filter-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 20V10M18 20V4M6 20v-4"/>
                            </svg>
                            Cấp độ
                        </label>
                        <select name="level" class="lg-jobs__filter-select">
                            <option value="">Tất cả</option>
                            <option value="intern" {{ ($searchParams['level'] ?? '') == 'intern' ? 'selected' : '' }}>Intern</option>
                            <option value="fresher" {{ ($searchParams['level'] ?? '') == 'fresher' ? 'selected' : '' }}>Fresher</option>
                            <option value="junior" {{ ($searchParams['level'] ?? '') == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="senior" {{ ($searchParams['level'] ?? '') == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ ($searchParams['level'] ?? '') == 'lead' ? 'selected' : '' }}>Lead/Manager</option>
                        </select>
                    </div>

                    <div class="lg-jobs__filter-group">
                        <label class="lg-jobs__filter-label">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="7" width="20" height="14" rx="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            Loại hình
                        </label>
                        <select name="type" class="lg-jobs__filter-select">
                            <option value="">Tất cả</option>
                            <option value="fulltime" {{ ($searchParams['type'] ?? '') == 'fulltime' ? 'selected' : '' }}>Full-time</option>
                            <option value="parttime" {{ ($searchParams['type'] ?? '') == 'parttime' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ ($searchParams['type'] ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="freelance" {{ ($searchParams['type'] ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                </div>

                {{-- Quick Tags --}}
                <div class="lg-jobs__quick-tags">
                    <span class="lg-jobs__quick-label">Phổ biến:</span>
                    <button type="button" class="lg-jobs__quick-tag {{ ($searchParams['keyword'] ?? '') == 'Unity' ? 'active' : '' }}" data-keyword="Unity">Unity</button>
                    <button type="button" class="lg-jobs__quick-tag {{ ($searchParams['keyword'] ?? '') == 'Unreal' ? 'active' : '' }}" data-keyword="Unreal">Unreal</button>
                    <button type="button" class="lg-jobs__quick-tag {{ ($searchParams['location'] ?? '') == 'remote' ? 'active' : '' }}" data-location="remote">Remote</button>
                    <button type="button" class="lg-jobs__quick-tag {{ ($searchParams['keyword'] ?? '') == 'Game Designer' ? 'active' : '' }}" data-keyword="Game Designer">Game Designer</button>
                    <button type="button" class="lg-jobs__quick-tag {{ ($searchParams['keyword'] ?? '') == '3D Artist' ? 'active' : '' }}" data-keyword="3D Artist">3D Artist</button>
                </div>
            </form>
        </div>
    </section>

    {{-- Results Section --}}
    <section class="lg-jobs__results">
        <div class="lg-v2-container">
            {{-- Results Header --}}
            <div class="lg-jobs__results-header">
                <div class="lg-jobs__results-count">
                    <strong>{{ $totalJobs }}</strong> việc làm 
                    @if($searchParams['keyword'] ?? false)
                        cho "<em>{{ $searchParams['keyword'] }}</em>"
                    @endif
                </div>
                <div class="lg-jobs__results-sort">
                    <label>Sắp xếp:</label>
                    <select name="sort" form="jobSearchForm" onchange="document.getElementById('jobSearchForm').submit()">
                        <option value="newest" {{ ($searchParams['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="salary-high" {{ ($searchParams['sort'] ?? '') == 'salary-high' ? 'selected' : '' }}>Lương cao nhất</option>
                        <option value="company" {{ ($searchParams['sort'] ?? '') == 'company' ? 'selected' : '' }}>Theo công ty</option>
                    </select>
                </div>
            </div>

            {{-- Job Grid --}}
            <div class="lg-jobs__grid">
                {{-- Main Job List --}}
                <div class="lg-jobs__list">
                    @forelse($jobs as $index => $job)
                        @php
                            $salaryFormatted = $job->attributes['salary_range'] ?? 'Thỏa thuận';
                            $location = $job->attributes['job_location'] ?? 'Việt Nam';
                            $jobType = $job->attributes['job_type'] ?? 'Full-time';
                            $postedAgo = \Carbon\Carbon::parse($job->created_at)->diffForHumans();
                            $isFeatured = $index < 2;
                            $experienceLevel = $job->attributes['experience_level'] ?? null;
                            $skills = isset($job->attributes['required_skills']) ? array_slice(explode(',', $job->attributes['required_skills']), 0, 3) : [];
                        @endphp
                        
                        <article class="lg-jobs__card {{ $isFeatured ? 'lg-jobs__card--featured' : '' }}">
                            @if($isFeatured)
                            <div class="lg-jobs__card-badge">⭐ Nổi bật</div>
                            @endif
                            
                            <div class="lg-jobs__card-header">
                                <div class="lg-jobs__card-logo">
                                    @if($job->company_logo_url)
                                        <img src="{{ $job->company_logo_url }}" alt="{{ $job->company_name }}">
                                    @else
                                        <span class="lg-jobs__card-logo-text">{{ strtoupper(substr($job->company_name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div class="lg-jobs__card-info">
                                    <h3 class="lg-jobs__card-title">
                                        <a href="{{ route('lamgame.job.detail', $job->url_key) }}">{{ $job->job_title }}</a>
                                    </h3>
                                    <div class="lg-jobs__card-company">{{ $job->company_name }}</div>
                                </div>
                            </div>

                            <div class="lg-jobs__card-meta">
                                <span class="lg-jobs__card-salary">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                    </svg>
                                    {{ $salaryFormatted }}
                                </span>
                                <span class="lg-jobs__card-location">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $location }}
                                </span>
                                <span class="lg-jobs__card-type">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                    {{ $jobType }}
                                </span>
                            </div>

                            <p class="lg-jobs__card-desc">{!! Str::limit(strip_tags($job->processed_description), 120) !!}</p>

                            <div class="lg-jobs__card-tags">
                                @if($experienceLevel)
                                <span class="lg-jobs__tag lg-jobs__tag--level">{{ $experienceLevel }}</span>
                                @endif
                                @foreach($skills as $skill)
                                <span class="lg-jobs__tag">{{ trim($skill) }}</span>
                                @endforeach
                            </div>

                            <div class="lg-jobs__card-footer">
                                <span class="lg-jobs__card-time">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                                    </svg>
                                    {{ $postedAgo }}
                                </span>
                                <div class="lg-jobs__card-actions">
                                    <a href="{{ route('lamgame.job.detail', $job->url_key) }}" class="lg-jobs__btn lg-jobs__btn--outline">Chi tiết</a>
                                    <a href="{{ route('lamgame.job.detail', $job->url_key) }}#apply" class="lg-jobs__btn lg-jobs__btn--primary">Ứng tuyển</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="lg-jobs__empty">
                            <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                            <h3>Không tìm thấy việc làm</h3>
                            <p>Hãy thử thay đổi từ khóa hoặc bộ lọc tìm kiếm</p>
                            <a href="{{ url('/viec-lam-game') }}" class="lg-jobs__btn lg-jobs__btn--primary">Xem tất cả việc làm</a>
                        </div>
                    @endforelse
                </div>

                {{-- Sidebar --}}
                <aside class="lg-jobs__sidebar">
                    {{-- Top Companies --}}
                    <div class="lg-jobs__sidebar-card">
                        <h3 class="lg-jobs__sidebar-title">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                            </svg>
                            Công ty hàng đầu
                        </h3>
                        <div class="lg-jobs__companies">
                            @forelse($topCompanies as $company)
                            <div class="lg-jobs__company">
                                <div class="lg-jobs__company-logo">
                                    @if($company->logo)
                                        @php
                                            $path = 'company-logos/' . basename($company->logo);
                                            $logoUrl = null;
                                            if (\Storage::disk('public')->exists($path)) {
                                                try {
                                                    $file = \Storage::disk('public')->get($path);
                                                    $mimeType = \Storage::disk('public')->mimeType($path);
                                                    $logoUrl = 'data:' . $mimeType . ';base64,' . base64_encode($file);
                                                } catch (\Exception $e) {}
                                            }
                                        @endphp
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" alt="{{ $company->company_name }}">
                                        @else
                                            <span>{{ strtoupper(substr($company->company_name, 0, 2)) }}</span>
                                        @endif
                                    @else
                                        <span>{{ strtoupper(substr($company->company_name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div class="lg-jobs__company-info">
                                    <strong>{{ $company->company_name }}</strong>
                                    <span>{{ $company->job_count }} việc làm</span>
                                </div>
                            </div>
                            @empty
                            <p class="lg-jobs__sidebar-empty">Chưa có dữ liệu</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Salary Guide --}}
                    <div class="lg-jobs__sidebar-card">
                        <h3 class="lg-jobs__sidebar-title">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            Mức lương tham khảo
                        </h3>
                        <div class="lg-jobs__salary-guide">
                            <div class="lg-jobs__salary-item">
                                <span class="lg-jobs__salary-role">Unity Developer</span>
                                <span class="lg-jobs__salary-range">15-35 triệu</span>
                            </div>
                            <div class="lg-jobs__salary-item">
                                <span class="lg-jobs__salary-role">Game Designer</span>
                                <span class="lg-jobs__salary-range">12-28 triệu</span>
                            </div>
                            <div class="lg-jobs__salary-item">
                                <span class="lg-jobs__salary-role">3D Artist</span>
                                <span class="lg-jobs__salary-range">10-25 triệu</span>
                            </div>
                            <div class="lg-jobs__salary-item">
                                <span class="lg-jobs__salary-role">QA Tester</span>
                                <span class="lg-jobs__salary-range">8-18 triệu</span>
                            </div>
                            <div class="lg-jobs__salary-item">
                                <span class="lg-jobs__salary-role">Technical Lead</span>
                                <span class="lg-jobs__salary-range">35-60 triệu</span>
                            </div>
                        </div>
                    </div>

                    {{-- Job Alert --}}
                    <div class="lg-jobs__sidebar-card lg-jobs__sidebar-card--highlight">
                        <h3 class="lg-jobs__sidebar-title">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            Nhận thông báo việc làm
                        </h3>
                        <p class="lg-jobs__sidebar-desc">Đăng ký để nhận email khi có việc làm mới phù hợp với bạn.</p>
                        <form class="lg-jobs__alert-form">
                            <input type="email" placeholder="Email của bạn" required>
                            <button type="submit" class="lg-jobs__btn lg-jobs__btn--primary lg-jobs__btn--full">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                </svg>
                                Đăng ký ngay
                            </button>
                        </form>
                    </div>
                </aside>
            </div>

            {{-- Pagination --}}
            @if($jobs->hasPages())
            <div class="lg-jobs__pagination">
                {{ $jobs->appends(request()->query())->links('lamgame.components.custom-pagination') }}
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
/* ========================================
   JOBS PAGE V2 — DARK THEME
   Consistent with LamGame.vn V2 Design
======================================== */

:root {
    --jobs-bg: #0D0D1A;
    --jobs-surface: #161625;
    --jobs-surface-hover: #1E1E30;
    --jobs-border: #2A2A40;
    --jobs-text: #F0F0F5;
    --jobs-text-muted: #8B8BA3;
    --jobs-accent: #8B5CF6;
    --jobs-accent-hover: #7C3AED;
    --jobs-success: #10B981;
    --jobs-warning: #F59E0B;
    --jobs-gradient: linear-gradient(135deg, #8B5CF6, #06B6D4);
}

.lg-jobs {
    background: var(--jobs-bg);
    min-height: 100vh;
}

/* Hero */
.lg-jobs__hero {
    position: relative;
    padding: 60px 0 40px;
    overflow: hidden;
}

.lg-jobs__hero-bg {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(ellipse 80% 50% at 50% -20%, rgba(139, 92, 246, 0.15), transparent),
        radial-gradient(ellipse 60% 40% at 80% 50%, rgba(6, 182, 212, 0.1), transparent);
    pointer-events: none;
}

.lg-jobs__hero-content {
    position: relative;
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
}

.lg-jobs__badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.3);
    color: var(--jobs-accent);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 16px;
}

.lg-jobs__title {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 800;
    color: var(--jobs-text);
    margin-bottom: 16px;
    line-height: 1.2;
}

.lg-jobs__title-accent {
    background: var(--jobs-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lg-jobs__subtitle {
    color: var(--jobs-text-muted);
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 32px;
}

.lg-jobs__stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.lg-jobs__stat {
    text-align: center;
}

.lg-jobs__stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--jobs-accent);
}

.lg-jobs__stat-label {
    font-size: 0.75rem;
    color: var(--jobs-text-muted);
}

.lg-jobs__stat-divider {
    width: 1px;
    height: 40px;
    background: var(--jobs-border);
}

/* Search Section */
.lg-jobs__search {
    padding: 0 0 32px;
    margin-top: -20px;
}

.lg-jobs__search-form {
    background: var(--jobs-surface);
    border: 1px solid var(--jobs-border);
    border-radius: 16px;
    padding: 24px;
}

.lg-jobs__search-main {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.lg-jobs__search-input-wrap {
    flex: 1;
    position: relative;
}

.lg-jobs__search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--jobs-text-muted);
    pointer-events: none;
}

.lg-jobs__search-input {
    width: 100%;
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    border-radius: 12px;
    padding: 14px 16px 14px 48px;
    color: var(--jobs-text);
    font-size: 0.95rem;
    transition: all 0.2s;
}

.lg-jobs__search-input:focus {
    outline: none;
    border-color: var(--jobs-accent);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.lg-jobs__search-input::placeholder {
    color: var(--jobs-text-muted);
}

.lg-jobs__search-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--jobs-text-muted);
    cursor: pointer;
    padding: 4px;
}

.lg-jobs__search-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--jobs-accent);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 14px 24px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}

.lg-jobs__search-btn:hover {
    background: var(--jobs-accent-hover);
}

/* Filters */
.lg-jobs__filters {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--jobs-border);
    margin-bottom: 16px;
}

.lg-jobs__filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.lg-jobs__filter-label {
    display: flex;
    align-items: center;
    gap: 4px;
    color: var(--jobs-text-muted);
    font-size: 0.8rem;
    white-space: nowrap;
}

.lg-jobs__filter-select {
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    border-radius: 8px;
    padding: 8px 12px;
    color: var(--jobs-text);
    font-size: 0.85rem;
    cursor: pointer;
}

.lg-jobs__filter-select:focus {
    outline: none;
    border-color: var(--jobs-accent);
}

/* Quick Tags */
.lg-jobs__quick-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.lg-jobs__quick-label {
    color: var(--jobs-text-muted);
    font-size: 0.8rem;
}

.lg-jobs__quick-tag {
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    color: var(--jobs-text-muted);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}

.lg-jobs__quick-tag:hover,
.lg-jobs__quick-tag.active {
    background: var(--jobs-accent);
    border-color: var(--jobs-accent);
    color: white;
}

/* Results Section */
.lg-jobs__results {
    padding: 0 0 60px;
}

.lg-jobs__results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.lg-jobs__results-count {
    color: var(--jobs-text-muted);
    font-size: 0.9rem;
}

.lg-jobs__results-count strong {
    color: var(--jobs-text);
}

.lg-jobs__results-count em {
    color: var(--jobs-accent);
    font-style: normal;
}

.lg-jobs__results-sort {
    display: flex;
    align-items: center;
    gap: 8px;
}

.lg-jobs__results-sort label {
    color: var(--jobs-text-muted);
    font-size: 0.85rem;
}

.lg-jobs__results-sort select {
    background: var(--jobs-surface);
    border: 1px solid var(--jobs-border);
    border-radius: 8px;
    padding: 8px 12px;
    color: var(--jobs-text);
    font-size: 0.85rem;
}

/* Grid Layout */
.lg-jobs__grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
}

/* Job Cards */
.lg-jobs__list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.lg-jobs__card {
    background: var(--jobs-surface);
    border: 1px solid var(--jobs-border);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s ease;
    position: relative;
}

.lg-jobs__card:hover {
    border-color: var(--jobs-accent);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(139, 92, 246, 0.1);
}

.lg-jobs__card--featured {
    border-color: var(--jobs-warning);
    background: linear-gradient(135deg, var(--jobs-surface), rgba(245, 158, 11, 0.05));
}

.lg-jobs__card-badge {
    position: absolute;
    top: -1px;
    right: 24px;
    background: var(--jobs-warning);
    color: #000;
    padding: 4px 12px;
    border-radius: 0 0 8px 8px;
    font-size: 0.7rem;
    font-weight: 600;
}

.lg-jobs__card-header {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.lg-jobs__card-logo {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}

.lg-jobs__card-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 4px;
}

.lg-jobs__card-logo-text {
    font-weight: 700;
    font-size: 1rem;
    color: var(--jobs-accent);
}

.lg-jobs__card-info {
    flex: 1;
    min-width: 0;
}

.lg-jobs__card-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 4px;
}

.lg-jobs__card-title a {
    color: var(--jobs-text);
    text-decoration: none;
    transition: color 0.2s;
}

.lg-jobs__card-title a:hover {
    color: var(--jobs-accent);
}

.lg-jobs__card-company {
    color: var(--jobs-text-muted);
    font-size: 0.9rem;
}

.lg-jobs__card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 12px;
}

.lg-jobs__card-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: var(--jobs-text-muted);
}

.lg-jobs__card-meta svg {
    flex-shrink: 0;
}

.lg-jobs__card-salary {
    color: var(--jobs-success) !important;
    font-weight: 600;
}

.lg-jobs__card-desc {
    color: var(--jobs-text-muted);
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 12px;
}

.lg-jobs__card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.lg-jobs__tag {
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    color: var(--jobs-text-muted);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
}

.lg-jobs__tag--level {
    background: rgba(139, 92, 246, 0.1);
    border-color: rgba(139, 92, 246, 0.3);
    color: var(--jobs-accent);
}

.lg-jobs__card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--jobs-border);
}

.lg-jobs__card-time {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--jobs-text-muted);
    font-size: 0.8rem;
}

.lg-jobs__card-actions {
    display: flex;
    gap: 8px;
}

/* Buttons */
.lg-jobs__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
}

.lg-jobs__btn--primary {
    background: var(--jobs-accent);
    color: white;
}

.lg-jobs__btn--primary:hover {
    background: var(--jobs-accent-hover);
}

.lg-jobs__btn--outline {
    background: transparent;
    border: 1px solid var(--jobs-border);
    color: var(--jobs-text);
}

.lg-jobs__btn--outline:hover {
    border-color: var(--jobs-accent);
    color: var(--jobs-accent);
}

.lg-jobs__btn--full {
    width: 100%;
}

/* Sidebar */
.lg-jobs__sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.lg-jobs__sidebar-card {
    background: var(--jobs-surface);
    border: 1px solid var(--jobs-border);
    border-radius: 16px;
    padding: 20px;
}

.lg-jobs__sidebar-card--highlight {
    background: linear-gradient(135deg, var(--jobs-surface), rgba(139, 92, 246, 0.05));
    border-color: rgba(139, 92, 246, 0.3);
}

.lg-jobs__sidebar-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 700;
    color: var(--jobs-text);
    margin-bottom: 16px;
}

.lg-jobs__sidebar-title svg {
    color: var(--jobs-accent);
}

.lg-jobs__sidebar-desc {
    color: var(--jobs-text-muted);
    font-size: 0.85rem;
    margin-bottom: 16px;
}

/* Companies List */
.lg-jobs__companies {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.lg-jobs__company {
    display: flex;
    align-items: center;
    gap: 12px;
}

.lg-jobs__company-logo {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.lg-jobs__company-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 4px;
}

.lg-jobs__company-logo span {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--jobs-accent);
}

.lg-jobs__company-info strong {
    display: block;
    font-size: 0.85rem;
    color: var(--jobs-text);
}

.lg-jobs__company-info span {
    font-size: 0.75rem;
    color: var(--jobs-text-muted);
}

/* Salary Guide */
.lg-jobs__salary-guide {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.lg-jobs__salary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--jobs-border);
}

.lg-jobs__salary-item:last-child {
    border-bottom: none;
}

.lg-jobs__salary-role {
    color: var(--jobs-text);
    font-size: 0.85rem;
}

.lg-jobs__salary-range {
    color: var(--jobs-success);
    font-weight: 600;
    font-size: 0.85rem;
}

/* Alert Form */
.lg-jobs__alert-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.lg-jobs__alert-form input {
    background: var(--jobs-bg);
    border: 1px solid var(--jobs-border);
    border-radius: 10px;
    padding: 12px 16px;
    color: var(--jobs-text);
    font-size: 0.9rem;
}

.lg-jobs__alert-form input:focus {
    outline: none;
    border-color: var(--jobs-accent);
}

/* Empty State */
.lg-jobs__empty {
    text-align: center;
    padding: 60px 20px;
    background: var(--jobs-surface);
    border: 1px solid var(--jobs-border);
    border-radius: 16px;
}

.lg-jobs__empty svg {
    color: var(--jobs-text-muted);
    margin-bottom: 16px;
}

.lg-jobs__empty h3 {
    color: var(--jobs-text);
    margin-bottom: 8px;
}

.lg-jobs__empty p {
    color: var(--jobs-text-muted);
    margin-bottom: 24px;
}

/* Pagination */
.lg-jobs__pagination {
    margin-top: 32px;
}

/* Responsive */
@media (max-width: 1024px) {
    .lg-jobs__grid {
        grid-template-columns: 1fr;
    }
    
    .lg-jobs__sidebar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }
}

@media (max-width: 768px) {
    .lg-jobs__hero {
        padding: 40px 0 24px;
    }
    
    .lg-jobs__stats {
        gap: 16px;
    }
    
    .lg-jobs__stat-divider {
        display: none;
    }
    
    .lg-jobs__search-main {
        flex-direction: column;
    }
    
    .lg-jobs__search-btn {
        width: 100%;
        justify-content: center;
    }
    
    .lg-jobs__filters {
        flex-direction: column;
        gap: 12px;
    }
    
    .lg-jobs__filter-group {
        width: 100%;
    }
    
    .lg-jobs__filter-select {
        flex: 1;
    }
    
    .lg-jobs__card {
        padding: 16px;
    }
    
    .lg-jobs__card-header {
        gap: 12px;
    }
    
    .lg-jobs__card-logo {
        width: 48px;
        height: 48px;
    }
    
    .lg-jobs__card-meta {
        gap: 12px;
    }
    
    .lg-jobs__card-footer {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    
    .lg-jobs__card-actions {
        width: 100%;
    }
    
    .lg-jobs__card-actions .lg-jobs__btn {
        flex: 1;
    }
    
    .lg-jobs__sidebar {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('jobSearchForm');
    const keywordInput = form.querySelector('input[name="keyword"]');
    const quickTags = document.querySelectorAll('.lg-jobs__quick-tag');
    
    // Quick tag click
    quickTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const keyword = this.dataset.keyword;
            const location = this.dataset.location;
            
            if (keyword) {
                keywordInput.value = keyword;
            }
            if (location) {
                form.querySelector('select[name="location"]').value = location;
            }
            
            form.submit();
        });
    });
    
    // Filter change auto-submit
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => form.submit());
    });
    
    // Keyboard shortcut
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            keywordInput.focus();
        }
    });
});
</script>
@endpush
