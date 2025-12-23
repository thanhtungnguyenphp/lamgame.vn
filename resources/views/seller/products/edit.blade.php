@extends('shop::layouts.master')

@section('page_title')
    Chỉnh sửa sản phẩm
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-4xl">
    <h1 class="mb-6 text-3xl font-bold">Chỉnh sửa sản phẩm</h1>

    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Thông tin cơ bản</h2>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Tên sản phẩm *</label>
                <input type="text" name="name" value="{{ old('name', $product->flat->name ?? $product->name) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                @error('name')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Mô tả ngắn *</label>
                <textarea name="short_description" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">{{ old('short_description', $product->flat->short_description ?? '') }}</textarea>
                @error('short_description')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Mô tả chi tiết *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">{{ old('description', $product->flat->description ?? '') }}</textarea>
                @error('description')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-medium">Giá (VNĐ) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->flat->price ?? 0) }}" min="0" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    @error('price')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium">Danh mục *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->categories->contains($category->id) ? 'selected' : '' }}>
                                {{ $category->translations->first()->name ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Hình ảnh hiện tại</h2>
            <div class="grid grid-cols-4 gap-4 mb-4">
                @foreach($product->images as $image)
                    <img src="{{ Storage::url($image->path) }}" class="w-full rounded" alt="">
                @endforeach
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Thêm hình ảnh mới</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Files hiện tại</h2>
            <ul class="mb-4 space-y-2">
                @foreach($product->downloadable_links as $link)
                    <li class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span>{{ $link->title }} ({{ $link->downloads }} lượt tải)</span>
                    </li>
                @endforeach
            </ul>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Thêm files mới</label>
                <input type="file" name="source_files[]" multiple class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('seller.products.index') }}" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700">
                Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection
