@extends('shop::seller.layouts.master')

@section('page_title', $page_title)

@section('content')
<div class="seller-dashboard" style="background: #f8f9fa; min-height: 100vh;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; padding: 2rem 0;">
        <div class="container">
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">
                👋 Xin chào, {{ $seller->shop_name }}!
            </h1>
            <p style="opacity: 0.9;">Quản lý shop và sản phẩm của bạn</p>
        </div>
    </div>

    <div class="container" style="padding: 3rem 0;">
        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <!-- Total Products -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 2rem;">📦</span>
                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                        Sản phẩm
                    </span>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c5f41; margin-bottom: 0.5rem;">
                    {{ $stats['total_products'] }}
                </div>
                <div style="color: #666; font-size: 0.9rem;">Tổng sản phẩm</div>
            </div>

            <!-- Total Sales -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 2rem;">🛒</span>
                    <span style="background: #f3e5f5; color: #7b1fa2; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                        Đơn hàng
                    </span>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c5f41; margin-bottom: 0.5rem;">
                    {{ $stats['total_sales'] }}
                </div>
                <div style="color: #666; font-size: 0.9rem;">Tổng đơn hàng</div>
            </div>

            <!-- Total Revenue -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 2rem;">💰</span>
                    <span style="background: #e8f5e9; color: #388e3c; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                        Doanh thu
                    </span>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c5f41; margin-bottom: 0.5rem;">
                    {{ number_format($stats['total_revenue'], 0, ',', '.') }}đ
                </div>
                <div style="color: #666; font-size: 0.9rem;">Tổng doanh thu</div>
            </div>

            <!-- Rating -->
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 2rem;">⭐</span>
                    <span style="background: #fff3e0; color: #f57c00; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                        Đánh giá
                    </span>
                </div>
                <div style="font-size: 2.5rem; font-weight: 800; color: #2c5f41; margin-bottom: 0.5rem;">
                    {{ number_format($stats['rating_avg'], 1) }}
                </div>
                <div style="color: #666; font-size: 0.9rem;">Điểm trung bình</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 3rem;">
            <h2 style="color: #2c5f41; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 700;">
                🚀 Hành động nhanh
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('seller.products.create') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">➕</span>
                    <div>
                        <div style="font-weight: 600;">Thêm sản phẩm</div>
                        <div style="font-size: 0.9rem; color: #666;">Upload source game mới</div>
                    </div>
                </a>
                <a href="{{ route('seller.products.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">📦</span>
                    <div>
                        <div style="font-weight: 600;">Quản lý sản phẩm</div>
                        <div style="font-size: 0.9rem; color: #666;">Xem & chỉnh sửa</div>
                    </div>
                </a>
                <a href="{{ route('seller.orders.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">📊</span>
                    <div>
                        <div style="font-weight: 600;">Đơn hàng</div>
                        <div style="font-size: 0.9rem; color: #666;">Theo dõi đơn hàng</div>
                    </div>
                </a>
                <a href="{{ route('seller.withdrawals.index') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">💳</span>
                    <div>
                        <div style="font-weight: 600;">Rút tiền</div>
                        <div style="font-size: 0.9rem; color: #666;">{{ number_format($stats['available_balance'], 0, ',', '.') }}đ</div>
                    </div>
                </a>
                <a href="{{ route('seller.register') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">⚙️</span>
                    <div>
                        <div style="font-weight: 600;">Cài đặt Shop</div>
                        <div style="font-size: 0.9rem; color: #666;">Thông tin & ngân hàng</div>
                    </div>
                </a>
                <a href="{{ route('seller.analytics') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: #333; transition: all 0.3s; border: 2px solid transparent;">
                    <span style="font-size: 2rem;">📈</span>
                    <div>
                        <div style="font-weight: 600;">Phân tích</div>
                        <div style="font-size: 0.9rem; color: #666;">Biểu đồ & báo cáo</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Coming Soon -->
        <div style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); padding: 3rem; border-radius: 15px; text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🚧</div>
            <h3 style="color: #2c5f41; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">
                Tính năng đang phát triển
            </h3>
            <p style="color: #666; font-size: 1.1rem;">
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
