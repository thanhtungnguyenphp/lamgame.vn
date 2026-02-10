@extends('shop::seller.layouts.master')

@section('page_title', 'Quản lý sản phẩm - Seller - Làm Game')

@push('styles')
<style>
    .sp-page { background: #f8f9fa; min-height: 100vh; padding: 2rem 0; }
    .sp-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .sp-title { font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0; }
    .sp-subtitle { color: #6b7280; margin: 0.5rem 0 0 0; }
    .sp-actions { display: flex; gap: 1rem; }
    .sp-btn { padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-block; }
    .sp-btn--outline { background: white; border: 1px solid #d1d5db; color: #374151; }
    .sp-btn--primary { background: #2c5f41; color: white; }
    .sp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .sp-card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
    .sp-card__img { position: relative; height: 200px; background: #e5e7eb; }
    .sp-card__img img { width: 100%; height: 100%; object-fit: cover; }
    .sp-card__placeholder { display: flex; align-items: center; justify-content: center; height: 100%; font-size: 4rem; }
    .sp-badge { position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    .sp-badge--pending { background: #fef3c7; color: #92400e; }
    .sp-badge--active { background: #d1fae5; color: #065f46; }
    .sp-card__body { padding: 1.5rem; }
    .sp-card__name { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0; }
    .sp-card__desc { color: #6b7280; font-size: 0.875rem; margin: 0 0 1rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .sp-card__meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; }
    .sp-price { font-size: 1.25rem; font-weight: 700; color: #2c5f41; }
    .sp-price--free { color: #059669; }
    .sp-sku { font-size: 0.875rem; color: #6b7280; }
    .sp-card__actions { display: flex; gap: 0.5rem; }
    .sp-btn-edit { flex: 1; padding: 0.5rem; background: #eff6ff; color: #2563eb; border-radius: 6px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
    .sp-btn-delete { width: 100%; padding: 0.5rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
    .sp-pagination { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .sp-empty { background: white; padding: 4rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center; }
    .sp-empty__icon { font-size: 4rem; margin-bottom: 1rem; }
    .sp-empty__title { color: #374151; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .sp-empty__text { color: #6b7280; margin-bottom: 2rem; }

    @media (max-width: 640px) {
        .sp-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .sp-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="sp-page">
    <div class="container">
        <div class="sp-header">
            <div>
                <h1 class="sp-title">📦 Quản lý sản phẩm</h1>
                <p class="sp-subtitle">Danh sách source game của bạn</p>
            </div>
            <div class="sp-actions">
                <a href="{{ route('seller.dashboard') }}" class="sp-btn sp-btn--outline">← Dashboard</a>
                <a href="{{ route('seller.products.create') }}" class="sp-btn sp-btn--primary">➕ Thêm sản phẩm</a>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="sp-grid">
                @foreach($products as $product)
                    @php $flat = $product->flat; @endphp
                    <div class="sp-card">
                        <div class="sp-card__img">
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->path) }}" alt="{{ optional($flat)->name ?? 'Product' }}">
                            @else
                                <div class="sp-card__placeholder">🎮</div>
                            @endif

                            @if(optional($flat)->status)
                                <span class="sp-badge sp-badge--active">Đã duyệt</span>
                            @else
                                <span class="sp-badge sp-badge--pending">Chờ duyệt</span>
                            @endif
                        </div>

                        <div class="sp-card__body">
                            <h3 class="sp-card__name">{{ optional($flat)->name ?? 'Untitled' }}</h3>
                            <p class="sp-card__desc">{{ optional($flat)->short_description ?? '' }}</p>

                            <div class="sp-card__meta">
                                <div class="sp-price {{ (optional($flat)->price ?? 0) == 0 ? 'sp-price--free' : '' }}">
                                    @if((optional($flat)->price ?? 0) > 0)
                                        {{ number_format($flat->price, 0, ',', '.') }}đ
                                    @else
                                        Miễn phí
                                    @endif
                                </div>
                                <div class="sp-sku">SKU: {{ $product->sku }}</div>
                            </div>

                            <div class="sp-card__actions">
                                <a href="{{ route('seller.products.edit', $product->id) }}" class="sp-btn-edit">✏️ Sửa</a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product->id) }}" style="flex:1;" onsubmit="return confirm('Xác nhận xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="sp-btn-delete">🗑️ Xóa</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sp-pagination">
                {{ $products->links() }}
            </div>
        @else
            <div class="sp-empty">
                <div class="sp-empty__icon">📦</div>
                <h3 class="sp-empty__title">Chưa có sản phẩm nào</h3>
                <p class="sp-empty__text">Bắt đầu bán source game của bạn ngay hôm nay!</p>
                <a href="{{ route('seller.products.create') }}" class="sp-btn sp-btn--primary">➕ Thêm sản phẩm đầu tiên</a>
            </div>
        @endif
    </div>
</div>
@endsection
