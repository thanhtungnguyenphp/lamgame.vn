<x-admin::layouts>
    <x-slot:title>Quản lý rút tiền</x-slot:title>
    <x-slot:content>
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">Quản lý rút tiền</p>
        </div>

        <!-- Stats -->
        <div class="mt-4 flex gap-4">
            <div class="rounded-lg border bg-yellow-50 p-4 dark:bg-gray-800">
                <p class="text-sm text-gray-600">Chờ xử lý</p>
                <p class="text-xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}đ</p>
            </div>
            <div class="rounded-lg border bg-green-50 p-4 dark:bg-gray-800">
                <p class="text-sm text-gray-600">Đã thanh toán</p>
                <p class="text-xl font-bold text-green-600">{{ number_format($stats['completed']) }}đ</p>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-4 rounded-lg border bg-white dark:bg-gray-900">
            <table class="w-full text-sm">
                <thead class="border-b bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Seller</th>
                        <th class="p-3 text-right">Số tiền</th>
                        <th class="p-3 text-left">Ngân hàng</th>
                        <th class="p-3 text-left">Trạng thái</th>
                        <th class="p-3 text-left">Ngày tạo</th>
                        <th class="p-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-3">#{{ $w->id }}</td>
                        <td class="p-3">{{ $w->seller?->shop_name ?? 'N/A' }}</td>
                        <td class="p-3 text-right font-semibold">{{ number_format($w->amount) }}đ</td>
                        <td class="p-3">
                            <div>{{ $w->bank_name }}</div>
                            <div class="text-xs text-gray-500">{{ $w->bank_account }} - {{ $w->bank_holder }}</div>
                        </td>
                        <td class="p-3">
                            @switch($w->status)
                                @case('pending')
                                    <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Chờ duyệt</span>
                                    @break
                                @case('processing')
                                    <span class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-800">Đang xử lý</span>
                                    @break
                                @case('completed')
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-800">Hoàn thành</span>
                                    @break
                                @case('rejected')
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-800">Từ chối</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="p-3 text-xs">{{ $w->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3 text-center">
                            @if($w->status === 'pending')
                                <div class="flex justify-center gap-1">
                                    <form method="POST" action="{{ route('admin.withdrawals.approve', $w->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded bg-blue-500 px-2 py-1 text-xs text-white hover:bg-blue-600">Duyệt</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.withdrawals.reject', $w->id) }}" class="inline"
                                          onsubmit="var note=prompt('Lý do từ chối:'); if(!note) return false; this.querySelector('[name=admin_note]').value=note;">
                                        @csrf
                                        <input type="hidden" name="admin_note" value="">
                                        <button type="submit" class="rounded bg-red-500 px-2 py-1 text-xs text-white hover:bg-red-600">Từ chối</button>
                                    </form>
                                </div>
                            @elseif($w->status === 'processing')
                                <form method="POST" action="{{ route('admin.withdrawals.complete', $w->id) }}" class="inline"
                                      onsubmit="var ref=prompt('Mã giao dịch:'); if(!ref) return false; this.querySelector('[name=transaction_reference]').value=ref;">
                                    @csrf
                                    <input type="hidden" name="transaction_reference" value="">
                                    <button type="submit" class="rounded bg-green-500 px-2 py-1 text-xs text-white hover:bg-green-600">Hoàn thành</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">
                                    {{ $w->admin_note ?? ($w->transaction_id ? 'Ref: '.$w->transaction_id : '—') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">Chưa có yêu cầu rút tiền nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">{{ $withdrawals->links() }}</div>
        </div>
    </x-slot:content>
</x-admin::layouts>
