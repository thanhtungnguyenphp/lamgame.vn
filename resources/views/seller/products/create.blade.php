@extends('shop::layouts.master')

@section('page_title')
    Thêm sản phẩm mới
@endsection

@section('content-wrapper')
<div class="container px-4 py-8 mx-auto max-w-4xl">
    <h1 class="mb-6 text-3xl font-bold">Thêm sản phẩm mới</h1>

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Thông tin cơ bản</h2>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Tên sản phẩm *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                @error('name')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Mô tả ngắn *</label>
                <textarea name="short_description" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">{{ old('short_description') }}</textarea>
                @error('short_description')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Mô tả chi tiết *</label>
                <textarea name="description" rows="8" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
                @error('description')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-medium">Giá (VNĐ) *</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <small class="text-gray-500">Nhập 0 nếu miễn phí</small>
                    @error('price')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium">Danh mục *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->translations->first()->name ?? $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Thông tin kỹ thuật</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-medium">Game Engine</label>
                    <input type="text" name="game_engine" value="{{ old('game_engine') }}" placeholder="Unity, Unreal, Godot..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block mb-2 font-medium">Ngôn ngữ lập trình</label>
                    <input type="text" name="programming_language" value="{{ old('programming_language') }}" placeholder="C#, C++, JavaScript..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-medium">Phiên bản</label>
                    <input type="text" name="version" value="{{ old('version', '1.0') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Yêu cầu hệ thống</label>
                <textarea name="requirements" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">{{ old('requirements') }}</textarea>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="mb-4 text-xl font-semibold">Hình ảnh & Files</h2>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Hình ảnh sản phẩm</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                <small class="text-gray-500">Tối đa 5MB/ảnh. Có thể chọn nhiều ảnh.</small>
                @error('images.*')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Source code files *</label>
                <input type="file" name="source_files[]" multiple required class="w-full px-4 py-2 border rounded-lg">
                <small class="text-gray-500">Tối đa 100MB/file. Có thể chọn nhiều files.</small>
                @error('source_files.*')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('seller.products.index') }}" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700">
                Tạo sản phẩm
            </button>
        </div>
    </form>
</div>
@endsection
