@extends('shop::seller.layouts.master')

@section('page_title', $page_title)

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;">📦 Đơn hàng</h1>
                <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Quản lý đơn hàng của bạn</p>
            </div>
            <a href="{{ route('seller.dashboard') }}" style="padding: 0.75rem 1.5rem; background: white; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500;">
                ← Quay lại Dashboard
            </a>
        </div>

        <!-- Orders Table -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            @if($orders->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <tr>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151;">Mã đơn</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151;">Sản phẩm</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151;">Khách hàng</th>
                                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Số lượng</th>
                                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Tổng tiền</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;">Trạng thái</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151;">Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1rem;">
                                        <a href="{{ route('seller.orders.show', $order->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td style="padding: 1rem;">{{ $order->product_name }}</td>
                                    <td style="padding: 1rem;">{{ $order->customer_email }}</td>
                                    <td style="padding: 1rem; text-align: right;">{{ $order->qty }}</td>
                                    <td style="padding: 1rem; text-align: right; font-weight: 600; color: #2c5f41;">
                                        {{ number_format($order->total, 0, ',', '.') }}đ
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                                'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'completed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                                'canceled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                            ];
                                            $color = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                        @endphp
                                        <span style="display: inline-block; padding: 0.25rem 0.75rem; background: {{ $color['bg'] }}; color: {{ $color['text'] }}; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                    {{ $orders->links() }}
                </div>
            @else
                <div style="padding: 4rem; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                    <h3 style="color: #374151; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Chưa có đơn hàng nào</h3>
                    <p style="color: #6b7280;">Đơn hàng sẽ xuất hiện ở đây khi có khách mua sản phẩm của bạn</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
