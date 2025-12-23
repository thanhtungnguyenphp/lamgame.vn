@extends('shop::seller.layouts.master')

@section('page_title', 'Thêm sản phẩm mới - Seller - Làm Game')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container" style="max-width: 900px;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0;">➕ Thêm sản phẩm mới</h1>
                <p style="color: #6b7280; margin: 0.5rem 0 0 0;">Upload source game của bạn</p>
            </div>
            <a href="{{ route('seller.products.index') }}" style="padding: 0.75rem 1.5rem; background: white; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500;">
                ← Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.5rem; color: #c00;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Info -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    📝 Thông tin cơ bản
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Tên sản phẩm <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
                           placeholder="VD: Game Flappy Bird Unity">
                    @error('name')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Mô tả ngắn <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="short_description" required rows="3"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
                              placeholder="Mô tả ngắn gọn về sản phẩm (tối đa 500 ký tự)">{{ old('short_description') }}</textarea>
                    @error('short_description')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Mô tả chi tiết <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="description" required rows="8"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"
                              placeholder="Mô tả chi tiết về tính năng, cách sử dụng, yêu cầu hệ thống...">{{ old('description') }}</textarea>
                    @error('description')<span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Giá (VNĐ) <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" required
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                        <small style="color: #6b7280; font-size: 0.875rem;">Nhập 0 nếu miễn phí</small>
                        @error('price')<span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                            Danh mục <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="category_id" required
                                style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->translations->first()->name ?? $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Technical Info -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    ⚙️ Thông tin kỹ thuật
                </h2>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Game Engine</label>
                        <input type="text" name="game_engine" value="{{ old('game_engine') }}"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px;"
                               placeholder="VD: Unity, Unreal">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Ngôn ngữ lập trình</label>
                        <input type="text" name="programming_language" value="{{ old('programming_language') }}"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px;"
                               placeholder="VD: C#, C++">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Phiên bản</label>
                        <input type="text" name="version" value="{{ old('version') }}"
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px;"
                               placeholder="VD: 1.0.0">
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Yêu cầu hệ thống</label>
                    <textarea name="requirements" rows="4"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px;"
                              placeholder="VD: Unity 2021.3+, Windows 10+, 4GB RAM">{{ old('requirements') }}</textarea>
                </div>
            </div>

            <!-- Files -->
            <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    📁 Files & Hình ảnh
                </h2>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        Hình ảnh sản phẩm
                    </label>
                    <input type="file" name="images[]" multiple accept="image/*"
                           style="width: 100%; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 8px;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Tối đa 5MB mỗi ảnh. Định dạng: JPG, PNG, WebP</small>
                </div>

                <div>
                    <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                        File source code
                    </label>
                    <input type="file" name="source_files[]" multiple
                           style="width: 100%; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 8px;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Tối đa 100MB mỗi file. Định dạng: ZIP, RAR, 7Z</small>
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
                    ✓ Tạo sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
