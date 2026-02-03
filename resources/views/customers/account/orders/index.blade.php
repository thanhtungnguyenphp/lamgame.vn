<x-layouts.account title="Đơn hàng của tôi">
    <div class="orders-page">
        <div class="page-header">
            <h1>Đơn hàng của tôi</h1>
            <p>Lịch sử mua hàng và trạng thái đơn hàng</p>
        </div>

        @php
            $orders = auth('customer')->user()->orders()->orderBy('created_at', 'desc')->get();
        @endphp

        @if($orders->count() > 0)
            <div class="orders-list">
                @foreach($orders as $order)
                    @php
                        $hasDownloadable = $order->items->contains(fn($item) => $item->type == 'downloadable');
                    @endphp
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <span class="order-id">#{{ $order->increment_id }}</span>
                                <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <span class="status status-{{ $order->status }}">
                                @switch($order->status)
                                    @case('pending') Chờ xử lý @break
                                    @case('processing') Đang xử lý @break
                                    @case('completed') Hoàn thành @break
                                    @case('canceled') Đã hủy @break
                                    @default {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="order-items">
                            @foreach($order->items->take(3) as $item)
                                <div class="order-item">
                                    <span class="item-name">{{ Str::limit($item->name, 50) }}</span>
                                    <span class="item-qty">x{{ $item->qty_ordered }}</span>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="more-items">+{{ $order->items->count() - 3 }} sản phẩm khác</div>
                            @endif
                        </div>

                        <div class="order-footer">
                            <div class="order-total">
                                <span class="label">Tổng tiền:</span>
                                <span class="amount">{{ core()->formatPrice($order->grand_total, $order->order_currency_code) }}</span>
                            </div>
                            <div class="order-actions">
                                @if($hasDownloadable && in_array($order->status, ['completed', 'processing']))
                                    <a href="{{ route('shop.customers.account.downloadable_products.index') }}" class="btn-download">
                                        📥 Tải về
                                    </a>
                                @endif
                                <a href="{{ route('shop.customers.account.orders.view', $order->id) }}" class="btn-view">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg width="80" height="80" fill="#d1d5db" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                </svg>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa mua sản phẩm nào. Khám phá ngay!</p>
                <a href="{{ route('lamgame.source-game') }}" class="btn-primary">Khám phá Source Game</a>
            </div>
        @endif
    </div>

    <style>
        .orders-page .page-header { margin-bottom: 2rem; }
        .orders-page .page-header h1 { font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; }
        .orders-page .page-header p { color: #6b7280; }
        
        .orders-list { display: flex; flex-direction: column; gap: 1rem; }
        
        .order-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        
        .order-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: white; border-bottom: 1px solid #e5e7eb; }
        .order-info { display: flex; align-items: center; gap: 1rem; }
        .order-id { font-weight: 600; color: #6a4c93; }
        .order-date { font-size: 0.85rem; color: #6b7280; }
        
        .status { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-canceled { background: #fee2e2; color: #991b1b; }
        
        .order-items { padding: 1rem 1.5rem; }
        .order-item { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb; }
        .order-item:last-child { border-bottom: none; }
        .item-name { color: #374151; }
        .item-qty { color: #6b7280; font-size: 0.9rem; }
        .more-items { font-size: 0.85rem; color: #6b7280; padding-top: 0.5rem; }
        
        .order-footer { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: white; border-top: 1px solid #e5e7eb; }
        .order-total .label { color: #6b7280; margin-right: 0.5rem; }
        .order-total .amount { font-weight: 600; color: #1f2937; font-size: 1.1rem; }
        
        .order-actions { display: flex; gap: 0.75rem; }
        .btn-download { padding: 8px 16px; background: #d1fae5; color: #065f46; border-radius: 6px; text-decoration: none; font-size: 0.9rem; }
        .btn-view { padding: 8px 16px; background: #6a4c93; color: white; border-radius: 6px; text-decoration: none; font-size: 0.9rem; }
        
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state svg { margin-bottom: 1.5rem; }
        .empty-state h3 { font-size: 1.25rem; color: #374151; margin-bottom: 0.5rem; }
        .empty-state p { color: #6b7280; margin-bottom: 1.5rem; }
        .btn-primary { display: inline-block; padding: 12px 24px; background: #6a4c93; color: white; border-radius: 8px; text-decoration: none; }
        
        @media (max-width: 640px) {
            .order-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .order-footer { flex-direction: column; gap: 1rem; align-items: flex-start; }
            .order-actions { width: 100%; }
            .btn-download, .btn-view { flex: 1; text-align: center; }
        }
    </style>
</x-layouts.account>
