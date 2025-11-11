@extends('admin.layouts.app')

@section('title', 'Quản Lý Job')

@push('styles')
<style>
.job-actions .btn {
    margin-right: 5px;
}
.job-stats {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state-icon {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 20px;
}
</style>
@endpush

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Quản Lý Job</h1>
        </div>
        <div class="page-actions">
            <a href="{{ route('job.dashboard.index') }}" class="btn btn-secondary">
                <i class="icon-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('job.dashboard.create') }}" class="btn btn-primary">
                <i class="icon-plus"></i> Đăng Job Mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert--success">
            <i class="icon-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert--error">
            <i class="icon-alert-circle"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="page-content">
        @if($jobs && $jobs->count() > 0)
            <div class="job-stats">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <h3>{{ $jobs->total() ?? $jobs->count() }}</h3>
                            <p>Tổng số job</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tên Job</th>
                                    <th>SKU</th>
                                    <th>Ngày tạo</th>
                                    <th width="200">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td>
                                        <strong>{{ $job->name ?: $job->sku }}</strong>
                                    </td>
                                    <td>
                                        <code class="badge badge-light">{{ $job->sku }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ date('d/m/Y H:i', strtotime($job->created_at)) }}
                                        </small>
                                    </td>
                                    <td class="job-actions">
                                        <a href="{{ route('job.dashboard.edit', $job->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="icon-edit"></i> Sửa
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('job.dashboard.destroy', $job->id) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa job này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="icon-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($jobs, 'links'))
                        <div class="pagination-wrapper">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="icon-briefcase"></i>
                </div>
                <h3>Chưa có job nào</h3>
                <p class="text-muted">Bắt đầu bằng cách tạo job đầu tiên của bạn.</p>
                <a href="{{ route('job.dashboard.create') }}" class="btn btn-primary">
                    <i class="icon-plus"></i> Đăng Job Đầu Tiên
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
