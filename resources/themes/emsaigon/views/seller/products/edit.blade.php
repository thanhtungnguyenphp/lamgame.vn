@extends('shop::seller.layouts.master')

@section('page_title', 'Sửa sản phẩm - Seller - Làm Game')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container" style="max-width: 900px;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;">✏️ Sửa sản phẩm</h1>
                <p style="color: #6b7280; margin: 0.5rem 0 0 0;">{{ $product->name ?? $product->sku }}</p>
            </div>
            <a href="{{ route('seller.products.index') }}" style="padding: 0.75rem 1.5rem; background: white; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500;">
                ← Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('seller.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.5rem; color: #c00;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $flat = $product->product_flats->first();
            @endphp

            <!-- Basic Info -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    📝 Thông tin cơ bản
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Tên sản phẩm <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $flat->name ?? '') }}" required 
                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                    @error('name')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Mô tả ngắn <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="short_description" required rows="3"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">{{ old('short_description', $flat->short_description ?? '') }}</textarea>
                    @error('short_description')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Mô tả chi tiết <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="description" required rows="8"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">{{ old('description', $flat->description ?? '') }}</textarea>
                    @error('description')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Giá (VNĐ) <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price', $flat->price ?? 0) }}" min="0" required
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                        @error('price')<span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Danh mục <span style="color: #dc2626;">*</span>
                        </label>
                        @php $currentCategory = $product->categories->first()?->id; @endphp
                        <select name="category_id" required
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $currentCategory) == $category->id ? 'selected' : '' }}>
                                    {{ $category->translations->first()->name ?? 'Category #' . $category->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            @if($product->images->count() > 0)
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0;">
                    🖼️ Hình ảnh hiện tại
                </h2>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    @foreach($product->images as $image)
                        <div style="position: relative;">
                            <img src="{{ Storage::url($image->path) }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Files -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    📁 Thêm Files & Hình ảnh mới
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Thêm hình ảnh
                    </label>
                    <input type="file" name="images[]" multiple accept="image/*"
                           style="width: 100%; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 8px;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Tối đa 5MB mỗi ảnh</small>
                </div>

                <div>
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Thêm file source code
                    </label>
                    <input type="file" name="source_files[]" multiple
                           style="width: 100%; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 8px;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Tối đa 100MB mỗi file</small>
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('seller.products.index') }}" 
                   style="padding: 0.75rem 1.5rem; background: white; color: #6b7280; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; font-weight: 500;">
                    Hủy
                </a>
                <button type="submit"
                        style="padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer;">
                    ✓ Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
