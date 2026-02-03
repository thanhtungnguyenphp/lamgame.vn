<x-layouts.account title="Sản phẩm tải về">
    <div class="downloads-page">
        <div class="page-header">
            <h1>Sản phẩm tải về</h1>
            <p>Danh sách các source code bạn đã mua</p>
        </div>

        @php
            $downloads = DB::table('downloadable_link_purchased')
                ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
                ->leftJoin('invoices', 'downloadable_link_purchased.order_id', '=', 'invoices.order_id')
                ->select(
                    'downloadable_link_purchased.*',
                    'orders.increment_id',
                    'invoices.state as invoice_state',
                    DB::raw('(downloadable_link_purchased.download_bought - downloadable_link_purchased.download_canceled - downloadable_link_purchased.download_used) as remaining_downloads')
                )
                ->where('downloadable_link_purchased.customer_id', auth('customer')->id())
                ->orderBy('downloadable_link_purchased.created_at', 'desc')
                ->get();
        @endphp

        @if($downloads->count() > 0)
            <div class="downloads-list">
                @foreach($downloads as $item)
                    <div class="download-card">
                        <div class="download-info">
                            <div class="download-header">
                                <span class="order-id">Đơn hàng #{{ $item->increment_id }}</span>
                                <span class="status status-{{ $item->status }}">
                                    @if($item->status == 'available') Sẵn sàng
                                    @elseif($item->status == 'pending') Chờ xử lý
                                    @else Hết hạn
                                    @endif
                                </span>
                            </div>
                            <h3 class="product-name">{{ $item->product_name }}</h3>
                            <div class="download-meta">
                                <span>📅 {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                                <span>📥 Còn lại: {{ $item->download_bought ? $item->remaining_downloads : 'Không giới hạn' }}</span>
                            </div>
                        </div>
                        <div class="download-action">
                            @if($item->status == 'available' && $item->invoice_state == 'paid')
                                <a href="{{ route('shop.customers.account.downloadable_products.download', $item->id) }}" class="btn-download">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Tải xuống
                                </a>
                            @elseif($item->status == 'pending')
                                <span class="btn-disabled">Chờ thanh toán</span>
                            @else
                                <span class="btn-disabled">Không khả dụng</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg width="80" height="80" fill="#d1d5db" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                <h3>Chưa có sản phẩm nào</h3>
                <p>Bạn chưa mua source code nào. Khám phá ngay!</p>
                <a href="{{ route('lamgame.source-game') }}" class="btn-primary">Khám phá Source Game</a>
            </div>
        @endif
    </div>

    <style>
        .downloads-page .page-header { margin-bottom: 2rem; }
        .downloads-page .page-header h1 { font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; }
        .downloads-page .page-header p { color: #6b7280; }
        
        .downloads-list { display: flex; flex-direction: column; gap: 1rem; }
        
        .download-card {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.5rem; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        .download-card:hover { border-color: #6a4c93; box-shadow: 0 4px 12px rgba(106,76,147,0.1); }
        
        .download-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; }
        .order-id { font-size: 0.85rem; color: #6b7280; }
        
        .status { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
        .status-available { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-expired { background: #fee2e2; color: #991b1b; }
        
        .product-name { font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; }
        
        .download-meta { display: flex; gap: 1.5rem; font-size: 0.85rem; color: #6b7280; }
        
        .btn-download {
            display: flex; align-items: center; gap: 8px;
            padding: 12px 24px; background: #6a4c93; color: white;
            border-radius: 8px; text-decoration: none; font-weight: 500;
            transition: all 0.2s;
        }
        .btn-download:hover { background: #5a3c83; transform: translateY(-2px); }
        
        .btn-disabled { padding: 12px 24px; background: #e5e7eb; color: #9ca3af; border-radius: 8px; }
        
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state svg { margin-bottom: 1.5rem; }
        .empty-state h3 { font-size: 1.25rem; color: #374151; margin-bottom: 0.5rem; }
        .empty-state p { color: #6b7280; margin-bottom: 1.5rem; }
        .btn-primary { display: inline-block; padding: 12px 24px; background: #6a4c93; color: white; border-radius: 8px; text-decoration: none; }
        
        @media (max-width: 640px) {
            .download-card { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .download-action { width: 100%; }
            .btn-download, .btn-disabled { width: 100%; justify-content: center; text-align: center; }
        }
    </style>
</x-layouts.account>
