# Dashboard Frontend Integration Guide

## Tổng quan

Hướng dẫn này mô tả cách tích hợp API Dashboard vào giao diện frontend để tạo một dashboard quản lý job postings và applications cho employers.

## Yêu cầu Frontend

### Công nghệ sử dụng
- HTML5, CSS3, JavaScript (ES6+)
- Bootstrap 5.x hoặc Tailwind CSS
- Axios hoặc Fetch API
- Optional: Vue.js, React, hoặc Alpine.js

### Dependencies
```html
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Axios for HTTP requests -->
<script src="https://cdn.jsdelivr.net/npm/axios@1.5.0/dist/axios.min.js"></script>

<!-- Chart.js for statistics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.0.0/dist/chart.min.js"></script>
```

---

## Dashboard Layout Structure

### HTML Template
```html
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quản lý tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-briefcase me-2"></i>
                Dashboard Tuyển Dụng
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="#" id="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <nav class="nav flex-column">
                            <a class="nav-link active" href="#dashboard" data-tab="dashboard">
                                <i class="fas fa-chart-line me-2"></i>
                                Tổng quan
                            </a>
                            <a class="nav-link" href="#jobs" data-tab="jobs">
                                <i class="fas fa-briefcase me-2"></i>
                                Công việc của tôi
                            </a>
                            <a class="nav-link" href="#applications" data-tab="applications">
                                <i class="fas fa-users me-2"></i>
                                Đơn ứng tuyển
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Dashboard Overview Tab -->
                <div id="tab-dashboard" class="tab-content active">
                    <!-- Statistics Cards -->
                    <div class="row mb-4" id="statistics-cards">
                        <!-- Will be populated by JavaScript -->
                    </div>

                    <!-- Recent Jobs and Applications -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-briefcase me-2"></i>Công việc mới nhất</h5>
                                </div>
                                <div class="card-body" id="recent-jobs">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-user-plus me-2"></i>Ứng viên mới nhất</h5>
                                </div>
                                <div class="card-body" id="recent-applications">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Details Tab -->
                <div id="tab-jobs" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-briefcase me-2"></i>Chi tiết công việc</h5>
                        </div>
                        <div class="card-body" id="job-details">
                            <p class="text-muted">Chọn một công việc để xem chi tiết ứng viên</p>
                        </div>
                    </div>
                </div>

                <!-- Applications Management Tab -->
                <div id="tab-applications" class="tab-content">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-users me-2"></i>Quản lý ứng viên</h5>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary btn-sm" data-filter="all">Tất cả</button>
                                <button class="btn btn-outline-warning btn-sm" data-filter="pending">Chờ xử lý</button>
                                <button class="btn btn-outline-info btn-sm" data-filter="reviewed">Đã xem</button>
                                <button class="btn btn-outline-success btn-sm" data-filter="shortlisted">Danh sách ngắn</button>
                            </div>
                        </div>
                        <div class="card-body" id="applications-list">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="applicationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết ứng viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="application-details">
                    <!-- Will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info" onclick="updateStatus('reviewed')">
                            <i class="fas fa-eye"></i> Đã xem
                        </button>
                        <button type="button" class="btn btn-warning" onclick="updateStatus('shortlisted')">
                            <i class="fas fa-star"></i> Danh sách ngắn
                        </button>
                        <button type="button" class="btn btn-success" onclick="updateStatus('accepted')">
                            <i class="fas fa-check"></i> Chấp nhận
                        </button>
                        <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.5.0/dist/axios.min.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>
```

---

## JavaScript Implementation

### Main Dashboard Script (`dashboard.js`)

```javascript
class DashboardAPI {
    constructor() {
        this.baseURL = '/api/dashboard';
        this.token = localStorage.getItem('auth_token');
        this.currentApplicationId = null;
        
        // Configure Axios defaults
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadDashboard();
        this.setupTabNavigation();
    }
    
    setupEventListeners() {
        // Logout handler
        document.getElementById('logout-btn').addEventListener('click', this.logout.bind(this));
        
        // Tab navigation
        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                this.showTab(e.target.dataset.tab);
            });
        });
        
        // Status filter buttons
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.filterApplications(e.target.dataset.filter);
            });
        });
    }
    
    setupTabNavigation() {
        const tabs = document.querySelectorAll('.nav-link[data-tab]');
        const contents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active classes
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));
                
                // Add active class
                tab.classList.add('active');
                const targetTab = document.getElementById(`tab-${tab.dataset.tab}`);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            });
        });
    }
    
    async loadDashboard() {
        try {
            this.showLoading('dashboard');
            
            const response = await axios.get(`${this.baseURL}/`);
            const data = response.data.data;
            
            this.renderStatistics(data.statistics);
            this.renderRecentJobs(data.recent_jobs);
            this.renderRecentApplications(data.recent_applications);
            
            this.hideLoading('dashboard');
        } catch (error) {
            this.handleError('Không thể tải dữ liệu dashboard', error);
        }
    }
    
    renderStatistics(stats) {
        const container = document.getElementById('statistics-cards');
        
        const cards = [
            {
                title: 'Tổng công việc',
                value: stats.total_jobs,
                icon: 'fas fa-briefcase',
                color: 'primary'
            },
            {
                title: 'Tổng ứng viên',
                value: stats.total_applications,
                icon: 'fas fa-users',
                color: 'info'
            },
            {
                title: 'Chờ xử lý',
                value: stats.pending_applications,
                icon: 'fas fa-clock',
                color: 'warning'
            },
            {
                title: 'Có ứng viên',
                value: stats.jobs_with_applications,
                icon: 'fas fa-user-check',
                color: 'success'
            }
        ];
        
        container.innerHTML = cards.map(card => `
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-${card.color}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">${card.title}</h6>
                                <h3 class="mb-0">${card.value}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="${card.icon}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    renderRecentJobs(jobs) {
        const container = document.getElementById('recent-jobs');
        
        if (jobs.length === 0) {
            container.innerHTML = '<p class="text-muted">Chưa có công việc nào</p>';
            return;
        }
        
        container.innerHTML = jobs.map(job => `
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                <div>
                    <h6 class="mb-1">${job.title}</h6>
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        ${new Date(job.created_at).toLocaleDateString('vi-VN')}
                    </small>
                    ${job.is_urgent ? '<span class="badge bg-warning text-dark ms-2">Gấp</span>' : ''}
                    ${job.is_featured ? '<span class="badge bg-primary ms-2">Nổi bật</span>' : ''}
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="dashboard.viewJobApplications(${job.id})">
                    <i class="fas fa-users"></i> Xem ứng viên
                </button>
            </div>
        `).join('');
    }
    
    renderRecentApplications(applications) {
        const container = document.getElementById('recent-applications');
        
        if (applications.length === 0) {
            container.innerHTML = '<p class="text-muted">Chưa có ứng viên nào</p>';
            return;
        }
        
        const statusColors = {
            pending: 'warning',
            reviewed: 'info',
            shortlisted: 'success',
            accepted: 'success',
            rejected: 'danger'
        };
        
        container.innerHTML = applications.map(app => `
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                <div>
                    <h6 class="mb-1">${app.applicant_name}</h6>
                    <small class="text-muted">
                        ${app.job_title}
                    </small>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        ${app.applied_at_human}
                    </small>
                </div>
                <div>
                    <span class="badge bg-${statusColors[app.status] || 'secondary'}">${app.status}</span>
                    <button class="btn btn-outline-primary btn-sm ms-2" onclick="dashboard.viewApplication(${app.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }
    
    async viewJobApplications(jobId) {
        try {
            this.showTab('jobs');
            this.showLoading('job-details');
            
            const response = await axios.get(`${this.baseURL}/jobs/${jobId}/applications`);
            const data = response.data.data;
            
            this.renderJobApplications(data);
            this.hideLoading('job-details');
        } catch (error) {
            this.handleError('Không thể tải danh sách ứng viên', error);
        }
    }
    
    renderJobApplications(data) {
        const container = document.getElementById('job-details');
        
        container.innerHTML = `
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4>${data.job.title}</h4>
                    <p class="text-muted">${data.job.company_info.name}</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <span class="btn btn-outline-secondary">${data.statistics.total_applications} ứng viên</span>
                        <span class="btn btn-outline-warning">${data.statistics.pending} chờ xử lý</span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                ${data.applications.map(app => `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6>${app.applicant_name}</h6>
                                        <p class="text-muted mb-1">${app.applicant_email}</p>
                                        <small class="text-muted">${app.applied_at_human}</small>
                                    </div>
                                    <span class="badge bg-${this.getStatusColor(app.status)}">${app.status}</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm" onclick="dashboard.viewApplication(${app.id})">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    async viewApplication(applicationId) {
        try {
            // For demo, we'll show the modal with sample data
            // In real implementation, you might want to fetch detailed application data
            this.currentApplicationId = applicationId;
            
            const modal = new bootstrap.Modal(document.getElementById('applicationModal'));
            
            // Sample application details
            document.getElementById('application-details').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Thông tin ứng viên</h6>
                        <p><strong>Họ tên:</strong> John Doe</p>
                        <p><strong>Email:</strong> john@example.com</p>
                        <p><strong>Điện thoại:</strong> 0909123456</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Trạng thái hiện tại</h6>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                </div>
                <hr>
                <div>
                    <h6>Thư xin việc</h6>
                    <p>I am very interested in this position and believe my skills would be a great fit...</p>
                </div>
                <hr>
                <div>
                    <h6>Ghi chú của nhà tuyển dụng</h6>
                    <textarea class="form-control" id="employer-notes" rows="3" placeholder="Nhập ghi chú..."></textarea>
                </div>
            `;
            
            modal.show();
        } catch (error) {
            this.handleError('Không thể tải chi tiết ứng viên', error);
        }
    }
    
    async updateStatus(status) {
        if (!this.currentApplicationId) return;
        
        try {
            const notes = document.getElementById('employer-notes').value;
            
            await axios.put(`${this.baseURL}/applications/${this.currentApplicationId}/status`, {
                status: status,
                employer_notes: notes
            });
            
            this.showSuccess(`Cập nhật trạng thái thành ${status} thành công`);
            
            // Close modal and refresh data
            bootstrap.Modal.getInstance(document.getElementById('applicationModal')).hide();
            this.loadDashboard();
            
        } catch (error) {
            this.handleError('Không thể cập nhật trạng thái', error);
        }
    }
    
    getStatusColor(status) {
        const colors = {
            pending: 'warning',
            reviewed: 'info',
            shortlisted: 'success',
            accepted: 'success',
            rejected: 'danger'
        };
        return colors[status] || 'secondary';
    }
    
    showTab(tabName) {
        const tabs = document.querySelectorAll('.nav-link[data-tab]');
        const contents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.tab === tabName) {
                tab.classList.add('active');
            }
        });
        
        contents.forEach(content => {
            content.classList.remove('active');
            if (content.id === `tab-${tabName}`) {
                content.classList.add('active');
            }
        });
    }
    
    showLoading(containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                </div>
            `;
        }
    }
    
    hideLoading(containerId) {
        // Loading will be replaced by actual content
    }
    
    showSuccess(message) {
        this.showAlert(message, 'success');
    }
    
    showError(message) {
        this.showAlert(message, 'danger');
    }
    
    showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
    
    handleError(message, error) {
        console.error('Dashboard Error:', error);
        
        if (error.response?.status === 401) {
            this.showError('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
            setTimeout(() => this.logout(), 2000);
        } else {
            this.showError(message);
        }
    }
    
    logout() {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    }
}

// Global functions for onclick handlers
function updateStatus(status) {
    dashboard.updateStatus(status);
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new DashboardAPI();
});
```

---

## CSS Styling (`dashboard.css`)

```css
/* Custom Dashboard Styles */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-body {
    padding: 1.25rem;
}

.navbar-brand {
    font-weight: bold;
}

/* Status badges */
.badge {
    font-size: 0.75rem;
}

/* Loading spinner */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-md-3 {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
}

/* Status color mapping */
.status-pending {
    color: #ffc107;
}

.status-reviewed {
    color: #17a2b8;
}

.status-shortlisted {
    color: #28a745;
}

.status-accepted {
    color: #28a745;
}

.status-rejected {
    color: #dc3545;
}

/* Animation for cards */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Alert positioning */
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1060;
    min-width: 300px;
}
```

---

## Mobile Responsive Considerations

### Responsive Design Features
1. **Mobile-first approach** với Bootstrap breakpoints
2. **Collapsible sidebar** cho mobile devices
3. **Touch-friendly buttons** và tap targets
4. **Optimized modals** cho mobile screens

### Mobile-specific JavaScript
```javascript
// Add to DashboardAPI class
checkMobileView() {
    return window.innerWidth < 768;
}

adaptToMobile() {
    if (this.checkMobileView()) {
        // Hide sidebar by default on mobile
        document.querySelector('.col-md-3').classList.add('d-none');
        
        // Add mobile menu toggle
        this.addMobileMenuToggle();
    }
}

addMobileMenuToggle() {
    const navbar = document.querySelector('.navbar');
    const toggleButton = `
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    `;
    navbar.insertAdjacentHTML('beforeend', toggleButton);
}
```

---

## Testing & Deployment

### Testing Checklist
- [ ] Dashboard loads without errors
- [ ] Authentication works properly
- [ ] All API endpoints respond correctly
- [ ] Mobile responsiveness works
- [ ] Error handling displays properly
- [ ] Real-time updates function correctly

### Performance Optimization
1. **Lazy loading** cho large datasets
2. **Caching** API responses khi có thể
3. **Debouncing** cho search và filter functions
4. **Image optimization** cho avatars và attachments

### Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## Deployment Instructions

### 1. File Structure
```
resources/views/dashboard/
├── index.blade.php          # Main dashboard template
├── assets/
│   ├── js/
│   │   ├── dashboard.js     # Main JavaScript file
│   │   └── dashboard.min.js # Minified version
│   └── css/
│       ├── dashboard.css    # Custom styles
│       └── dashboard.min.css # Minified version
└── components/
    ├── sidebar.blade.php    # Sidebar component
    ├── modals.blade.php     # Modal components
    └── alerts.blade.php     # Alert components
```

### 2. Laravel Route Setup
```php
// routes/web.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});
```

### 3. Build Process
```bash
# Install dependencies
npm install

# Build assets for production
npm run production

# Or for development
npm run dev
```

### 4. Environment Configuration
```env
# .env
APP_URL=https://lamgame.localhost
SANCTUM_STATEFUL_DOMAINS=lamgame.localhost
SESSION_DOMAIN=.lamgame.localhost
```

Tài liệu này cung cấp framework hoàn chỉnh để tích hợp API Dashboard vào frontend, với responsive design, error handling, và user experience tối ưu.