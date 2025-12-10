@extends('admin.layouts.app')

@section('title', 'Quản Lý Ứng Viên')

@push('styles')
<style>
.applications-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.filter-section {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.06);
}

.filter-section .form-label {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem 1rem;
    align-items: flex-end;
}

.filter-row__field {
    min-width: 220px;
}

.filter-row__actions {
    display: flex;
    gap: 0.5rem;
}

.filter-summary {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.applications-table {
    background: #ffffff;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
}

.table-header {
    background: #f8fafc;
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.table-responsive {
    width: 100%;
}

.applications-table table {
    width: 100%;
    border-collapse: collapse;
}

.applications-table th,
.applications-table td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.applications-table thead th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
}

.applications-table tbody tr:hover {
    background: #f9fafb;
}

.applications-table td.col-name {
    font-weight: 500;
}

.applications-table td.col-email {
    max-width: 260px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.applications-table td.col-job {
    max-width: 220px;
}

.applications-table td.col-status {
    white-space: nowrap;
}

.applications-table td.col-actions {
    text-align: right;
    white-space: nowrap;
}

.application-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    margin-right: 0.25rem;
}

.application-actions .btn:last-child {
    margin-right: 0;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.6rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-new { background: #dbeafe; color: #1e40af; }
.status-reviewed { background: #fef3c7; color: #92400e; }
.status-contacted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }
.status-accepted { background: #dcfce7; color: #166534; }

@media (max-width: 768px) {
    .applications-page {
        gap: 1rem;
    }

    .applications-table th,
    .applications-table td {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    .applications-table td.col-actions {
        text-align: left;
    }
}
</style>
@endpush

@section('admin-content')
<div class="content applications-page">
    <div class="page-header">
        <div class="page-title">
            <h1>Quản Lý Ứng Viên</h1>
            <p>Xem và quản lý các ứng viên đã apply job</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.applications.index') }}">
            <div class="filter-row">
                <div class="filter-row__field">
                    <label class="form-label">Lọc theo Job</label>
                    <select name="job_id" class="form-select">
                        <option value="">Tất cả Jobs</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ $jobId == $job->id ? 'selected' : '' }}>
                                {{ $job->title ?: 'Job #' . $job->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-row__actions">
                    <button type="submit" class="btn btn--primary">Lọc</button>
                    @if(request('job_id'))
                        <a href="{{ route('admin.applications.index') }}" class="btn btn--secondary">Bỏ lọc</a>
                    @endif
                </div>
            </div>

            <div class="filter-summary">
                @if($jobId)
                    Hiển thị {{ $applications->total() }} ứng viên cho job ID #{{ $jobId }}
                @else
                    Hiển thị {{ $applications->total() }} ứng viên cho tất cả jobs
                @endif
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="applications-table">
        <div class="table-header">
            <h3>Danh Sách Ứng Viên</h3>
        </div>

        @if($applications->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Số ĐT</th>
                            <th>Job Apply</th>
                            <th>Ngày Apply</th>
                            <th>Trạng Thái</th>
                            <th style="text-align: right;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td class="col-name">
                                <strong>{{ $application->applicant_name ?: 'N/A' }}</strong>
                            </td>
                            <td class="col-email">{{ $application->applicant_email ?: 'N/A' }}</td>
                            <td>{{ $application->applicant_phone ?: 'N/A' }}</td>
                            <td class="col-job">{{ $application->job_title ?: 'Job #' . $application->job_id }}</td>
                            <td>{{ $application->applied_at ? date('d/m/Y H:i', strtotime($application->applied_at)) : 'N/A' }}</td>
                            <td class="col-status">
                                @php
                                    $statusMap = [
                                        'pending' => ['label' => 'Chờ xử lý', 'class' => 'status-new'],
                                        'reviewed' => ['label' => 'Đã xem', 'class' => 'status-reviewed'],
                                        'shortlisted' => ['label' => 'Lọt vòng', 'class' => 'status-contacted'],
                                        'rejected' => ['label' => 'Từ chối', 'class' => 'status-rejected'],
                                        'accepted' => ['label' => 'Chấp nhận', 'class' => 'status-accepted'],
                                    ];
                                    $status = $statusMap[$application->status] ?? ['label' => 'Mới', 'class' => 'status-new'];
                                @endphp
                                <span class="status-badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="col-actions">
                                <div class="application-actions">
                                    <a href="{{ route('admin.applications.show', $application->id) }}" 
                                       class="btn btn--info btn--sm" title="Xem chi tiết">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if($application->resume_file_path)
                                        <a href="{{ route('admin.applications.download-cv', $application->id) }}" 
                                           target="_blank" 
                                           class="btn btn--secondary btn--sm" title="Xem CV">
                                            <i class="icon-file"></i>
                                        </a>
                                    @endif
                                    <form method="POST" 
                                          action="{{ route('admin.applications.destroy', $application->id) }}" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa ứng viên này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn--sm" title="Xóa">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-pagination">
                {{ $applications->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="icon-users"></i>
                </div>
                <h3>Chưa có ứng viên nào</h3>
                <p>Các ứng viên apply job sẽ hiển thị ở đây</p>
            </div>
        @endif
    </div>
</div>
@endsection
