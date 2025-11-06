@extends('job-dashboard.layout')

@section('title', 'Job của tôi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Job của tôi</h4>
    <a href="{{ route('job.create') }}" class="btn btn-primary">Đăng Job Mới</a>
</div>

<div class="card">
    <div class="card-body">
        @if(isset($jobs['data']) && count($jobs['data']) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Loại</th>
                            <th>Lương</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs['data'] as $job)
                        <tr>
                            <td>{{ $job['name'] ?? $job['title'] }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($job['job_type'] ?? 'N/A') }}
                                </span>
                            </td>
                            <td>{{ $job['salary_range'] ?? 'Thỏa thuận' }}</td>
                            <td>
                                <span class="badge {{ $job['status'] == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $job['status'] == 1 ? 'Đang tuyển' : 'Tạm dừng' }}
                                </span>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($job['created_at'])) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('job.edit', $job['id']) }}" class="btn btn-outline-primary">Sửa</a>
                                    <form method="POST" action="{{ route('job.delete', $job['id']) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Xác nhận xóa job này?')">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($jobs['links']))
                <div class="d-flex justify-content-center">
                    {!! $jobs['links'] !!}
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <p>Bạn chưa có job nào.</p>
                <a href="{{ route('job.create') }}" class="btn btn-primary">Đăng Job Đầu Tiên</a>
            </div>
        @endif
    </div>
</div>
@endsection
