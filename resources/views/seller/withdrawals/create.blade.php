@extends('shop::layouts.master')

@section('page_title')
    Rút tiền
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-4xl">
    <h1 class="mb-6 text-3xl font-bold">Yêu cầu rút tiền</h1>

    <div class="p-6 mb-6 bg-blue-50 rounded-lg">
        <p class="text-lg font-semibold">Số dư khả dụng: <span class="text-blue-600">{{ number_format($availableBalance) }}đ</span></p>
        <p class="mt-2 text-sm text-gray-600">Số tiền tối thiểu: 100,000đ | Thời gian xử lý: 3-5 ngày làm việc</p>
    </div>

    <form action="{{ route('seller.withdrawals.store') }}" method="POST" class="p-6 bg-white rounded-lg shadow">
        @csrf

        <div class="mb-6">
            <label class="block mb-2 font-medium">Số tiền rút *</label>
            <input type="number" name="amount" value="{{ old('amount') }}" min="100000" max="{{ $availableBalance }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
            @error('amount')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
        </div>

        <div class="p-4 mb-6 bg-gray-50 rounded-lg">
            <h3 class="mb-3 font-semibold">Thông tin ngân hàng</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Ngân hàng:</span> <span class="font-medium">{{ $seller->bank_name }}</span></p>
                <p><span class="text-gray-600">Số tài khoản:</span> <span class="font-medium">{{ $seller->bank_account }}</span></p>
                <p><span class="text-gray-600">Chủ tài khoản:</span> <span class="font-medium">{{ $seller->bank_holder }}</span></p>
            </div>
            <a href="{{ route('seller.register') }}" class="inline-block mt-3 text-sm text-blue-600 hover:underline">Cập nhật thông tin ngân hàng</a>
        </div>

        <div class="mb-6">
            <label class="block mb-2 font-medium">Ghi chú (tùy chọn)</label>
            <textarea name="note" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('note') }}</textarea>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('seller.withdrawals.index') }}" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                Gửi yêu cầu
            </button>
        </div>
    </form>
</div>
@endsection
