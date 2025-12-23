@extends('shop::seller.layouts.master')

@section('page_title', 'Quản lý sản phẩm - Seller - Làm Game')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;">📦 Quản lý sản phẩm</h1>
                <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Danh sách source game của bạn</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('seller.dashboard') }}" style="padding: 0.75rem 1.5rem; background: white; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500;">
                    ← Dashboard
                </a>
                <a href="{{ route('seller.products.create') }}" style="padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    ➕ Thêm sản phẩm
                </a>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                @foreach($products as $product)
                    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <!-- Image -->
                        <div style="position: relative; height: 200px; background: #e5e7eb;">
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->path) }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;"
                                     alt="{{ $product->flat->name ?? 'Product' }}">
                            @else
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 4rem;">
                                    🎮
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            @php
                                $statusColors = [
                                    0 => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Chờ duyệt'],
                                    1 => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Đã duyệt'],
                                ];
                                $status = $statusColors[$product->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => 'Unknown'];
                            @endphp
                            <span style="position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.25rem 0.75rem; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div style="padding: 1.5rem;">
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">
                                {{ $product->flat->name ?? 'Untitled' }}
                            </h3>
                            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 1rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $product->flat->short_description ?? '' }}
                            </p>

                            <!-- Price & Stats -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                                <div style="font-size: 1.25rem; font-weight: 700; color: #2c5f41;">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </div>
                                <div style="font-size: 0.875rem; color: #6b7280;">
                                    SKU: {{ $product->sku }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('seller.products.edit', $product->id) }}" 
                                   style="flex: 1; padding: 0.5rem; background: #eff6ff; color: #2563eb; border-radius: 6px; text-align: center; text-decoration: none; font-size: 0.875rem; font-weight: 500;">
                                    ✏️ Sửa
                                </a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product->id) }}" 
                                      style="flex: 1;" 
                                      onsubmit="return confirm('Xác nhận xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="width: 100%; padding: 0.5rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div style="background: white; padding: 4rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                <h3 style="color: #374151; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Chưa có sản phẩm nào</h3>
                <p style="color: #6b7280; margin-bottom: 2rem;">Bắt đầu bán source game của bạn ngay hôm nay!</p>
                <a href="{{ route('seller.products.create') }}" 
                   style="display: inline-block; padding: 0.875rem 1.75rem; background: #2c5f41; color: white; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    ➕ Thêm sản phẩm đầu tiên
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
