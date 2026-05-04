@extends('layouts.job-admin')

@section('title', 'Quản lý Yêu cầu Thuê Dev')
@section('page-title', 'Yêu cầu Thuê Team Dev')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white shadow-sm rounded-lg px-4 py-5 sm:p-6">
        <form method="GET" class="flex gap-3 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select name="status" class="mt-1 rounded-md border-gray-300 text-sm">
                    <option value="">Tất cả</option>
                    @foreach(['new' => 'Mới', 'contacted' => 'Đã liên hệ', 'quoted' => 'Đã báo giá', 'closed' => 'Đóng'] as $k => $v)
                        <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, email, công ty..." class="mt-1 w-full rounded-md border-gray-300 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-500">Lọc</button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khách hàng</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại dự án</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngân sách</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mô tả</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($hireRequests as $req)
                <tr>
                    <td class="px-4 py-3 text-sm">
                        <div class="font-medium">{{ $req->name }}</div>
                        <div class="text-gray-500 text-xs">{{ $req->email }}</div>
                        @if($req->phone)<div class="text-gray-500 text-xs">{{ $req->phone }}</div>@endif
                        @if($req->company)<div class="text-gray-400 text-xs">{{ $req->company }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ ucfirst($req->project_type) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $req->budget_range ?: '—' }}</td>
                    <td class="px-4 py-3 text-sm max-w-xs truncate" title="{{ $req->description }}">{{ Str::limit($req->description, 80) }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $req->status === 'new' ? 'bg-blue-100 text-blue-700' : ($req->status === 'contacted' ? 'bg-yellow-100 text-yellow-700' : ($req->status === 'quoted' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ $req->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm">
                        <form method="POST" action="{{ route('admin.hire-requests.update-status', $req->id) }}" class="flex gap-2 items-center">
                            @csrf
                            <select name="status" class="rounded border-gray-300 text-xs py-1">
                                @foreach(['new', 'contacted', 'quoted', 'closed'] as $s)
                                    <option value="{{ $s }}" {{ $req->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            <button class="text-primary-600 hover:underline text-xs">Lưu</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Chưa có yêu cầu nào.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $hireRequests->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
