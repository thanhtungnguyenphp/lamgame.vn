@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.seller-dashboard {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
}
.dashboard-header {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    color: white;
    padding: 2rem 0;
}
.dashboard-header h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}
.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.stat-card h3 {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}
.stat-card .value {
    font-size: 2rem;
    font-weight: 800;
    color: #2c5f41;
}
</style>
@endpush

@section('content')
<div class="seller-dashboard">
    <div class="dashboard-header">
        <div class="container">
            <h1>👋 Xin chào, {{ $seller->shop_name }}!</h1>
            <p>Quản lý shop và sản phẩm của bạn</p>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div>
            <!-- Total Products -->
            <div>
                <div>
                    <span>📦</span>
                    <span>
                        Sản phẩm
                    </span>
                </div>
                <div>
                    {{ $stats['total_products'] }}
                </div>
                <div>Tổng sản phẩm</div>
            </div>

            <!-- Total Sales -->
            <div>
                <div>
                    <span>🛒</span>
                    <span>
                        Đơn hàng
                    </span>
                </div>
                <div>
                    {{ $stats['total_sales'] }}
                </div>
                <div>Tổng đơn hàng</div>
            </div>

            <!-- Total Revenue -->
            <div>
                <div>
                    <span>💰</span>
                    <span>
                        Doanh thu
                    </span>
                </div>
                <div>
                    {{ number_format($stats['total_revenue'], 0, ',', '.') }}đ
                </div>
                <div>Tổng doanh thu</div>
            </div>

            <!-- Rating -->
            <div>
                <div>
                    <span>⭐</span>
                    <span>
                        Đánh giá
                    </span>
                </div>
                <div>
                    {{ number_format($stats['rating_avg'], 1) }}
                </div>
                <div>Điểm trung bình</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <h2>
                🚀 Hành động nhanh
            </h2>
            <div>
                <a href="#">
                    <span>➕</span>
                    <div>
                        <div>Thêm sản phẩm</div>
                        <div>Upload source game mới</div>
                    </div>
                </a>
                <a href="#">
                    <span>📊</span>
                    <div>
                        <div>Xem báo cáo</div>
                        <div>Thống kê chi tiết</div>
                    </div>
                </a>
                <a href="#">
                    <span>💳</span>
                    <div>
                        <div>Rút tiền</div>
                        <div>Yêu cầu thanh toán</div>
                    </div>
                </a>
                <a href="#">
                    <span>⚙️</span>
                    <div>
                        <div>Cài đặt</div>
                        <div>Quản lý shop</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Coming Soon -->
        <div>
            <div>🚧</div>
            <h3>
                Tính năng đang phát triển
            </h3>
            <p>
                Dashboard đầy đủ với biểu đồ, quản lý sản phẩm, và nhiều tính năng khác sẽ sớm ra mắt!
            </p>
        </div>
    </div>
</div>

<style>
.seller-dashboard a:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44, 95, 65, 0.2);
    border-color: #2c5f41 !important;
}
</style>
@endsection
