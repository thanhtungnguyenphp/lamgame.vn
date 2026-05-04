@extends('layouts.job-admin')

@section('title', 'Quản lý Đánh giá')
@section('page-title', 'Quản lý Đánh giá Source Game')

@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white shadow-sm rounded-lg px-4 py-5 sm:p-6">
        <div class="flex gap-3 items-center">
            @foreach(['pending' => 'Chờ duyệt', 'published' => 'Đã duyệt', 'hidden' => 'Ẩn', 'all' => 'Tất cả'] as $key => $label)
                <a href="?status={{ $key }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $status === $key ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <form id="bulkForm" method="POST" action="{{ route('admin.reviews.bulk') }}">
            @csrf
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" onclick="toggleAll(this)"></th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khách hàng</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sản phẩm</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội dung</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reviews as $review)
                    <tr>
                        <td class="px-4 py-3"><input type="checkbox" name="ids[]" value="{{ $review->id }}"></td>
                        <td class="px-4 py-3 text-sm">
                            {{ $review->customer->first_name ?? '' }} {{ $review->customer->last_name ?? '' }}
                            @if($review->is_verified_purchase) <span class="text-green-600 text-xs">✓ Đã mua</span> @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $review->product->sku ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate">{{ $review->title ?: Str::limit($review->content, 60) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $review->status === 'published' ? 'bg-green-100 text-green-700' : ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $review->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($review->status !== 'published')
                            <form method="POST" action="{{ route('admin.reviews.update-status', $review->id) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="published">
                                <button class="text-green-600 hover:underline text-xs">Duyệt</button>
                            </form>
                            @endif
                            @if($review->status !== 'hidden')
                            <form method="POST" action="{{ route('admin.reviews.update-status', $review->id) }}" class="inline ml-2">
                                @csrf
                                <input type="hidden" name="status" value="hidden">
                                <button class="text-red-600 hover:underline text-xs">Ẩn</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Không có đánh giá nào.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-3 bg-gray-50 flex items-center gap-3">
                <select name="action" class="rounded-md border-gray-300 text-sm">
                    <option value="publish">Duyệt đã chọn</option>
                    <option value="hide">Ẩn đã chọn</option>
                    <option value="delete">Xóa đã chọn</option>
                </select>
                <button type="submit" class="px-3 py-1.5 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-500">Thực hiện</button>
            </div>
        </form>

        <div class="px-4 py-3">{{ $reviews->withQueryString()->links() }}</div>
    </div>
</div>

<script>
function toggleAll(el) {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = el.checked);
}
</script>
@endsection
