@extends('admin.layouts.app')

@section('title', 'Quản Lý Ứng Viên')

@push('styles')
<style>
.applications-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-section {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.application-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    margin-right: 0.25rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-new { background: #dbeafe; color: #1e40af; }
.status-reviewed { background: #fef3c7; color: #92400e; }
.status-contacted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }
.status-accepted { background: #dcfce7; color: #166534; }
</style>
@endpush

@section('admin-content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Quản Lý Ứng Viên</h1>
            <p>Xem và quản lý các ứng viên đã apply job</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.applications.index') }}">
            <div class="row align-items-end">
                <div class="col-md-4">
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn--primary">Lọc</button>
                </div>
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
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->applicant_name ?: 'N/A' }}</strong>
                            </td>
                            <td>{{ $application->applicant_email ?: 'N/A' }}</td>
                            <td>{{ $application->applicant_phone ?: 'N/A' }}</td>
                            <td>{{ $application->job_title ?: 'Job #' . $application->job_id }}</td>
                            <td>{{ $application->applied_at ? date('d/m/Y H:i', strtotime($application->applied_at)) : 'N/A' }}</td>
                            <td>
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
                            <td>
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
