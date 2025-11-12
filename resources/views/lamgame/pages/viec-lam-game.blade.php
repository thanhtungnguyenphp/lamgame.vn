@extends('layouts.master')

@section('page_title', $page_title ?? 'Việc làm Game - Làm Game')
@section('page_description', $page_description ?? 'Tìm kiếm cơ hội việc làm trong ngành game development tại Việt Nam và quốc tế')

@section('content')
    <!-- Progress bar -->
    <div class="progress-bar" id="progress-bar"></div>
    
    <!-- Hero Section -->
    <section class="hero-simple">
        <div class="container">
            <h1>Việc làm Game Development</h1>
            <p class="lead">Kết nối bạn với những cơ hội việc làm tốt nhất trong ngành game</p>
        </div>
    </section>

    <!-- Job Search Section -->
    <section class="job-search-section">
        <div class="container">
            <div class="search-form-container">
                <form class="job-search-form" method="GET" id="search-form">
                    <div class="search-header">
                        <h3><i class="fa fa-search"></i> Tìm việc làm phù hợp</h3>
                        <p class="search-subtitle">Khám phá {{ $totalJobs }} cơ hội việc làm game development</p>
                    </div>
                    
                    <div class="search-main">
                        <!-- Primary Search - Always visible -->
                        <div class="search-primary">
                            <div class="search-group keyword-search">
                                <label for="keyword" class="sr-only">Từ khóa tìm kiếm</label>
                                <div class="input-wrapper">
                                    <i class="fa fa-search search-icon"></i>
                                    <input type="text" 
                                           id="keyword" 
                                           name="keyword" 
                                           placeholder="Unity Developer, Game Designer, C#..." 
                                           value="{{ $searchParams['keyword'] ?? '' }}"
                                           autocomplete="off"
                                           class="keyword-input">
                                    @if($searchParams['keyword'] ?? false)
                                        <button type="button" class="clear-btn" onclick="clearKeyword()"
                                                aria-label="Xóa từ khóa">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-search-primary">
                                <i class="fa fa-search"></i>
                                <span class="btn-text">Tìm kiếm</span>
                            </button>
                        </div>
                        
                        <!-- Advanced Filters - Collapsible on mobile -->
                        <div class="search-advanced" id="advanced-filters">
                            <div class="advanced-header">
                                <button type="button" class="filter-toggle" onclick="toggleAdvancedFilters()" aria-expanded="false">
                                    <i class="fa fa-filter"></i>
                                    <span>Bộ lọc nâng cao</span>
                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                </button>
                                
                                @if(array_filter($searchParams ?? []))
                                    <div class="active-filters-count">
                                        {{ count(array_filter($searchParams ?? [])) - (isset($searchParams['keyword']) ? 1 : 0) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="advanced-content" id="advanced-content">
                                <div class="search-row">
                                    <div class="search-group">
                                        <label for="location">
                                            <i class="fa fa-map-marker"></i>
                                            Địa điểm
                                        </label>
                                        <select id="location" name="location" class="select-styled">
                                            <option value="">Tất cả địa điểm</option>
                                            <option value="ho-chi-minh" {{ ($searchParams['location'] ?? '') == 'ho-chi-minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                            <option value="ha-noi" {{ ($searchParams['location'] ?? '') == 'ha-noi' ? 'selected' : '' }}>Hà Nội</option>
                                            <option value="da-nang" {{ ($searchParams['location'] ?? '') == 'da-nang' ? 'selected' : '' }}>Đà Nẵng</option>
                                            <option value="remote" {{ ($searchParams['location'] ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                                        </select>
                                    </div>
                                    
                                    <div class="search-group">
                                        <label for="level">
                                            <i class="fa fa-level-up"></i>
                                            Cấp độ
                                        </label>
                                        <select id="level" name="level" class="select-styled">
                                            <option value="">Tất cả cấp độ</option>
                                            <option value="intern" {{ ($searchParams['level'] ?? '') == 'intern' ? 'selected' : '' }}>Thực tập sinh</option>
                                            <option value="fresher" {{ ($searchParams['level'] ?? '') == 'fresher' ? 'selected' : '' }}>Fresher (0-1 năm)</option>
                                            <option value="junior" {{ ($searchParams['level'] ?? '') == 'junior' ? 'selected' : '' }}>Junior (1-3 năm)</option>
                                            <option value="senior" {{ ($searchParams['level'] ?? '') == 'senior' ? 'selected' : '' }}>Senior (3+ năm)</option>
                                            <option value="lead" {{ ($searchParams['level'] ?? '') == 'lead' ? 'selected' : '' }}>Lead/Manager</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="filter-actions">
                                    <button type="button" class="btn btn-clear" onclick="clearAllFilters()">
                                        <i class="fa fa-times"></i>
                                        Xóa bộ lọc
                                    </button>
                                    <button type="submit" class="btn btn-apply-filters">
                                        <i class="fa fa-check"></i>
                                        Áp dụng
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick filters -->
                        <div class="quick-filters">
                            <div class="quick-filters-label">Tìm nhanh:</div>
                            <div class="quick-filter-buttons">
                                <button type="button" class="quick-filter-btn {{ ($searchParams['keyword'] ?? '') == 'Unity' ? 'active' : '' }}" 
                                        onclick="setQuickFilter('keyword', 'Unity')">Unity</button>
                                <button type="button" class="quick-filter-btn {{ ($searchParams['location'] ?? '') == 'remote' ? 'active' : '' }}" 
                                        onclick="setQuickFilter('location', 'remote')">Remote</button>
                                <button type="button" class="quick-filter-btn {{ ($searchParams['level'] ?? '') == 'senior' ? 'active' : '' }}" 
                                        onclick="setQuickFilter('level', 'senior')">Senior</button>
                                <button type="button" class="quick-filter-btn {{ ($searchParams['keyword'] ?? '') == 'Game Designer' ? 'active' : '' }}" 
                                        onclick="setQuickFilter('keyword', 'Game Designer')">Game Design</button>
                            </div>
                        </div>
                        
                        <!-- Sort options moved from jobs-header -->
                        <div class="search-sort-section">
                            <div class="search-results-info">
                                <span class="results-count">Tìm thấy <strong class="job-count">{{ $totalJobs }}</strong> việc làm phù hợp</span>
                            </div>
                            <div class="sort-options">
                                <label for="sort-select" class="sort-label">Sắp xếp:</label>
                                <select id="sort-select" class="sort-select" onchange="this.form.submit()" form="search-form">
                                    <option value="newest" {{ ($searchParams['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="salary-high" {{ ($searchParams['sort'] ?? '') == 'salary-high' ? 'selected' : '' }}>Lương cao nhất</option>
                                    <option value="company" {{ ($searchParams['sort'] ?? '') == 'company' ? 'selected' : '' }}>Theo công ty</option>
                                </select>
                                <input type="hidden" name="sort" value="{{ $searchParams['sort'] ?? 'newest' }}" form="search-form">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Job Listings -->
    <section class="job-listings-section">
        <div class="container">
            <div class="row">
                <!-- Job List -->
                <div class="col-lg-8">

                    <div class="job-list">
                        <div id="jobs-container">
                        @forelse($jobs as $index => $job)
                            @php
                                // Get salary from attributes or use default
                                $salaryFormatted = $job->attributes['salary_range'] ?? 'Thỏa thuận';
                                
                                // Get job location
                                $location = $job->attributes['job_location'] ?? 'Việt Nam';
                                
                                // Get job type
                                $jobType = $job->attributes['job_type'] ?? 'Full-time';
                                
                                // Posted time
                                $postedAgo = \Carbon\Carbon::parse($job->created_at)->diffForHumans();
                                
                                // Featured flag
                                $isFeatured = $index < 2; // First 2 jobs are featured
                            @endphp
                            <div class="job-item {{ $isFeatured ? 'featured' : '' }}">
                                <div class="job-content">
                                    <div class="job-header">
                                        <div class="job-thumbnail">
                                            @if($job->company_logo_url)
                                                <img src="{{ $job->company_logo_url }}" alt="{{ $job->company_name }}" class="job-thumbnail-img">
                                            @else
                                                <div class="job-thumbnail-img job-thumbnail-placeholder">
                                                    <span>{{ strtoupper(substr($job->company_name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="job-info">
                                            <h3><a href="{{ route('lamgame.job.detail', $job->url_key) }}" class="job-title" title="{{ $job->job_title }}">{{ $job->job_title }}</a></h3>
                                            <div class="company-name">
                                                <span>{{ $job->company_name }}</span>
                                            </div>
                                            <div class="job-meta-primary">
                                                <span class="salary highlight"><i class="fa fa-money"></i> {{ $salaryFormatted }}</span>
                                                <span class="posted"><i class="fa fa-clock-o"></i> {{ $postedAgo }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="job-meta">
                                        <div class="job-meta-secondary">
                                            <span class="location"><i class="fa fa-map-marker"></i> {{ $location }}</span>
                                            <span class="type"><i class="fa fa-briefcase"></i> {{ $jobType }}</span>
                                        </div>
                                    </div>
                                    <div class="job-description">
                                        <div class="job-desc-content">{!! $job->processed_description !!}</div>
                                    </div>
                                    <div class="job-tags">
                                        @php
                                            $tags = [];
                                            
                                            // Add experience level
                                            if (isset($job->attributes['experience_level'])) {
                                                $tags[] = ['text' => $job->attributes['experience_level'], 'type' => 'level'];
                                            }
                                            
                                            // Add required skills (comma-separated)
                                            if (isset($job->attributes['required_skills']) && !empty($job->attributes['required_skills'])) {
                                                $skills = explode(',', $job->attributes['required_skills']);
                                                foreach (array_slice($skills, 0, 3) as $skill) { // Limit to 3 skills
                                                    $tags[] = ['text' => trim($skill), 'type' => 'skill'];
                                                }
                                            }
                                            
                                            // Add job benefits (comma-separated)
                                            if (isset($job->attributes['job_benefits']) && !empty($job->attributes['job_benefits'])) {
                                                $benefits = explode(',', $job->attributes['job_benefits']);
                                                foreach (array_slice($benefits, 0, 2) as $benefit) { // Limit to 2 benefits
                                                    $tags[] = ['text' => trim($benefit), 'type' => 'benefit'];
                                                }
                                            }
                                            
                                            // Add category if available
                                            if ($job->category_name) {
                                                $tags[] = ['text' => $job->category_name, 'type' => 'category'];
                                            }
                                            
                                            // Limit total tags to 5
                                            $tags = array_slice($tags, 0, 5);
                                        @endphp
                                        
                                        @foreach($tags as $tag)
                                            <span class="tag tag-{{ $tag['type'] }}">
                                                @if($tag['type'] === 'skill')
                                                    <i class="fa fa-code"></i>
                                                @elseif($tag['type'] === 'benefit')
                                                    <i class="fa fa-gift"></i>
                                                @elseif($tag['type'] === 'level')
                                                    <i class="fa fa-star"></i>
                                                @endif
                                                {{ $tag['text'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="job-actions">
                                        <a href="{{ route('lamgame.job.detail', $job->url_key) }}" class="btn btn-detail">
                                            <i class="fa fa-eye"></i>
                                            <span class="btn-text">Chi tiết</span>
                                        </a>
                                        @if($job->contact_email)
                                            <a href="mailto:{{ $job->contact_email }}?subject=Ứng tuyển: {{ $job->job_title }}" class="btn btn-apply">
                                                <i class="fa fa-paper-plane"></i>
                                                <span class="btn-text">Ứng tuyển</span>
                                            </a>
                                        @else
                                            <button class="btn btn-apply">
                                                <i class="fa fa-paper-plane"></i>
                                                <span class="btn-text">Ứng tuyển</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="no-jobs-found">
                                <div class="text-center py-5">
                                    <i class="fa fa-search fa-3x text-muted mb-3"></i>
                                    <h3 class="text-muted">Không tìm thấy việc làm phù hợp</h3>
                                    <p class="text-muted">Hãy thử thay đổi từ khóa hoặc bộ lọc tìm kiếm</p>
                                    <a href="{{ url('/viec-lam-game') }}" class="btn btn-primary">Xem tất cả việc làm</a>
                                </div>
                            </div>
                        @endforelse
                        </div>
                    </div>

                    <!-- Improved Pagination -->
                    @if($jobs->hasPages())
                        {{ $jobs->appends(request()->query())->links('lamgame.components.custom-pagination') }}
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Career Tips -->
                        <div class="sidebar-block">
                            <h3>Lời khuyên nghề nghiệp</h3>
                            <div class="career-tips">
                                <div class="tip-item">
                                    <div class="tip-icon">
                                        <i class="fa fa-lightbulb-o"></i>
                                    </div>
                                    <div class="tip-content">
                                        <h4>Xây dựng Portfolio mạnh</h4>
                                        <p>Tạo portfolio showcase các game project để thể hiện kỹ năng thực tế</p>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <div class="tip-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="tip-content">
                                        <h4>Networking trong cộng đồng</h4>
                                        <p>Tham gia các event game dev, Discord communities để mở rộng mạng lưới</p>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <div class="tip-icon">
                                        <i class="fa fa-code"></i>
                                    </div>
                                    <div class="tip-content">
                                        <h4>Học công nghệ mới</h4>
                                        <p>Luôn cập nhật kỹ năng với các engine, framework mới nhất</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Companies -->
                        <div class="sidebar-block">
                            <h3>Công ty hàng đầu</h3>
                            <div class="top-companies">
                                @forelse($topCompanies as $company)
                                    <div class="company-item">
                                        @if($company->logo)
                                            @php
                                                $path = 'company-logos/' . basename($company->logo);
                                                $logoUrl = null;
                                                if (\Storage::disk('public')->exists($path)) {
                                                    try {
                                                        $file = \Storage::disk('public')->get($path);
                                                        $mimeType = \Storage::disk('public')->mimeType($path);
                                                        $logoUrl = 'data:' . $mimeType . ';base64,' . base64_encode($file);
                                                    } catch (\Exception $e) {
                                                        $logoUrl = null;
                                                    }
                                                }
                                            @endphp
                                            @if($logoUrl)
                                                <img src="{{ $logoUrl }}" alt="{{ $company->company_name }}" style="object-fit: contain; padding: 4px; background: white;">
                                            @else
                                                <div style="width: 40px; height: 40px; border-radius: 4px; background: linear-gradient(135deg, #6a4c93, #9b59b6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                                </div>
                                            @endif
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 4px; background: linear-gradient(135deg, #6a4c93, #9b59b6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                                {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="company-info">
                                            <h4>{{ $company->company_name }}</h4>
                                            <span class="job-count">{{ $company->job_count }} việc làm</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Chưa có dữ liệu công ty</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Job Alert -->
                        <div class="sidebar-block">
                            <h3>Nhận thông báo việc làm</h3>
                            <p>Đăng ký để nhận thông báo khi có việc làm phù hợp</p>
                            <form class="job-alert-form">
                                <div class="form-group">
                                    <input type="email" placeholder="Email của bạn" required>
                                </div>
                                <div class="form-group">
                                    <select required>
                                        <option value="">Chọn vị trí quan tâm</option>
                                        <option value="unity-developer">Unity Developer</option>
                                        <option value="game-designer">Game Designer</option>
                                        <option value="3d-artist">3D Artist</option>
                                        <option value="qa-tester">QA Tester</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-bell"></i> Đăng ký thông báo
                                </button>
                            </form>
                        </div>

                        <!-- Salary Guide -->
                        <div class="sidebar-block">
                            <h3>Thống kê lương</h3>
                            <div class="salary-stats">
                                <div class="stat-item">
                                    <div class="position">Unity Developer</div>
                                    <div class="salary-range">15-35 triệu</div>
                                </div>
                                <div class="stat-item">
                                    <div class="position">Game Designer</div>
                                    <div class="salary-range">12-28 triệu</div>
                                </div>
                                <div class="stat-item">
                                    <div class="position">3D Artist</div>
                                    <div class="salary-range">10-25 triệu</div>
                                </div>
                                <div class="stat-item">
                                    <div class="position">QA Tester</div>
                                    <div class="salary-range">8-18 triệu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <!-- Enhanced Pagination JS -->
    <script src="{{ asset('js/pagination-enhanced.js') }}"></script>
    <script>
        // Toggle advanced filters
        function toggleAdvancedFilters() {
            const toggle = document.querySelector('.filter-toggle');
            const content = document.getElementById('advanced-content');
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            
            toggle.setAttribute('aria-expanded', !isExpanded);
            
            if (!isExpanded) {
                content.classList.add('show');
            } else {
                content.classList.remove('show');
            }
        }
        
        // Clear keyword input
        function clearKeyword() {
            const input = document.getElementById('keyword');
            input.value = '';
            input.focus();
            
            // Hide clear button
            const clearBtn = document.querySelector('.clear-btn');
            if (clearBtn) {
                clearBtn.style.display = 'none';
            }
        }
        
        // Show/hide clear button based on input value
        document.addEventListener('DOMContentLoaded', function() {
            const keywordInput = document.getElementById('keyword');
            const clearBtn = document.querySelector('.clear-btn');
            
            if (keywordInput && clearBtn) {
                keywordInput.addEventListener('input', function() {
                    clearBtn.style.display = this.value ? 'flex' : 'none';
                });
            }
        });
        
        // Clear all filters
        function clearAllFilters() {
            const form = document.getElementById('search-form');
            const inputs = form.querySelectorAll('input[type="text"], select');
            
            inputs.forEach(input => {
                if (input.type === 'text') {
                    input.value = '';
                } else {
                    input.selectedIndex = 0;
                }
            });
            
            // Remove active state from quick filter buttons
            document.querySelectorAll('.quick-filter-btn.active').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Update URL without filters
            const url = new URL(window.location.href);
            url.search = '';
            window.history.pushState({}, '', url.toString());
        }
        
        // Set quick filter
        function setQuickFilter(field, value) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                if (input.type === 'text') {
                    input.value = value;
                } else {
                    const option = Array.from(input.options).find(opt => opt.value === value);
                    if (option) {
                        input.selectedIndex = option.index;
                    }
                }
                
                // Update quick filter button states
                updateQuickFilterStates();
                
                // Submit form
                document.getElementById('search-form').submit();
            }
        }
        
        // Update quick filter button states
        function updateQuickFilterStates() {
            const keyword = document.getElementById('keyword').value;
            const location = document.getElementById('location').value;
            const level = document.getElementById('level').value;
            
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Check active states
            if (keyword === 'Unity') {
                document.querySelector('[onclick*="Unity"]').classList.add('active');
            }
            if (location === 'remote') {
                document.querySelector('[onclick*="remote"]').classList.add('active');
            }
            if (level === 'senior') {
                document.querySelector('[onclick*="senior"]').classList.add('active');
            }
            if (keyword === 'Game Designer') {
                document.querySelector('[onclick*="Game Designer"]').classList.add('active');
            }
        }
        
        // Auto-expand advanced filters if any filters are set
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasFilters = Array.from(urlParams.entries()).some(([key, value]) => 
                key !== 'keyword' && value
            );
            
            if (hasFilters) {
                const toggle = document.querySelector('.filter-toggle');
                const content = document.getElementById('advanced-content');
                
                toggle.setAttribute('aria-expanded', 'true');
                content.classList.add('show');
            }
            
            // Update quick filter states on load
            updateQuickFilterStates();
        });
        
        // Enhanced form submission with loading states and skeleton
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('search-form');
            const submitBtns = form.querySelectorAll('[type="submit"]');
            const jobsContainer = document.getElementById('jobs-container');
            const searchFormContainer = document.querySelector('.search-form-container');
            
            // Create skeleton loader
            function createJobSkeleton() {
                return `
                    <div class="job-skeleton">
                        <div class="skeleton-header">
                            <div class="skeleton skeleton-thumb"></div>
                            <div class="skeleton-content">
                                <div class="skeleton skeleton-title"></div>
                                <div class="skeleton skeleton-company"></div>
                                <div class="skeleton-meta">
                                    <div class="skeleton skeleton-meta-item"></div>
                                    <div class="skeleton skeleton-meta-item"></div>
                                    <div class="skeleton skeleton-meta-item"></div>
                                </div>
                            </div>
                        </div>
                        <div class="skeleton skeleton-description"></div>
                        <div class="skeleton skeleton-description" style="width: 80%;"></div>
                        <div class="skeleton-tags">
                            <div class="skeleton skeleton-tag"></div>
                            <div class="skeleton skeleton-tag"></div>
                            <div class="skeleton skeleton-tag"></div>
                        </div>
                        <div class="skeleton-actions">
                            <div class="skeleton skeleton-btn"></div>
                            <div class="skeleton skeleton-btn"></div>
                        </div>
                    </div>
                `;
            }
            
            form.addEventListener('submit', function(e) {
                // Show progress bar
                const progressBar = document.getElementById('progress-bar');
                progressBar.classList.add('loading');
                progressBar.style.width = '30%';
                
                // Show loading state
                searchFormContainer.classList.add('loading');
                
                // Show skeleton loading with staggered animation
                const skeletonHTML = Array.from({length: 5}, (_, i) => {
                    return `<div style="animation-delay: ${i * 0.1}s;">${createJobSkeleton()}</div>`;
                }).join('');
                jobsContainer.innerHTML = skeletonHTML;
                
                // Progress animation
                setTimeout(() => progressBar.style.width = '60%', 500);
                setTimeout(() => progressBar.style.width = '90%', 1000);
                
                submitBtns.forEach(btn => {
                    btn.disabled = true;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang tìm kiếm...';
                    
                    // Enhanced re-enable with complete progress
                    setTimeout(() => {
                        progressBar.style.width = '100%';
                        setTimeout(() => {
                            progressBar.style.width = '0%';
                            progressBar.classList.remove('loading');
                        }, 200);
                        
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        searchFormContainer.classList.remove('loading');
                    }, 3000);
                });
                
                // Enhanced smooth scroll with offset for header
                setTimeout(() => {
                    const target = document.querySelector('.job-listings-section');
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
        
        // Enhanced keyboard shortcuts and mobile optimizations
        document.addEventListener('keydown', function(e) {
            // Ctrl+F or Cmd+F to focus keyword input
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('keyword').focus();
            }
            
            // Escape to clear keyword
            if (e.key === 'Escape') {
                const keywordInput = document.getElementById('keyword');
                if (document.activeElement === keywordInput && keywordInput.value) {
                    clearKeyword();
                }
            }
            
            // Enter key to search from any form field
            if (e.key === 'Enter' && e.target.closest('#search-form')) {
                e.preventDefault();
                form.submit();
            }
        });
        
        // Add touch-friendly interactions for mobile
        if ('ontouchstart' in window) {
            document.addEventListener('DOMContentLoaded', function() {
                // Add touch feedback to interactive elements
                const interactiveElements = document.querySelectorAll('.btn, .quick-filter-btn, .job-item');
                
                interactiveElements.forEach(element => {
                    element.addEventListener('touchstart', function() {
                        this.style.opacity = '0.8';
                    });
                    
                    element.addEventListener('touchend', function() {
                        setTimeout(() => {
                            this.style.opacity = '';
                        }, 150);
                    });
                });
                
                // Improve form experience on mobile
                const inputs = document.querySelectorAll('input[type="text"], select');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        // Scroll input into view on mobile to avoid keyboard overlap
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    });
                });
            });
        }
        
        // Analytics and performance tracking
        function trackJobInteraction(action, jobId = null) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'job_interaction', {
                    'event_category': 'jobs',
                    'event_label': action,
                    'job_id': jobId,
                    'value': 1
                });
            }
        }
        
        // Track job clicks
        document.addEventListener('click', function(e) {
            if (e.target.closest('.job-title, .btn-detail')) {
                const jobItem = e.target.closest('.job-item');
                const jobTitle = jobItem.querySelector('.job-title')?.textContent;
                trackJobInteraction('job_view', jobTitle);
            }
            
            if (e.target.closest('.btn-apply')) {
                const jobItem = e.target.closest('.job-item');
                const jobTitle = jobItem.querySelector('.job-title')?.textContent;
                trackJobInteraction('apply_click', jobTitle);
            }
        });
        
        // Enhanced lazy loading with blur-to-sharp transition
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.style.opacity = '0';
                        img.style.filter = 'blur(5px)';
                        img.style.transition = 'all 0.5s ease';
                        
                        const tempImg = new Image();
                        tempImg.onload = function() {
                            img.style.opacity = '1';
                            img.style.filter = 'blur(0px)';
                        };
                        tempImg.src = img.src;
                        
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px',
                threshold: 0.1
            });
            
            // Enhanced job item animations
            const jobObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                rootMargin: '20px',
                threshold: 0.1
            });
            
            document.addEventListener('DOMContentLoaded', function() {
                const images = document.querySelectorAll('.job-thumbnail-img');
                const jobItems = document.querySelectorAll('.job-item');
                
                images.forEach(img => imageObserver.observe(img));
                jobItems.forEach(item => jobObserver.observe(item));
                
                // Add animate-in class styles
                const style = document.createElement('style');
                style.textContent = `
                    .job-item {
                        opacity: 0;
                        transform: translateY(30px);
                        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    .job-item.animate-in {
                        opacity: 1;
                        transform: translateY(0);
                    }
                `;
                document.head.appendChild(style);
            });
        }
    </script>
    @endpush
    
    @push('styles')
    <style>
        /* Hero Simple với particles effect */
        .hero-simple {
            position: relative;
            background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
            overflow: hidden;
        }
        
        .hero-simple::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, rgba(255,255,255,0.1), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.1), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255,255,255,0.1), transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255,255,255,0.1), transparent);
            background-repeat: repeat;
            background-size: 200px 200px;
            animation: particleMove 20s linear infinite;
            pointer-events: none;
        }
        
        @keyframes particleMove {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-100px) translateX(50px); }
            100% { transform: translateY(0px) translateX(0px); }
        }
        
        .hero-simple .container {
            position: relative;
            z-index: 2;
        }
        
        .hero-simple h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .lead {
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        /* Job Search Section */
        .job-search-section {
            padding: 3rem 0;
            background: 
                linear-gradient(135deg, rgba(106, 76, 147, 0.05) 0%, rgba(155, 89, 182, 0.08) 50%, rgba(248, 250, 255, 0.9) 100%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="25" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            border-bottom: 1px solid rgba(106, 76, 147, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .job-search-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 25% 25%, rgba(106, 76, 147, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(155, 89, 182, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .job-search-section .container {
            position: relative;
            z-index: 2;
        }
        
        .search-form-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .job-search-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                0 8px 32px rgba(106, 76, 147, 0.15),
                0 4px 16px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
        }
        
        .job-search-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 20px;
            pointer-events: none;
        }
        
        /* Search Header */
        .search-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }
        
        .search-header::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #6a4c93, #9b59b6);
            border-radius: 2px;
        }
        
        .search-header h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .search-header h3 i {
            color: #667eea;
        }
        
        .search-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        /* Primary Search */
        .search-primary {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: stretch;
        }
        
        .keyword-search {
            flex: 1;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            color: #6a4c93;
            z-index: 2;
            pointer-events: none;
        }
        
        .keyword-input {
            width: 100%;
            padding: 1.2rem 1rem 1.2rem 2.8rem;
            border: 2px solid rgba(225, 229, 233, 0.5);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
        }
        
        .keyword-input:focus {
            outline: none;
            border-color: #6a4c93;
            background: white;
            box-shadow: 0 0 0 3px rgba(106, 76, 147, 0.1);
        }
        
        .clear-btn {
            position: absolute;
            right: 0.5rem;
            background: #f8f9fa;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .clear-btn:hover {
            background: #e9ecef;
            color: #333;
        }
        
        .btn-search-primary {
            background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(106, 76, 147, 0.3);
        }
        
        .btn-search-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(106, 76, 147, 0.4);
        }
        
        .btn-search-primary:active {
            transform: translateY(0);
        }
        
        /* Advanced Filters */
        .search-advanced {
            margin-bottom: 1.5rem;
        }
        
        .advanced-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .filter-toggle {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #555;
            transition: all 0.3s;
            flex: 1;
        }
        
        .filter-toggle:hover {
            background: #e9ecef;
            border-color: #6a4c93;
        }
        
        .filter-toggle[aria-expanded="true"] {
            background: #6a4c93;
            color: white;
            border-color: #6a4c93;
        }
        
        .filter-toggle[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }
        
        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }
        
        .active-filters-count {
            background: #6a4c93;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 0.5rem;
        }
        
        .advanced-content {
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        .advanced-content.show {
            display: block;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .search-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .search-group {
            display: flex;
            flex-direction: column;
        }
        
        .search-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .search-group label i {
            color: #6a4c93;
            font-size: 0.8rem;
        }
        
        .select-styled {
            padding: 0.75rem;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            font-size: 0.9rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .select-styled:focus {
            outline: none;
            border-color: #6a4c93;
            box-shadow: 0 0 0 2px rgba(106, 76, 147, 0.1);
        }
        
        .filter-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }
        
        .btn-clear {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #e1e5e9;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-clear:hover {
            background: #e9ecef;
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .btn-apply-filters {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-apply-filters:hover {
            background: #218838;
        }
        
        /* Quick Filters */
        .quick-filters {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        
        .quick-filters-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .quick-filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .quick-filter-btn {
            background: #f8f9fa;
            color: #6a4c93;
            border: 1px solid #e1e5e9;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .quick-filter-btn:hover {
            background: #e9ecef;
            border-color: #6a4c93;
        }
        
        .quick-filter-btn.active {
            background: #6a4c93;
            color: white;
            border-color: #6a4c93;
        }
        
        /* Search Sort Section */
        .search-sort-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0 0.5rem;
            border-top: 1px solid rgba(106, 76, 147, 0.1);
            margin-top: 0.5rem;
        }
        
        .search-results-info {
            color: #333;
            font-size: 0.95rem;
        }
        
        .search-results-info .job-count {
            color: #6a4c93;
            font-weight: 700;
        }
        
        .search-sort-section .sort-options {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sort-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .search-sort-section .sort-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 130px;
        }
        
        .search-sort-section .sort-select:focus {
            outline: none;
            border-color: #6a4c93;
            box-shadow: 0 0 0 2px rgba(106, 76, 147, 0.1);
        }
        
        /* Screen reader only */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Job Listings */
        .job-listings-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8faff 0%, #ffffff 50%, #f0f4ff 100%);
            position: relative;
        }
        
        .job-listings-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(106, 76, 147, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(155, 89, 182, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .job-listings-section .container {
            position: relative;
            z-index: 2;
        }
        
        .row {
            display: flex;
            gap: 2rem;
        }
        
        .col-lg-8 {
            flex: 0 0 66.66%;
        }
        
        .col-lg-4 {
            flex: 0 0 33.33%;
        }
        

        /* Job Items */
        .job-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .job-item {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border: 1px solid #e1e5e9;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            backdrop-filter: blur(20px);
            margin-bottom: 1.5rem;
        }
        
        .job-item:hover {
            box-shadow: 0 8px 25px rgba(106, 76, 147, 0.15), 0 3px 10px rgba(0,0,0,0.08);
            transform: translateY(-4px) scale(1.01);
            border-color: rgba(106, 76, 147, 0.2);
        }
        
        .job-item.featured {
            border: 2px solid #6a4c93;
            background: linear-gradient(135deg, rgba(106, 76, 147, 0.05) 0%, rgba(255,255,255, 0.95) 50%, rgba(155, 89, 182, 0.03) 100%);
            box-shadow: 0 4px 20px rgba(106, 76, 147, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .job-item.featured::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6a4c93, #9b59b6, #6a4c93);
            animation: shimmer 2s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        
        .job-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: flex-start;
        }
        
        .job-thumbnail {
            flex-shrink: 0;
        }
        
        .job-thumbnail-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: contain;
            border: 2px solid #e1e5e9;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: white;
            padding: 8px;
        }
        
        .job-thumbnail-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }
        
        .job-thumbnail-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .job-item:hover .job-thumbnail-img::before {
            left: 100%;
        }
        
        .job-item:hover .job-thumbnail-img {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .job-info {
            flex: 1;
            min-width: 0; /* Allow text truncation */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        
        .job-title {
            color: #333;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: color 0.3s ease;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .job-title:hover {
            color: #6a4c93;
            text-decoration: none;
        }
        
        .company-name {
            color: #6a4c93;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .job-meta-primary {
            display: flex;
            gap: 1rem;
            align-items: center;
            font-weight: 500;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }
        
        .job-meta {
            display: flex;
            flex-direction: row;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #666;
            padding-top: 0.75rem;
            border-top: 1px solid #f0f0f0;
        }
        
        .job-meta-secondary {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: #888;
            align-items: center;
        }
        
        .job-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            white-space: nowrap;
        }
        
        .job-meta i,
        .job-meta-primary i {
            color: #6a4c93;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        
        .job-meta .salary.highlight {
            color: #6a4c93;
            font-weight: 600;
            background: rgba(106, 76, 147, 0.08);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
        }
        
        .job-meta .posted {
            color: #28a745;
            font-weight: 500;
        }
        
        .job-description {
            margin: 1rem 0;
            color: #555;
            line-height: 1.6;
        }
        
        .job-desc-content {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .job-desc-content p {
            margin: 0 0 0.5rem 0;
        }
        
        .job-desc-content p:last-child {
            margin-bottom: 0;
        }
        
        .job-desc-content strong,
        .job-desc-content b {
            color: #333;
            font-weight: 600;
        }
        
        .job-desc-content em,
        .job-desc-content i {
            font-style: italic;
        }
        
        .job-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .tag {
            background: #f8f9fa;
            color: #6a4c93;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .tag i {
            font-size: 0.75rem;
        }
        
        .tag:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Tag type colors */
        .tag-skill {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border-color: rgba(52, 152, 219, 0.3);
        }
        
        .tag-skill i {
            color: #3498db;
        }
        
        .tag-benefit {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border-color: rgba(46, 204, 113, 0.3);
        }
        
        .tag-benefit i {
            color: #27ae60;
        }
        
        .tag-level {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
            border-color: rgba(241, 196, 15, 0.3);
        }
        
        .tag-level i {
            color: #f39c12;
        }
        
        .tag-category {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
            border-color: rgba(155, 89, 182, 0.3);
        }
        
        .job-actions {
            display: flex;
            gap: 0.75rem;
            align-items: stretch;
            margin: 1.5rem -1rem 0;
            padding: 1rem;
            background: rgba(248, 250, 255, 0.6);
            border-radius: 8px;
            border-top: 1px solid rgba(106, 76, 147, 0.08);
        }
        
        /* Ensure job content has enough space on mobile */
        @media (max-width: 768px) {
            .job-content {
                padding-bottom: 0.5rem;
            }
            
            .job-item {
                padding: 1rem;
            }
        }
        
        .btn-detail {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }
        
        .btn-detail:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea87a 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .btn-apply {
            background: linear-gradient(135deg, #6a4c93 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(106, 76, 147, 0.2);
        }
        
        .btn-apply:hover {
            background: linear-gradient(135deg, #5a3c83 0%, #8b4982 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(106, 76, 147, 0.3);
        }
        

        /* Old pagination CSS removed - using custom component */

        /* Sidebar */
        .sidebar-block {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,255,0.9) 100%);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(106, 76, 147, 0.08);
            margin-bottom: 2rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(106, 76, 147, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #6a4c93, #9b59b6, #6a4c93);
        }
        
        .sidebar-block h3 {
            color: #333;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #6a4c93;
            padding-bottom: 0.5rem;
        }

        /* Career Tips */
        .career-tips {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .tip-item {
            display: flex;
            gap: 1rem;
        }
        
        .tip-icon {
            background: #6a4c93;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .tip-content h4 {
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 1rem;
        }
        
        .tip-content p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Top Companies */
        .top-companies {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .company-item {
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .company-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .company-item img {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            object-fit: cover;
        }
        
        .company-info h4 {
            margin-bottom: 0.25rem;
            color: #333;
            font-size: 0.9rem;
        }
        
        .job-count {
            color: #666;
            font-size: 0.8rem;
        }

        /* Job Alert Form */
        .job-alert-form {
            margin-top: 1rem;
        }
        
        .job-alert-form .form-group {
            margin-bottom: 1rem;
        }
        
        .job-alert-form input,
        .job-alert-form select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-primary {
            background: #6a4c93;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
        }
        
        .btn-primary:hover {
            background: #5a3c83;
        }

        /* Salary Stats */
        .salary-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .position {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        
        .salary-range {
            color: #6a4c93;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Mobile-first responsive design */
        @media (max-width: 768px) {
            .job-search-section {
                padding: 1.5rem 0;
            }
            
            .job-search-form {
                padding: 1rem;
                margin: 0 0.5rem;
            }
            
            .search-header h3 {
                font-size: 1.2rem;
            }
            
            .search-subtitle {
                font-size: 0.85rem;
            }
            
            /* Mobile search layout */
            .search-primary {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .keyword-input {
                padding: 0.875rem 0.875rem 0.875rem 2.25rem;
                font-size: 16px; /* Prevent zoom on iOS */
            }
            
            .btn-search-primary {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1rem;
            }
            
            .btn-search-primary .btn-text {
                display: inline;
            }
            
            /* Advanced filters mobile */
            .filter-toggle {
                padding: 0.875rem;
                font-size: 0.95rem;
                touch-action: manipulation;
            }
            
            .search-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .select-styled {
                padding: 0.875rem;
                font-size: 16px; /* Prevent zoom on iOS */
            }
            
            .filter-actions {
                justify-content: stretch;
                gap: 0.5rem;
            }
            
            .btn-clear,
            .btn-apply-filters {
                flex: 1;
                justify-content: center;
                padding: 0.75rem;
            }
            
            /* Quick filters mobile */
            .quick-filters {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
            }
            
            .quick-filters-label {
                font-size: 0.85rem;
            }
            
            .quick-filter-buttons {
                width: 100%;
                justify-content: flex-start;
            }
            
            .quick-filter-btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.85rem;
                touch-action: manipulation;
            }
            
            /* Job listings mobile */
            .row {
                flex-direction: column;
            }
            
            .col-lg-8, .col-lg-4 {
                flex: 1;
            }
            
            .hero-simple h1 {
                font-size: 2rem;
            }
            
            .jobs-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .job-header {
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .job-thumbnail-img {
                width: 55px;
                height: 55px;
                padding: 6px;
            }
            
            .job-thumbnail-placeholder {
                font-size: 1rem;
            }
            
            .job-title {
                font-size: 0.95rem;
                margin-bottom: 0.1rem;
                -webkit-line-clamp: 2;
            }
            
            .company-name {
                font-size: 0.8rem;
            }
            
            .job-meta {
                gap: 0.4rem;
            }
            
            .job-meta-primary {
                gap: 0.75rem;
            }
            
            .job-meta-secondary {
                gap: 0.75rem;
                font-size: 0.75rem;
            }
            
            .job-meta .salary.highlight {
                font-size: 0.85rem;
                padding: 0.2rem 0.4rem;
            }
            
            .job-actions {
                margin: 1rem -1rem 0;
                padding: 1rem;
                gap: 0.75rem;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border-top: 1px solid #e1e5e9;
                border-radius: 8px;
            }
            
            .btn-apply,
            .btn-detail {
                flex: 1;
                padding: 1rem 0.75rem;
                font-size: 0.85rem;
                justify-content: center;
                min-height: 48px;
                font-weight: 600;
            }
        }
        
        /* Tablet responsive */
        @media (max-width: 1024px) and (min-width: 769px) {
            .search-primary {
                gap: 0.75rem;
            }
            
            .btn-search-primary {
                padding: 1rem 1.5rem;
            }
            
            .search-row {
                grid-template-columns: 1fr;
                gap: 0.875rem;
            }
        }
        
        /* Large mobile landscape */
        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }
            
            .job-search-form {
                margin: 0;
                border-radius: 16px;
                padding: 1.5rem;
            }
            
            .search-header {
                margin-bottom: 1.5rem;
            }
            
            .search-header h3 {
                font-size: 1.1rem;
            }
            
            .keyword-input {
                padding: 1rem 0.75rem 1rem 2.2rem;
                font-size: 16px;
            }
            
            .search-icon {
                left: 0.75rem;
            }
            
            .btn-search-primary {
                padding: 1rem;
                font-size: 0.9rem;
            }
            
            .quick-filter-btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.8rem;
            }
            
            .search-sort-section {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
                padding: 0.75rem 0 0.25rem;
            }
            
            .search-sort-section .sort-options {
                align-self: stretch;
                justify-content: space-between;
            }
            
            .search-results-info {
                font-size: 0.9rem;
            }
            
            .job-actions {
                margin: 1rem -1rem 0;
                padding: 1rem;
                gap: 0.75rem;
                border-radius: 8px;
            }
            
            .btn-detail,
            .btn-apply {
                flex: 1;
                min-height: 50px;
                padding: 1rem 0.75rem;
                font-size: 0.85rem;
                font-weight: 600;
            }
            
            .btn-text {
                display: inline;
                margin-left: 0.25rem;
            }
        }
        
            /* Touch device optimizations */
        @media (hover: none) {
            .btn-search-primary:hover {
                transform: none;
            }
            
            .keyword-input:hover {
                border-color: #e1e5e9;
            }
            
            .filter-toggle:hover {
                background: #f8f9fa;
                border-color: #e1e5e9;
            }
        }
        
        /* Loading states and micro-interactions */
        .btn-search-primary:active {
            transform: scale(0.98);
        }
        
        .job-item {
            will-change: transform, box-shadow;
        }
        
        .job-item:active {
            transform: translateY(-1px);
        }
        
        /* Smooth scrolling for pagination */
        html {
            scroll-behavior: smooth;
        }
        
        /* Focus styles for accessibility */
        .btn:focus,
        .keyword-input:focus,
        .select-styled:focus {
            outline: 2px solid #6a4c93;
            outline-offset: 2px;
        }
        
        /* Improve text contrast */
        .hero-simple h1 {
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .job-item:hover .job-thumbnail-img {
            filter: brightness(1.05);
            transform: scale(1.1) rotate(1deg);
        }
        
        /* Loading skeleton animations */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 2s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .job-skeleton {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .skeleton-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .skeleton-thumb {
            width: 80px;
            height: 60px;
            border-radius: 8px;
        }
        
        .skeleton-content {
            flex: 1;
        }
        
        .skeleton-title {
            height: 24px;
            border-radius: 4px;
            margin-bottom: 8px;
            width: 70%;
        }
        
        .skeleton-company {
            height: 16px;
            border-radius: 4px;
            margin-bottom: 8px;
            width: 40%;
        }
        
        .skeleton-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .skeleton-meta-item {
            height: 16px;
            border-radius: 4px;
            width: 80px;
        }
        
        .skeleton-description {
            height: 14px;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        
        .skeleton-tags {
            display: flex;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .skeleton-tag {
            height: 24px;
            border-radius: 20px;
            width: 80px;
        }
        
        .skeleton-actions {
            display: flex;
            gap: 1rem;
        }
        
        .skeleton-btn {
            height: 40px;
            border-radius: 4px;
            width: 120px;
        }
        
        /* Improved search form transitions */
        .search-form-container {
            transition: all 0.3s ease;
        }
        
        .search-form-container.loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        /* Better mobile touch targets */
        @media (max-width: 768px) {
            .job-actions {
                margin: 1rem -1rem 0;
                padding: 1rem;
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border-top: 1px solid rgba(106, 76, 147, 0.1);
                gap: 0.75rem;
                border-radius: 8px;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            }
            
            .btn-detail,
            .btn-apply {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
                min-height: 50px;
                flex: 1;
                white-space: nowrap;
                font-weight: 600;
            }
            
            .btn-text {
                display: inline;
                margin-left: 0.25rem;
            }
            
            .job-item {
                margin-bottom: 1rem;
                border-radius: 12px;
                overflow: visible;
                padding-bottom: 0;
            }
            
            .job-header {
                align-items: flex-start;
                gap: 0.75rem;
                margin-bottom: 0.75rem;
            }
            
            .job-thumbnail {
                flex-shrink: 0;
            }
            
            .job-thumbnail-img {
                width: 60px;
                height: 45px;
                border-radius: 8px;
            }
            
            .job-title {
                font-size: 1rem;
                line-height: 1.4;
                margin-bottom: 0.15rem;
                -webkit-line-clamp: 2;
            }
            
            .company-name {
                font-size: 0.85rem;
                margin-bottom: 0;
            }
            
            .job-meta {
                margin-top: 0.75rem;
                padding-top: 0.75rem;
            }
            
            .job-content {
                padding-bottom: 0;
            }
        }
        
        /* Performance optimizations */
        .job-list {
            contain: layout style;
        }
        
        .job-item {
            contain: layout style;
            transform: translateZ(0); /* Force GPU acceleration */
        }
        
        /* Improved contrast for better readability */
        .job-description {
            line-height: 1.7;
        }
        
        .job-meta {
            font-size: 0.95rem;
        }
        
        /* Button text always visible */
        .btn-text {
            display: inline;
        }
        
        /* Better visual hierarchy */
        
        .job-title {
            line-height: 1.3;
            letter-spacing: -0.01em;
            position: relative;
            overflow: hidden;
        }
        
        .job-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(106, 76, 147, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .job-item:hover .job-title::before {
            left: 100%;
        }
        
        /* Staggered animation for job items */
        .job-item {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .job-item:nth-child(1) { animation-delay: 0.1s; }
        .job-item:nth-child(2) { animation-delay: 0.2s; }
        .job-item:nth-child(3) { animation-delay: 0.3s; }
        .job-item:nth-child(4) { animation-delay: 0.4s; }
        .job-item:nth-child(5) { animation-delay: 0.5s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Floating label effect for form inputs */
        .form-field-floating {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-field-floating input,
        .form-field-floating select {
            width: 100%;
            padding: 1rem 1rem 0.5rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255,255,255,0.9);
            transition: all 0.3s ease;
        }
        
        .form-field-floating label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #666;
            pointer-events: none;
            transition: all 0.3s ease;
            transform-origin: left;
        }
        
        .form-field-floating input:focus + label,
        .form-field-floating input:not(:placeholder-shown) + label,
        .form-field-floating select:focus + label {
            top: 0.3rem;
            font-size: 0.8rem;
            color: #6a4c93;
            font-weight: 600;
        }
        
        .form-field-floating input:focus,
        .form-field-floating select:focus {
            border-color: #6a4c93;
            box-shadow: 0 0 0 3px rgba(106, 76, 147, 0.1);
            outline: none;
        }
        
        /* Enhanced button interactions */
        .btn-apply,
        .btn-detail {
            position: relative;
            overflow: hidden;
            will-change: transform;
        }
        
        .btn-apply::before,
        .btn-detail::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            pointer-events: none;
        }
        
        .btn-apply:active::before,
        .btn-detail:active::before {
            width: 200px;
            height: 200px;
        }
        
        .btn-apply:focus,
        .btn-detail:focus {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }
        
        /* Pulse effect for featured jobs */
        .job-item.featured {
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(106, 76, 147, 0.1);
            }
            50% {
                box-shadow: 0 8px 30px rgba(106, 76, 147, 0.2);
            }
        }
        
        /* Progress bar for loading states */
        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #6a4c93, #9b59b6);
            transition: width 0.3s ease;
            z-index: 9999;
        }
        
        .progress-bar.loading {
            animation: progressPulse 1.5s ease-in-out infinite;
        }
        
        @keyframes progressPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Enhanced no-jobs state */
        .no-jobs-found {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,250,255,0.8) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(106, 76, 147, 0.1);
            box-shadow: 0 8px 32px rgba(106, 76, 147, 0.08);
        }
        
        .no-jobs-found i {
            background: linear-gradient(135deg, #6a4c93, #9b59b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Interactive states for better feedback */
        .job-item {
            cursor: pointer;
            user-select: none;
        }
        
        .job-item:active {
            transform: translateY(-2px) scale(0.98);
        }
        
        /* Smooth transitions for all elements */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Custom scrollbar for webkit browsers */
        .job-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .job-list::-webkit-scrollbar-track {
            background: rgba(106, 76, 147, 0.1);
            border-radius: 3px;
        }
        
        .job-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #6a4c93, #9b59b6);
            border-radius: 3px;
        }
        
        .job-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a3c83, #8b4982);
        }
    </style>
    @endpush
@endsection
