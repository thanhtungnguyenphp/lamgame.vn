@extends('layouts.master')

@section('page_title', $page_title ?? 'Việc làm Game - Làm Game')
@section('page_description', $page_description ?? 'Tìm kiếm cơ hội việc làm trong ngành game development tại Việt Nam và quốc tế')

@section('content')
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
                    <div class="jobs-header">
                        <h2>Tìm thấy <span class="job-count">{{ $totalJobs }}</span> việc làm phù hợp</h2>
                        <div class="sort-options">
                            <select class="sort-select" onchange="this.form.submit()" form="search-form">
                                <option value="newest" {{ ($searchParams['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="salary-high" {{ ($searchParams['sort'] ?? '') == 'salary-high' ? 'selected' : '' }}>Lương cao nhất</option>
                                <option value="company" {{ ($searchParams['sort'] ?? '') == 'company' ? 'selected' : '' }}>Theo công ty</option>
                            </select>
                            <input type="hidden" name="sort" value="{{ $searchParams['sort'] ?? 'newest' }}" form="search-form">
                        </div>
                    </div>

                    <div class="job-list">
                        @forelse($jobs as $index => $job)
                            @php
                                $companyName = trim(str_replace(' - ', ' ', explode(' - ', $job->name)[1] ?? $job->name));
                                $jobTitle = explode(' - ', $job->name)[0] ?? $job->name;
                                $salaryFormatted = number_format($job->price / 1000000, 1) . ' triệu';
                                $postedAgo = \Carbon\Carbon::parse($job->created_at)->diffForHumans();
                                $companySlug = \Str::slug($companyName);
                                $isFeatured = $index < 2; // First 2 jobs are featured
                            @endphp
                            <div class="job-item {{ $isFeatured ? 'featured' : '' }}">
                                @if($isFeatured)
                                    <div class="job-badge">Việc làm nổi bật</div>
                                @endif
                                <div class="job-content">
                                    <div class="job-header">
                                        <div class="company-logo">
                                            <img src="https://via.placeholder.com/60x60?text={{ strtoupper(substr($companyName, 0, 3)) }}" alt="{{ $companyName }}">
                                        </div>
                                        <div class="job-info">
                                            @php
                                                $jobSlug = \Str::slug($jobTitle);
                                            @endphp
                                            <h3><a href="{{ route('lamgame.job-detail', [$job->id, $jobSlug]) }}" class="job-title" title="{{ $jobTitle }}">{{ $jobTitle }}</a></h3>
                                            <div class="company-name">{{ $companyName }}</div>
                                            <div class="job-meta">
                                                <span class="location"><i class="fa fa-map-marker"></i> Việt Nam</span>
                                                <span class="salary"><i class="fa fa-money"></i> {{ $salaryFormatted }}</span>
                                                <span class="type"><i class="fa fa-clock-o"></i> Full-time</span>
                                                <span class="posted"><i class="fa fa-calendar"></i> {{ $postedAgo }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="job-description">
                                        <p>{{ \Str::limit($job->short_description, 150) }}</p>
                                    </div>
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
                                    <div class="job-actions">
                                        <a href="{{ route('lamgame.job-detail', [$job->id, $jobSlug]) }}" class="btn btn-detail">
                                            <i class="fa fa-eye"></i> Xem chi tiết
                                        </a>
                                        @if($job->contact_email)
                                            <a href="mailto:{{ $job->contact_email }}?subject=Ứng tuyển: {{ $jobTitle }}" class="btn btn-apply">Ứng tuyển ngay</a>
                                        @else
                                            <button class="btn btn-apply">Ứng tuyển ngay</button>
                                        @endif
                                        <button class="btn btn-save"><i class="fa fa-heart-o"></i></button>
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
                                        <img src="https://via.placeholder.com/40x40?text={{ strtoupper(substr($company->company_name, 0, 3)) }}" alt="{{ $company->company_name }}">
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
        
        // Enhanced form submission with loading states
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('search-form');
            const submitBtns = form.querySelectorAll('[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtns.forEach(btn => {
                    btn.disabled = true;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang tìm...';
                    
                    // Re-enable after 2 seconds as fallback
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            });
        });
        
        // Keyboard shortcuts
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
        });
    </script>
    @endpush
    
    @push('styles')
    <style>
        /* Hero Simple */
        .hero-simple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
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
            padding: 2rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }
        
        .search-form-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .job-search-form {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            border: 1px solid rgba(255,255,255,0.8);
        }
        
        /* Search Header */
        .search-header {
            text-align: center;
            margin-bottom: 2rem;
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
            color: #667eea;
            z-index: 2;
            pointer-events: none;
        }
        
        .keyword-input {
            width: 100%;
            padding: 1rem 1rem 1rem 2.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        
        .keyword-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        
        .btn-search-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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
            border-color: #667eea;
        }
        
        .filter-toggle[aria-expanded="true"] {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .filter-toggle[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }
        
        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }
        
        .active-filters-count {
            background: #667eea;
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
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
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
            color: #667eea;
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
            border-color: #667eea;
        }
        
        .quick-filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
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
        
        .jobs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .jobs-header h2 {
            color: #333;
            margin: 0;
        }
        
        .job-count {
            color: #667eea;
            font-weight: bold;
        }
        
        .sort-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        /* Job Items */
        .job-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .job-item {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
        }
        
        .job-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .job-item.featured {
            border: 2px solid #667eea;
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        }
        
        .job-badge {
            position: absolute;
            top: -10px;
            left: 2rem;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .job-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .company-logo img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e1e5e9;
        }
        
        .job-info {
            flex: 1;
        }
        
        .job-title {
            color: #333;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            transition: color 0.3s ease;
        }
        
        .job-title:hover {
            color: #667eea;
            text-decoration: none;
        }
        
        .company-name {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .job-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.9rem;
            color: #666;
        }
        
        .job-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .job-meta i {
            color: #667eea;
        }
        
        .job-description {
            margin: 1rem 0;
            color: #555;
            line-height: 1.6;
        }
        
        .job-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1rem 0;
        }
        
        .tag {
            background: #f8f9fa;
            color: #667eea;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
        }
        
        .job-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn-detail {
            background: #28a745;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-detail:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }
        
        .btn-apply {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-apply:hover {
            background: #5a67d8;
            color: white;
            text-decoration: none;
        }
        
        .btn-save {
            background: transparent;
            border: 1px solid #ddd;
            color: #666;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-save:hover {
            border-color: #667eea;
            color: #667eea;
        }

        /* Old pagination CSS removed - using custom component */

        /* Sidebar */
        .sidebar-block {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .sidebar-block h3 {
            color: #333;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #667eea;
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
            background: #667eea;
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
            background: #667eea;
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
            background: #5a67d8;
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
            color: #667eea;
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
                flex-direction: column;
                text-align: center;
            }
            
            .job-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .job-actions {
                flex-direction: column;
            }
            
            .btn-apply {
                width: 100%;
                justify-content: center;
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
                border-radius: 8px;
            }
            
            .search-header {
                margin-bottom: 1.5rem;
            }
            
            .search-header h3 {
                font-size: 1.1rem;
            }
            
            .keyword-input {
                padding: 0.75rem 0.75rem 0.75rem 2rem;
            }
            
            .search-icon {
                left: 0.75rem;
            }
            
            .btn-search-primary {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            
            .quick-filter-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
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
    </style>
    @endpush
@endsection
