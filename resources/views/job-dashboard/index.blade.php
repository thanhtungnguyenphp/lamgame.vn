<!DOCTYPE html>
<html>
<head>
    <title>Job Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Job Dashboard</h2>
            <a href="{{ route('job.dashboard.create') }}" class="btn btn-primary">Đăng Job Mới</a>
        </div>

        <!-- Thống kê -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5>Tổng Job</h5>
                        <h2>{{ $stats['total_jobs'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5>Job Đang Tuyển</h5>
                        <h2>{{ $stats['active_jobs'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5>Ứng Viên Mới</h5>
                        <h2>{{ $stats['new_applications'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5>Tổng Ứng Viên</h5>
                        <h2>{{ $stats['total_applications'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job gần đây -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Job Gần Đây</h5>
                <a href="{{ route('job.dashboard.jobs') }}" class="btn btn-sm btn-outline-primary">Xem Tất Cả</a>
            </div>
            <div class="card-body">
                @if($recentJobs && count($recentJobs) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên Job</th>
                                    <th>SKU</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentJobs as $job)
                                <tr>
                                    <td>{{ $job->name ?: $job->sku }}</td>
                                    <td><code>{{ $job->sku }}</code></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($job->created_at)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Chưa có job nào được tạo.</p>
                        <a href="{{ route('job.dashboard.create') }}" class="btn btn-primary">Đăng Job Đầu Tiên</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
