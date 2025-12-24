@extends('shop::seller.layouts.master')

@section('page_title', $page_title)

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;">📈 Phân tích & Báo cáo</h1>
                <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Thống kê chi tiết về doanh thu và sản phẩm</p>
            </div>
            <a href="{{ route('seller.dashboard') }}" style="padding: 0.75rem 1.5rem; background: white; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500;">
                ← Quay lại Dashboard
            </a>
        </div>

        <!-- Monthly Revenue Chart -->
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">💰 Doanh thu 12 tháng gần đây</h2>
            <canvas id="revenueChart" style="max-height: 400px;"></canvas>
        </div>

        <!-- Top Products & Category Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Top Products -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">🏆 Top 10 Sản phẩm bán chạy</h2>
                <div style="max-height: 500px; overflow-y: auto;">
                    @foreach($topProducts as $index => $product)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: {{ $index < 3 ? '#fbbf24' : '#e5e7eb' }}; color: {{ $index < 3 ? 'white' : '#6b7280' }}; border-radius: 50%; font-weight: 700; font-size: 0.875rem;">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $product->name }}</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $product->sales_count }} lượt bán</div>
                                </div>
                            </div>
                            <div style="text-align: right; font-weight: 600; color: #2c5f41;">
                                {{ number_format($product->revenue, 0, ',', '.') }}đ
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Category Stats -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">📊 Thống kê theo danh mục</h2>
                <canvas id="categoryChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
            borderColor: '#2c5f41',
            backgroundColor: 'rgba(44, 95, 65, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Số đơn hàng',
            data: {!! json_encode($monthlyRevenue->pluck('orders_count')) !!},
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.datasetIndex === 0) {
                            label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                        } else {
                            label += context.parsed.y + ' đơn';
                        }
                        return label;
                    }
                }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryStats->pluck('category_name')) !!},
        datasets: [{
            data: {!! json_encode($categoryStats->pluck('revenue')) !!},
            backgroundColor: [
                '#2c5f41',
                '#f59e0b',
                '#3b82f6',
                '#ef4444',
                '#8b5cf6',
                '#ec4899',
                '#14b8a6',
                '#f97316'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = new Intl.NumberFormat('vi-VN').format(context.parsed) + 'đ';
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1) + '%';
                        return label + ': ' + value + ' (' + percentage + ')';
                    }
                }
            }
        }
    }
});
</script>
@endsection
