<x-admin::layouts>
    <x-slot:title>
        Thêm Menu mới
    </x-slot>
    <div class="page-content">
        <h1>Thêm Menu mới</h1>
        <form action="{{ route('admin.menu.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Tên menu</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group mt-2">
                <label for="channel_id">Channel</label>
                <input type="number" name="channel_id" id="channel_id" class="form-control" required>
                <!-- Có thể thay bằng select nếu muốn lấy danh sách channel -->
            </div>
            <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
        </form>
    </div>
</x-admin::layouts> 