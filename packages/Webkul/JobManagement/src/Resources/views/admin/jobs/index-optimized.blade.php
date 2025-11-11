@extends('admin.layouts.app')

@section('title', __('job_management::app.admin.jobs.title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/jobs.css') }}">
@endpush

@section('admin-content')
<div class="admin-page">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb__list">
            <li class="breadcrumb__item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb__item active" aria-current="page">Jobs</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header__content">
            <h1 class="page-header__title">{{ __('job_management::app.admin.jobs.title') }}</h1>
            <p class="page-header__subtitle">Quản lý các tin tuyển dụng và ứng viên</p>
        </div>
        <div class="page-header__actions">
            <a href="{{ route('admin.jobs.create') }}" class="btn btn--primary btn--lg">
                <i class="fas fa-plus"></i>
                <span>{{ __('job_management::app.admin.jobs.add-title') }}</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid" id="statsGrid">
        <x-admin.stat-card 
            title="Total Jobs" 
            :value="$stats['total']" 
            icon="fas fa-briefcase" 
            color="primary"
            data-stat="total"
        />
        <x-admin.stat-card 
            title="Published" 
            :value="$stats['published']" 
            icon="fas fa-check-circle" 
            color="success"
            data-stat="published"
        />
        <x-admin.stat-card 
            title="Unpublished" 
            :value="$stats['unpublished']" 
            icon="fas fa-pause-circle" 
            color="warning"
            data-stat="unpublished"
        />
        <x-admin.stat-card 
            title="This Week" 
            :value="$stats['thisWeek']" 
            icon="fas fa-calendar-week" 
            color="info"
            data-stat="thisWeek"
        />
    </div>

    <!-- Data Table -->
    <div class="data-table-wrapper">
        <!-- Toolbar -->
        <div class="data-table__toolbar">
            <div class="toolbar__left">
                <div class="search-box">
                    <i class="fas fa-search search-box__icon"></i>
                    <input 
                        type="text" 
                        id="searchJobs" 
                        placeholder="Tìm kiếm theo tên, công ty, ID..." 
                        class="search-box__input"
                        aria-label="Tìm kiếm jobs"
                    >
                    <button type="button" class="search-box__clear" id="clearSearch" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="results-count" id="resultsCount">
                    Hiển thị {{ $jobs->count() }} kết quả
                </div>
            </div>
            <div class="toolbar__right">
                <div class="filter-group" role="group" aria-label="Lọc theo trạng thái">
                    <button type="button" class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i>
                        <span>Tất cả</span>
                    </button>
                    <button type="button" class="filter-btn" data-filter="published">
                        <i class="fas fa-check-circle"></i>
                        <span>Đã xuất bản</span>
                    </button>
                    <button type="button" class="filter-btn" data-filter="unpublished">
                        <i class="fas fa-pause-circle"></i>
                        <span>Chưa xuất bản</span>
                    </button>
                </div>
                <button type="button" class="btn btn--secondary btn--icon" id="refreshData" title="Làm mới dữ liệu">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulkActionsBar" class="bulk-actions-bar">
            <div class="bulk-actions__info">
                <span id="selectedCount">0</span> jobs đã chọn
            </div>
            <div class="bulk-actions__buttons">
                <button type="button" class="btn btn--sm btn--success" data-bulk-action="publish">
                    <i class="fas fa-check"></i> Xuất bản
                </button>
                <button type="button" class="btn btn--sm btn--warning" data-bulk-action="unpublish">
                    <i class="fas fa-pause"></i> Ẩn
                </button>
                <button type="button" class="btn btn--sm btn--danger" data-bulk-action="delete">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="data-table" id="dataTable">
            <table class="data-table__table" role="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input 
                                type="checkbox" 
                                id="selectAll" 
                                class="checkbox"
                                aria-label="Chọn tất cả"
                            >
                        </th>
                        <th class="sortable" data-sort="name">
                            <div class="th-content">
                                <span>Job</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="company">
                            <div class="th-content">
                                <span>Công ty</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="status">
                            <div class="th-content">
                                <span>Trạng thái</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </div>
                        </th>
                        <th class="sortable" data-sort="date">
                            <div class="th-content">
                                <span>Ngày tạo</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </div>
                        </th>
                        <th style="width: 200px; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr class="job-row" data-status="{{ $job->status ? 'published' : 'unpublished' }}" data-job-id="{{ $job->id }}">
                        <td>
                            <input 
                                type="checkbox" 
                                class="checkbox job-checkbox" 
                                value="{{ $job->id }}"
                                aria-label="Chọn job {{ $job->name }}"
                            >
                        </td>
                        <td>
                            <div class="job-info">
                                <a href="{{ route('admin.jobs.show', $job->id) }}" class="job-info__title">
                                    {{ $job->name ?: $job->sku }}
                                </a>
                                <span class="job-info__meta">ID: {{ $job->id }}</span>
                            </div>
                        </td>
                        <td>
                            @if($job->company_name)
                                <span class="badge badge--light">{{ $job->company_name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($job->status)
                                <span class="badge badge--success">
                                    <i class="fas fa-check-circle"></i> Đã xuất bản
                                </span>
                            @else
                                <span class="badge badge--warning">
                                    <i class="fas fa-pause-circle"></i> Chưa xuất bản
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="date-info">
                                <div>{{ $job->created_at ? $job->created_at->format('d/m/Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $job->created_by ?: 'System' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-group">
                                <a 
                                    href="{{ route('admin.jobs.show', $job->id) }}" 
                                    class="action-btn" 
                                    title="Xem chi tiết"
                                    aria-label="Xem chi tiết job {{ $job->name }}"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a 
                                    href="{{ route('admin.jobs.edit', $job->id) }}" 
                                    class="action-btn" 
                                    title="Chỉnh sửa"
                                    aria-label="Chỉnh sửa job {{ $job->name }}"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($job->status)
                                    <form method="POST" action="{{ route('admin.jobs.unpublish', $job->id) }}" class="inline-form">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="action-btn" 
                                            title="Ẩn"
                                            aria-label="Ẩn job {{ $job->name }}"
                                        >
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.jobs.publish', $job->id) }}" class="inline-form">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="action-btn" 
                                            title="Xuất bản"
                                            aria-label="Xuất bản job {{ $job->name }}"
                                        >
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                <button 
                                    type="button" 
                                    class="action-btn action-btn--danger" 
                                    data-delete-job="{{ $job->id }}"
                                    data-job-name="{{ $job->name ?: $job->sku }}"
                                    title="Xóa"
                                    aria-label="Xóa job {{ $job->name }}"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state__icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <h3 class="empty-state__title">Chưa có jobs nào</h3>
                                <p class="empty-state__description">Bắt đầu bằng cách tạo job đầu tiên của bạn</p>
                                <a href="{{ route('admin.jobs.create') }}" class="btn btn--primary">
                                    <i class="fas fa-plus"></i> Tạo Job
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
        <div class="data-table__footer">
            <div class="pagination-info">
                Hiển thị {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} trong tổng số {{ $jobs->total() }} kết quả
            </div>
            <div class="pagination-wrapper">
                {{ $jobs->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p>Đang xử lý...</p>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="modal">
    <div class="modal__overlay"></div>
    <div class="modal__content">
        <div class="modal__header">
            <h3 class="modal__title" id="modalTitle">Xác nhận</h3>
            <button type="button" class="modal__close" id="modalClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal__body">
            <div class="modal__icon" id="modalIcon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <p class="modal__message" id="modalMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--secondary" id="modalCancel">Hủy</button>
            <button type="button" class="btn btn--danger" id="modalConfirm">Xác nhận</button>
        </div>
    </div>
</div>

<!-- Screen Reader Announcements -->
<div id="sr-announcements" class="sr-only" role="status" aria-live="polite" aria-atomic="true"></div>

@push('scripts')
<script src="{{ asset('js/admin/jobs.js') }}"></script>
@endpush
