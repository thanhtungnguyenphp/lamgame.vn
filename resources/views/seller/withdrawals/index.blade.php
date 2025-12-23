@extends('shop::layouts.master')

@section('page_title')
    Lịch sử rút tiền
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Lịch sử rút tiền</h1>
        <a href="{{ route('seller.withdrawals.create') }}" class="px-6 py-3 text-white bg-purple-600 rounded-lg hover:bg-purple-700">
            + Rút tiền mới
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6 mb-6 bg-white rounded-lg shadow">
        <p class="text-lg">Số dư khả dụng: <span class="text-2xl font-bold text-purple-600">{{ number_format($availableBalance) }}đ</span></p>
    </div>

    <div class="overflow-hidden bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Ngày</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Số tiền</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Ngân hàng</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Ghi chú</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($withdrawals as $withdrawal)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-bold whitespace-nowrap">{{ number_format($withdrawal->amount) }}đ</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($withdrawal->status === 'pending')
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">Chờ xử lý</span>
                        @elseif($withdrawal->status === 'processing')
                            <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">Đang xử lý</span>
                        @elseif($withdrawal->status === 'completed')
                            <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">Hoàn thành</span>
                        @else
                            <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded-full">Từ chối</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <div>{{ $withdrawal->bank_name }}</div>
                            <div class="text-gray-500">{{ $withdrawal->bank_account }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($withdrawal->admin_note)
                            <span class="text-sm text-gray-600">{{ $withdrawal->admin_note }}</span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        Chưa có yêu cầu rút tiền nào. <a href="{{ route('seller.withdrawals.create') }}" class="text-purple-600 hover:underline">Tạo yêu cầu đầu tiên</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
