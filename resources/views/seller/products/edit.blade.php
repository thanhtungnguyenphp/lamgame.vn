@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.product-edit-page {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    padding: 3rem 0;
    min-height: calc(100vh - 200px);
}
.edit-card {
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
.existing-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}
.image-item {
    position: relative;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}
.image-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}
.image-delete {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    font-size: 1.2rem;
    line-height: 1;
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
<div class="product-edit-page">
    <div class="container">
        <div class="edit-card">
            <h1 style="color: #2c5f41; font-size: 2rem; font-weight: 800; margin-bottom: 2rem;">
                ✏️ Chỉnh sửa sản phẩm
            </h1>

            <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="form-section">
                    <h3>📝 Thông tin cơ bản</h3>

                    <div class="form-group">
                        <label>SKU <span style="color: red;">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" readonly style="background: #f8f9fa; cursor: not-allowed;">
                        <small style="color: #666;">SKU không thể thay đổi</small>
                    </div>

                    <div class="form-group">
                        <label>Tên sản phẩm <span style="color: red;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="form-section">
                    <h3>💰 Giá bán</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Giá <span style="color: red;">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1000">
                        </div>

                        <div class="form-group">
                            <label>Giá khuyến mãi</label>
                            <input type="number" name="special_price" value="{{ old('special_price', $product->special_price) }}" min="0" step="1000">
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
                                <option value="{{ $category->id }}" 
                                    {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: #666;">Giữ Ctrl (Cmd) để chọn nhiều danh mục</small>
                    </div>
                </div>

                <!-- Existing Images -->
                @if($product->images->count() > 0)
                <div class="form-section">
                    <h3>🖼️ Hình ảnh hiện tại</h3>
                    <div class="existing-images">
                        @foreach($product->images as $image)
                            <div class="image-item">
                                <img src="{{ Storage::url($image->path) }}" alt="Product Image">
                                <button type="button" class="image-delete" onclick="deleteImage({{ $image->id }})">×</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- New Images -->
                <div class="form-section">
                    <h3>🖼️ Thêm hình ảnh mới</h3>
                    <div class="form-group">
                        <label>Upload hình ảnh (tối đa 5 ảnh)</label>
                        <input type="file" name="images[]" multiple accept="image/*" style="padding: 0.5rem;">
                        <small style="color: #666;">JPG, PNG, WebP - Max 2MB mỗi ảnh</small>
                    </div>
                </div>

                <!-- Existing Source File -->
                @if($product->type === 'downloadable' && $product->downloadable_links->count() > 0)
                <div class="form-section">
                    <h3>📦 File Source Code hiện tại</h3>
                    @foreach($product->downloadable_links as $link)
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                            <strong>{{ $link->title }}</strong><br>
                            <small style="color: #666;">File: {{ basename($link->file) }}</small>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- New Source File -->
                <div class="form-section">
                    <h3>📦 Cập nhật File Source Code</h3>
                    <div class="form-group">
                        <label>Upload file source mới (nếu cần)</label>
                        <input type="file" name="source_file" accept=".zip,.rar,.unitypackage" style="padding: 0.5rem;">
                        <small style="color: #666;">Max 512MB - Để trống nếu không muốn thay đổi</small>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    💾 Cập nhật sản phẩm
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function deleteImage(imageId) {
    if (!confirm('Xóa hình ảnh này?')) return;
    
    fetch(`/seller/products/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        alert('Lỗi khi xóa hình ảnh');
    });
}
</script>
@endsection
