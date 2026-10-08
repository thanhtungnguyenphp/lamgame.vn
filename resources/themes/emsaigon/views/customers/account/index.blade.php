<x-layouts.account>
    <x-slot:title>
        Tài khoản của tôi
    </x-slot>

    @php
        $cid = auth()->guard('customer')->id();
        $customer = auth()->guard('customer')->user();

        $ordersCount = \Webkul\Sales\Models\Order::where('customer_id', $cid)->count();
        $completedOrders = \Webkul\Sales\Models\Order::where('customer_id', $cid)
            ->whereIn('status', ['completed', 'processing'])->get();
        $totalSpent = $completedOrders->sum('grand_total');
        $licenseCount = \App\Models\LicenseKey::where('customer_id', $cid)->count();
        $downloadCount = \Webkul\Sales\Models\DownloadableLinkPurchased::where('customer_id', $cid)->count();
        $recentOrders = \Webkul\Sales\Models\Order::where('customer_id', $cid)->orderByDesc('id')->limit(5)->get();

        $statusLabels = [
            'completed' => ['Hoàn thành', '#dcfce7', '#166534'],
            'processing' => ['Đang xử lý', '#dbeafe', '#1e40af'],
            'pending' => ['Chờ xử lý', '#fef9c3', '#854d0e'],
            'canceled' => ['Đã hủy', '#fee2e2', '#991b1b'],
            'closed' => ['Đã đóng', '#f3f4f6', '#374151'],
        ];
    @endphp

    <style>
        .dash-hello { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.25rem; }
        .dash-sub { color: #7A8599; margin-bottom: 1.5rem; }
        .dash-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .dash-stat { background: rgba(255,255,255,.03); border: 1px solid rgba(124,92,255,.15); border-radius: 14px; padding: 1.25rem; }
        .dash-stat__icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .dash-stat__num { font-size: 1.6rem; font-weight: 800; color: #F5F7FA; }
        .dash-stat__label { color: #7A8599; font-size: 0.85rem; }
        .dash-section-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 1rem 0; color: #F5F7FA; }
        .dash-table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
        .dash-table th { text-align: left; padding: 10px; border-bottom: 2px solid rgba(124,92,255,.15); color: #B7C0D1; font-weight: 600; }
        .dash-table td { padding: 10px; border-bottom: 1px solid rgba(124,92,255,.1); color: #B7C0D1; }
        .dash-badge { padding: 3px 10px; border-radius: 12px; font-size: 0.78rem; }
        .dash-empty { text-align: center; padding: 2rem; color: #7A8599; border: 1px dashed rgba(124,92,255,.2); border-radius: 12px; }
        .dash-link { color: #00D1FF; font-weight: 600; text-decoration: none; }
        .dash-shortcuts { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; margin-top: 1.5rem; }
        .dash-shortcut { display: flex; align-items: center; gap: 0.6rem; padding: 0.9rem 1rem; border: 1px solid rgba(124,92,255,.15); border-radius: 12px; color: #F5F7FA; text-decoration: none; transition: all .18s; }
        .dash-shortcut:hover { background: rgba(124,92,255,.1); border-color: rgba(124,92,255,.4); }
    </style>

    <div class="dash-hello">👋 {{ __('lamgame.account.hello') }}, {{ trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: __('lamgame.account.guest') }}!</div>
    <p class="dash-sub">{{ __('lamgame.account.dash_sub') }}</p>

    <!-- Thống kê nhanh -->
    <div class="dash-stats">
        <div class="dash-stat">
            <div class="dash-stat__icon">📦</div>
            <div class="dash-stat__num">{{ number_format($ordersCount) }}</div>
            <div class="dash-stat__label">{{ __('lamgame.account.stat_orders') }}</div>
        </div>
        <div class="dash-stat">
            <div class="dash-stat__icon">⬇️</div>
            <div class="dash-stat__num">{{ number_format($downloadCount) }}</div>
            <div class="dash-stat__label">{{ __('lamgame.account.stat_downloads') }}</div>
        </div>
        <div class="dash-stat">
            <div class="dash-stat__icon">🔑</div>
            <div class="dash-stat__num">{{ number_format($licenseCount) }}</div>
            <div class="dash-stat__label">{{ __('lamgame.account.stat_licenses') }}</div>
        </div>
        <div class="dash-stat">
            <div class="dash-stat__icon">💰</div>
            <div class="dash-stat__num">${{ number_format($totalSpent, 2) }}</div>
            <div class="dash-stat__label">{{ __('lamgame.account.stat_spent') }}</div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <h2 class="dash-section-title">{{ __('lamgame.account.recent_orders') }}</h2>
    @if($recentOrders->isEmpty())
        <div class="dash-empty">
            <p style="margin-bottom:0.75rem">{{ __('lamgame.account.no_orders') }}</p>
            <a href="{{ route('lamgame.source-game') }}" class="dash-link">{{ __('lamgame.account.explore_source') }}</a>
        </div>
    @else
        <div style="overflow-x:auto">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>{{ __('lamgame.account.col_order') }}</th>
                        <th>{{ __('lamgame.account.col_date') }}</th>
                        <th>{{ __('lamgame.account.col_status') }}</th>
                        <th>{{ __('lamgame.account.col_total') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $o)
                        @php $st = $statusLabels[$o->status] ?? [ucfirst($o->status), '#f3f4f6', '#374151']; @endphp
                        <tr>
                            <td style="font-weight:600;color:#F5F7FA">#{{ $o->increment_id }}</td>
                            <td>{{ $o->created_at?->format('d/m/Y') }}</td>
                            <td><span class="dash-badge" style="background:{{ $st[1] }};color:{{ $st[2] }}">{{ $st[0] }}</span></td>
                            <td style="font-weight:700;color:#00D1FF">${{ number_format($o->grand_total, 2) }}</td>
                            <td><a href="{{ route('shop.customers.account.orders.view', $o->id) }}" class="dash-link">{{ __('lamgame.account.view') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">
            <a href="{{ route('shop.customers.account.orders.index') }}" class="dash-link">{{ __('lamgame.account.view_all_orders') }}</a>
        </div>
    @endif

    <!-- Shortcut -->
    <div class="dash-shortcuts">
        <a href="{{ route('shop.customers.account.profile.index') }}" class="dash-shortcut"><span>👤</span> {{ __('lamgame.account.profile') }}</a>
        <a href="{{ route('shop.customers.account.downloadable_products.index') }}" class="dash-shortcut"><span>⬇️</span> {{ __('lamgame.account.downloads') }}</a>
        <a href="{{ route('lamgame.my-licenses') }}" class="dash-shortcut"><span>🔑</span> {{ __('lamgame.account.licenses') }}</a>
        <a href="{{ route('lamgame.source-game') }}" class="dash-shortcut"><span>🛒</span> {{ __('lamgame.cart.continue') }}</a>
    </div>
</x-layouts.account>
