@extends('job-dashboard.layout')

@section('title', 'Quản lý Ứng viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Quản lý Ứng viên</h4>
    <div class="btn-group">
        <a href="?status=" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">Tất cả</a>
        <a href="?status=pending" class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">Chờ duyệt</a>
        <a href="?status=approved" class="btn btn-outline-success {{ request('status') == 'approved' ? 'active' : '' }}">Đã duyệt</a>
        <a href="?status=rejected" class="btn btn-outline-danger {{ request('status') == 'rejected' ? 'active' : '' }}">Từ chối</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(isset($applications['data']) && count($applications['data']) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ứng viên</th>
                            <th>Job</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Ngày apply</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications['data'] as $app)
                        <tr>
                            <td>{{ $app['candidate_name'] ?? 'N/A' }}</td>
                            <td>{{ $app['job_title'] ?? 'N/A' }}</td>
                            <td>{{ $app['email'] ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match($app['status'] ?? 'pending') {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning'
                                    };
                                    $statusText = match($app['status'] ?? 'pending') {
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối',
                                        default => 'Chờ duyệt'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>{{ date('d/m/Y H:i', strtotime($app['created_at'])) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('job.application.show', $app['id']) }}" class="btn btn-outline-info">Xem</a>
                                    @if($app['status'] == 'pending')
                                        <button class="btn btn-outline-success" onclick="updateStatus({{ $app['id'] }}, 'approved')">Duyệt</button>
                                        <button class="btn btn-outline-danger" onclick="updateStatus({{ $app['id'] }}, 'rejected')">Từ chối</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <p>Chưa có ứng viên nào apply.</p>
            </div>
        @endif
    </div>
</div>

<script>
function updateStatus(id, status) {
    if (confirm(`Xác nhận ${status === 'approved' ? 'duyệt' : 'từ chối'} ứng viên này?`)) {
        fetch(`/job-dashboard/applications/${id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        }).then(() => location.reload());
    }
}
</script>
@endsection
