@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.products-page {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding: 2rem 0;
}
.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.btn-primary {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44,95,65,0.3);
}
.products-table {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.product-row {
    display: grid;
    grid-template-columns: 80px 1fr 150px 150px 150px 150px;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    align-items: center;
}
.product-row:hover {
    background: #f8f9fa;
}
.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}
.status-draft { background: #6c757d; color: white; }
.status-pending { background: #ffc107; color: #333; }
.status-active { background: #28a745; color: white; }
.status-rejected { background: #dc3545; color: white; }
.actions {
    display: flex;
    gap: 0.5rem;
}
.btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s;
}
.btn-edit {
    background: #007bff;
    color: white;
}
.btn-delete {
    background: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
}
</style>
@endpush

@section('content')
<div class="products-page">
    <div class="container">
        <div class="products-header">
            <h1 style="font-size: 2rem; font-weight: 800; color: #2c5f41;">📦 Quản lý sản phẩm</h1>
            <a href="{{ route('seller.products.create') }}" class="btn-primary">
                ➕ Thêm sản phẩm mới
            </a>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="products-table">
            @if($products->count() > 0)
                <div class="product-row" style="font-weight: 700; background: #f8f9fa; border-radius: 10px;">
                    <div>Hình</div>
                    <div>Tên sản phẩm</div>
                    <div>SKU</div>
                    <div>Giá</div>
                    <div>Trạng thái</div>
                    <div>Hành động</div>
                </div>

                @foreach($products as $product)
                    <div class="product-row">
                        <div>
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->path) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image" style="background: #e9ecef; display: flex; align-items: center; justify-content: center;">📦</div>
                            @endif
                        </div>
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <div style="font-size: 0.85rem; color: #666;">{{ Str::limit($product->short_description, 60) }}</div>
                        </div>
                        <div>{{ $product->sku }}</div>
                        <div style="font-weight: 600; color: #2c5f41;">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                        <div>
                            <span class="status-badge {{ $product->status_badge_class }}">
                                {{ $product->status_label }}
                            </span>
                            @if($product->rejection_reason)
                                <div style="font-size: 0.8rem; color: #dc3545; margin-top: 0.25rem;">
                                    Lý do: {{ Str::limit($product->rejection_reason, 50) }}
                                </div>
                            @endif
                        </div>
                        <div class="actions">
                            <a href="{{ route('seller.products.edit', $product->id) }}" class="btn-sm btn-edit">Sửa</a>
                            
                            @if($product->isDraft())
                                <form action="{{ route('seller.products.submit-review', $product->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-sm" style="background: #007bff; color: white; border: none; cursor: pointer;">
                                        Gửi duyệt
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div style="margin-top: 2rem;">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                    <h3>Chưa có sản phẩm nào</h3>
                    <p>Bắt đầu thêm sản phẩm đầu tiên của bạn!</p>
                    <a href="{{ route('seller.products.create') }}" class="btn-primary" style="display: inline-block; margin-top: 1rem;">
                        ➕ Thêm sản phẩm mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
