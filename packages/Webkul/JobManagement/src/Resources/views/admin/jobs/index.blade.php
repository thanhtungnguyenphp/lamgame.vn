@extends('admin.layouts.app')

@section('title', __('job_management::app.admin.jobs.title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/jobs.css') }}">
@endpush

@section('admin-content')
<div class="admin-page">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="page-header__content">
                <h1 class="page-header__title">{{ __('job_management::app.admin.jobs.title') }}</h1>
                <p class="page-header__subtitle">Quản lý các tin tuyển dụng và ứng viên</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row statistics-cards mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card bg-gradient-primary">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['total'] }}</h3>
                            <p class="stat-label">Total Jobs</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card bg-gradient-success">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['published'] }}</h3>
                            <p class="stat-label">Published</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card bg-gradient-warning">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['unpublished'] }}</h3>
                            <p class="stat-label">Unpublished</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card bg-gradient-info">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ $stats['thisWeek'] }}</h3>
                            <p class="stat-label">This Week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-dark">{{ __('job_management::app.admin.jobs.title') }}</h4>
                        <small class="text-muted">Manage job postings and applications</small>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('job_management::app.admin.jobs.add-title') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Search and Filter Bar -->
                <div class="table-toolbar p-3 bg-light border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search jobs..." id="searchJobs">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="filterJobs('all')">All</button>
                                <button type="button" class="btn btn-outline-success" onclick="filterJobs('published')">Published</button>
                                <button type="button" class="btn btn-outline-warning" onclick="filterJobs('unpublished')">Unpublished</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jobs Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="jobsTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th class="border-0">Job</th>
                                <th class="border-0">Company</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Created</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                            <tr class="job-row" data-status="{{ $job->status ? 'published' : 'unpublished' }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input job-checkbox" type="checkbox" value="{{ $job->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="job-info">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-decoration-none text-dark fw-semibold">
                                                {{ $job->name ?: $job->sku }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">ID: {{ $job->id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="company-info">
                                        @if($job->company_name)
                                            <span class="badge bg-light text-dark">{{ $job->company_name }}</span>
                                        @else
                                            <span class="text-muted">No Company</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($job->status)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fas fa-check-circle me-1"></i>Published
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="fas fa-pause-circle me-1"></i>Unpublished
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $job->created_at ? \Carbon\Carbon::parse($job->created_at)->format('M d, Y') : 'N/A' }}</div>
                                        <small class="text-muted">{{ $job->created_by ?: 'System' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($job->status)
                                                <form method="POST" action="{{ route('admin.jobs.unpublish', $job->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Unpublish">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.jobs.publish', $job->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Publish">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteJob({{ $job->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No jobs found</h5>
                                        <p class="text-muted">Start by creating your first job posting</p>
                                        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Job
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($jobs->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            <small class="text-muted">
                                Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} of {{ $jobs->total() }} results
                            </small>
                        </div>
                        <div>
                            {{ $jobs->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .statistics-cards .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease;
        }
        .statistics-cards .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.2);
            margin-right: 1rem;
        }
        .stat-icon i {
            font-size: 1.5rem;
            color: white;
        }
        .stat-content {
            color: white;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .stat-label {
            margin-bottom: 0;
            opacity: 0.9;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .table-toolbar {
            background-color: #f8f9fa !important;
        }
        .job-info h6 a:hover {
            color: #0d6efd !important;
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
        .empty-state {
            padding: 2rem;
        }
        .card {
            border-radius: 12px;
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }
    </style>

    <script>
        // Search functionality
        document.getElementById('searchJobs').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#jobsTable tbody tr.job-row');
            
            rows.forEach(row => {
                const jobName = row.querySelector('.job-info h6 a').textContent.toLowerCase();
                const company = row.querySelector('.company-info').textContent.toLowerCase();
                
                if (jobName.includes(searchTerm) || company.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filter functionality
        function filterJobs(status) {
            const rows = document.querySelectorAll('#jobsTable tbody tr.job-row');
            const buttons = document.querySelectorAll('.btn-group button');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.job-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Delete job function
        function deleteJob(jobId) {
            if (confirm('{{ __("job_management::app.admin.jobs.delete-confirm") }}')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/jobs/${jobId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/jobs.js') }}"></script>
@endpush
