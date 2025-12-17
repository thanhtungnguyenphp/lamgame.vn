@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.seller-dashboard {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-bottom: 3rem;
}
.dashboard-header {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 3rem;
}
.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.dashboard-header h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}
.dashboard-header p {
    opacity: 0.9;
    margin: 0;
}
.btn-edit {
    background: white;
    color: #2c5f41;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-block;
}
.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,255,255,0.3);
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
    transition: all 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.stat-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}
.stat-icon {
    font-size: 1.5rem;
}
.stat-title {
    color: #666;
    font-size: 0.9rem;
    font-weight: 600;
}
.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2c5f41;
    margin-bottom: 0.5rem;
}
.stat-label {
    color: #999;
    font-size: 0.85rem;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c5f41;
    margin-bottom: 1.5rem;
}
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}
.action-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s;
}
.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(44,95,65,0.2);
    border: 2px solid #2c5f41;
}
.action-icon {
    font-size: 3rem;
    flex-shrink: 0;
}
.action-content h3 {
    color: #2c5f41;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.action-content p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}
.coming-soon {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
}
.coming-soon-icon {
    font-size: 5rem;
    margin-bottom: 1rem;
}
.coming-soon h3 {
    color: #2c5f41;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}
.coming-soon p {
    color: #666;
    font-size: 1rem;
    max-width: 600px;
    margin: 0 auto;
}
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    .stats-grid,
    .actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="seller-dashboard">
    <div class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div>
                    <h1>👋 Xin chào, {{ $seller->shop_name }}!</h1>
                    <p>Quản lý shop và sản phẩm của bạn</p>
                </div>
                <a href="{{ route('seller.register') }}" class="btn-edit">
                    ✏️ Chỉnh sửa thông tin
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <!-- Total Products -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon">📦</span>
                    <span class="stat-title">Sản phẩm</span>
                </div>
                <div class="stat-value">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Tổng sản phẩm</div>
            </div>

            <!-- Total Sales -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon">🛒</span>
                    <span class="stat-title">Đơn hàng</span>
                </div>
                <div class="stat-value">{{ $stats['total_sales'] }}</div>
                <div class="stat-label">Tổng đơn hàng</div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon">💰</span>
                    <span class="stat-title">Doanh thu</span>
                </div>
                <div class="stat-value">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                <div class="stat-label">Tổng doanh thu</div>
            </div>

            <!-- Rating -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon">⭐</span>
                    <span class="stat-title">Đánh giá</span>
                </div>
                <div class="stat-value">{{ number_format($stats['rating_avg'], 1) }}</div>
                <div class="stat-label">Điểm trung bình</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="section-title">🚀 Hành động nhanh</h2>
        <div class="actions-grid">
            <a href="{{ route('seller.products.create') }}" class="action-card">
                <span class="action-icon">➕</span>
                <div class="action-content">
                    <h3>Thêm sản phẩm</h3>
                    <p>Upload source game mới</p>
                </div>
            </a>

            <a href="{{ route('seller.products.index') }}" class="action-card">
                <span class="action-icon">📦</span>
                <div class="action-content">
                    <h3>Quản lý sản phẩm</h3>
                    <p>Xem và chỉnh sửa sản phẩm</p>
                </div>
            </a>

            <a href="#" class="action-card" style="opacity: 0.6; pointer-events: none;">
                <span class="action-icon">📊</span>
                <div class="action-content">
                    <h3>Xem báo cáo</h3>
                    <p>Thống kê chi tiết (Sắp ra mắt)</p>
                </div>
            </a>

            <a href="#" class="action-card" style="opacity: 0.6; pointer-events: none;">
                <span class="action-icon">💳</span>
                <div class="action-content">
                    <h3>Rút tiền</h3>
                    <p>Yêu cầu thanh toán (Sắp ra mắt)</p>
                </div>
            </a>
        </div>

        <!-- Coming Soon -->
        <div class="coming-soon">
            <div class="coming-soon-icon">🚧</div>
            <h3>Tính năng đang phát triển</h3>
            <p>Dashboard đầy đủ với biểu đồ, quản lý đơn hàng, theo dõi doanh thu, và nhiều tính năng khác sẽ sớm ra mắt!</p>
        </div>
    </div>
</div>
@endsection
