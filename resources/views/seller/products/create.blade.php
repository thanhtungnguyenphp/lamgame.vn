@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.product-create-page {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    padding: 3rem 0;
    min-height: calc(100vh - 200px);
}
.create-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto;
}
.form-section {
    margin-bottom: 2rem;
}
.form-section h3 {
    color: #2c5f41;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    font-weight: 700;
}
.form-group {
    margin-bottom: 1.5rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #2c5f41;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.file-upload {
    border: 2px dashed #2c5f41;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}
.file-upload:hover {
    background: #f8f9fa;
}
.btn-submit {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44,95,65,0.3);
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="product-create-page">
    <div class="container">
        <div class="create-card">
            <h1 style="color: #2c5f41; font-size: 2rem; font-weight: 800; margin-bottom: 2rem;">
                ➕ Thêm sản phẩm mới
            </h1>

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Info -->
                <div class="form-section">
                    <h3>📝 Thông tin cơ bản</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Loại sản phẩm <span style="color: red;">*</span></label>
                            <select name="type" required>
                                <option value="downloadable">Downloadable (Source Game)</option>
                                <option value="simple">Simple</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Attribute Family <span style="color: red;">*</span></label>
                            <select name="attribute_family_id" required>
                                @foreach($attributeFamilies as $family)
                                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>SKU <span style="color: red;">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="VD: game-unity-001">
                        <small style="color: #666;">Chỉ dùng chữ thường, số và dấu gạch ngang (-). VD: game-unity-001</small>
                        @error('sku')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tên sản phẩm <span style="color: red;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="VD: Unity 2D Platformer Game">
                        @error('name')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>URL Key <span style="color: red;">*</span></label>
                        <input type="text" name="url_key" value="{{ old('url_key') }}" required placeholder="VD: unity-2d-platformer-game">
                        @error('url_key')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="short_description" rows="3" placeholder="Mô tả ngắn gọn về sản phẩm...">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" rows="6" placeholder="Mô tả chi tiết về sản phẩm, tính năng, yêu cầu...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <h3>💰 Giá bán</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Giá <span style="color: red;">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000" placeholder="VD: 500000">
                        </div>

                        <div class="form-group">
                            <label>Giá khuyến mãi</label>
                            <input type="number" name="special_price" value="{{ old('special_price') }}" min="0" step="1000" placeholder="VD: 400000">
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="form-section">
                    <h3>📂 Danh mục</h3>
                    <div class="form-group">
                        <label>Chọn danh mục <span style="color: red;">*</span></label>
                        <select name="categories[]" multiple required style="height: 150px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small style="color: #666;">Giữ Ctrl (Cmd) để chọn nhiều danh mục</small>
                    </div>
                </div>

                <!-- Images -->
                <div class="form-section">
                    <h3>🖼️ Hình ảnh</h3>
                    <div class="form-group">
                        <label>Upload hình ảnh (tối đa 5 ảnh)</label>
                        <input type="file" name="images[]" multiple accept="image/*" style="padding: 0.5rem;">
                        <small style="color: #666;">JPG, PNG, WebP - Max 2MB mỗi ảnh</small>
                    </div>
                </div>

                <!-- Source File -->
                <div class="form-section">
                    <h3>📦 File Source Code</h3>
                    <div class="form-group">
                        <label>Upload file source (ZIP, RAR, UnityPackage)</label>
                        <input type="file" name="source_file" accept=".zip,.rar,.unitypackage" style="padding: 0.5rem;">
                        <small style="color: #666;">Max 512MB</small>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    🚀 Tạo sản phẩm
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
