<!DOCTYPE html>
<html>
<head>
    <title>Quản Lý Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản Lý Job</h2>
            <div>
                <a href="{{ route('job.dashboard.index') }}" class="btn btn-secondary me-2">Dashboard</a>
                <a href="{{ route('job.dashboard.create') }}" class="btn btn-primary">Đăng Job Mới</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($jobs && $jobs->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên Job</th>
                                    <th>SKU</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td><strong>{{ $job->name ?: $job->sku }}</strong></td>
                                    <td><code>{{ $job->sku }}</code></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($job->created_at)) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('job.dashboard.edit', $job->id) }}" class="btn btn-outline-primary">Sửa</a>
                                            <form method="POST" action="{{ route('job.dashboard.destroy', $job->id) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa job này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($jobs, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $jobs->links() }}
                        </div>
                    @endif
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
