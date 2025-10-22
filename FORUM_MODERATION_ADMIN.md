# Forum Content Moderation System - Admin Panel

## Tổng quan

Hệ thống quản lý và kiểm duyệt nội dung Forum trong admin panel đã được triển khai hoàn chỉnh, cho phép admin quản lý bài viết, bình luận và báo cáo vi phạm.

## Cấu trúc Files

### 1. Controller
```
packages/Webkul/Admin/src/Http/Controllers/Forum/ForumModerationController.php
```

Chứa các methods:
- **Posts Management**: `posts()`, `showPost()`, `updatePost()`, `destroyPost()`, `massUpdatePosts()`, `massDestroyPosts()`
- **Comments Management**: `comments()`, `showComment()`, `updateComment()`, `destroyComment()`, `massUpdateComments()`, `massDestroyComments()`
- **Reports Management**: `reports()`, `showReport()`, `updateReport()`, `destroyReport()`, `massUpdateReports()`, `massDestroyReports()`
- **Statistics**: `stats()` - API endpoint để lấy thống kê forum

### 2. DataGrids
```
packages/Webkul/Admin/src/DataGrids/Forum/
├── ForumPostDataGrid.php
├── ForumCommentDataGrid.php
└── ForumReportDataGrid.php
```

Mỗi DataGrid cung cấp:
- Filtering (lọc theo status, category, date range, etc.)
- Sorting (sắp xếp theo các columns)
- Mass actions (cập nhật/xóa hàng loạt)
- Individual actions (xem, sửa, xóa)

### 3. Routes
```
packages/Webkul/Admin/src/Routes/forum-routes.php
```

Tất cả routes được prefix với `/admin/forum`:
- `/admin/forum/posts` - Quản lý bài viết
- `/admin/forum/comments` - Quản lý bình luận
- `/admin/forum/reports` - Quản lý báo cáo
- `/admin/forum/stats` - API thống kê

### 4. Views
```
packages/Webkul/Admin/src/Resources/views/forum/
├── posts/
│   └── index.blade.php
├── comments/
│   └── index.blade.php
└── reports/
    └── index.blade.php
```

## Tính năng

### Quản lý Bài viết (Posts)
- **Danh sách**: Hiển thị tất cả bài viết với thông tin chi tiết
- **Filter**: Theo status (published/pending/rejected), type, category, date
- **Sort**: Theo views, likes, comments, date
- **Actions**:
  - Xem chi tiết bài viết trên frontend
  - Cập nhật status (published/pending/rejected)
  - Đánh dấu featured/sticky
  - Xóa bài viết
- **Mass Actions**: Cập nhật status hoặc xóa nhiều bài viết cùng lúc

### Quản lý Bình luận (Comments)
- **Danh sách**: Hiển thị tất cả bình luận với preview nội dung
- **Filter**: Theo status, bài viết, tác giả, date
- **Actions**:
  - Xem bài viết chứa comment
  - Cập nhật status (published/pending/rejected)
  - Xóa bình luận
- **Mass Actions**: Duyệt/từ chối hoặc xóa nhiều bình luận
- **Auto-update**: Tự động cập nhật comment count của bài viết

### Quản lý Báo cáo (Reports)
- **Danh sách**: Hiển thị tất cả báo cáo vi phạm
- **Filter**: Theo status, reason, reporter, date
- **Thông tin**:
  - Người báo cáo
  - Loại nội dung (post/comment)
  - Lý do (spam/inappropriate/harassment/copyright/other)
  - Status (pending/reviewed/resolved/dismissed)
  - Người xử lý và thời gian
- **Actions**:
  - Xem nội dung bị báo cáo
  - Cập nhật status và admin notes
  - Xóa báo cáo
- **Mass Actions**: Xử lý nhiều báo cáo cùng lúc

## Status Flow

### Bài viết & Bình luận
```
pending → published (duyệt)
pending → rejected (từ chối)
published → rejected (ẩn sau khi duyệt)
```

### Báo cáo
```
pending → reviewed (đã xem xét)
reviewed → resolved (đã giải quyết)
reviewed → dismissed (từ chối xử lý)
```

## API Endpoints

### Posts
- `GET /admin/forum/posts` - Danh sách bài viết
- `GET /admin/forum/posts/{id}` - Chi tiết bài viết
- `PUT /admin/forum/posts/edit/{id}` - Cập nhật bài viết
- `DELETE /admin/forum/posts/{id}` - Xóa bài viết
- `POST /admin/forum/posts/mass-update` - Cập nhật hàng loạt
- `POST /admin/forum/posts/mass-delete` - Xóa hàng loạt

### Comments
- `GET /admin/forum/comments` - Danh sách bình luận
- `GET /admin/forum/comments/{id}` - Chi tiết bình luận
- `PUT /admin/forum/comments/edit/{id}` - Cập nhật bình luận
- `DELETE /admin/forum/comments/{id}` - Xóa bình luận
- `POST /admin/forum/comments/mass-update` - Cập nhật hàng loạt
- `POST /admin/forum/comments/mass-delete` - Xóa hàng loạt

### Reports
- `GET /admin/forum/reports` - Danh sách báo cáo
- `GET /admin/forum/reports/{id}` - Chi tiết báo cáo
- `PUT /admin/forum/reports/edit/{id}` - Xử lý báo cáo
- `DELETE /admin/forum/reports/{id}` - Xóa báo cáo
- `POST /admin/forum/reports/mass-update` - Xử lý hàng loạt
- `POST /admin/forum/reports/mass-delete` - Xóa hàng loạt

### Statistics
- `GET /admin/forum/stats` - Lấy thống kê tổng quan

## Cài đặt

### Bước 1: Load Routes
Thêm vào file `packages/Webkul/Admin/src/Providers/AdminServiceProvider.php`:

```php
protected function loadRoutes()
{
    include __DIR__ . '/../Routes/forum-routes.php';
}
```

Hoặc thêm vào `routes/web.php`:

```php
Route::group([
    'prefix' => config('app.admin_path', 'admin'),
    'middleware' => ['web', 'admin']
], function () {
    include base_path('packages/Webkul/Admin/src/Routes/forum-routes.php');
});
```

### Bước 2: Thêm Menu Admin
Cấu hình menu trong admin panel để hiển thị mục Forum. Thêm vào config menu:

```php
[
    'key'        => 'forum',
    'name'       => 'Forum',
    'route'      => 'admin.forum.posts.index',
    'sort'       => 7,
    'icon'       => 'icon-chat',
],
[
    'key'        => 'forum.posts',
    'name'       => 'Bài viết',
    'route'      => 'admin.forum.posts.index',
    'sort'       => 1,
],
[
    'key'        => 'forum.comments',
    'name'       => 'Bình luận',
    'route'      => 'admin.forum.comments.index',
    'sort'       => 2,
],
[
    'key'        => 'forum.reports',
    'name'       => 'Báo cáo',
    'route'      => 'admin.forum.reports.index',
    'sort'       => 3,
],
```

### Bước 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Permissions (Tùy chọn)

Nếu sử dụng hệ thống permissions của Bagisto, thêm các permissions:

```php
[
    'key'   => 'forum',
    'name'  => 'Forum',
    'route' => 'admin.forum.posts.index',
    'sort'  => 7,
],
[
    'key'   => 'forum.posts',
    'name'  => 'Quản lý bài viết',
    'route' => 'admin.forum.posts.index',
    'sort'  => 1,
],
// ... tương tự cho comments và reports
```

## Database Tables

Hệ thống sử dụng các tables hiện có:
- `forum_posts`
- `forum_comments`
- `forum_reports`
- `forum_categories`
- `forum_tags`
- `customers` (reporter info)
- `admins` (reviewer info)

## Tính năng Nâng cao

### 1. Thống kê Dashboard
Có thể tích hợp stats vào admin dashboard:

```php
$stats = Http::get(route('admin.forum.stats'))->json()['data'];
```

### 2. Notifications
Tích hợp notifications khi có báo cáo mới hoặc bài viết chờ duyệt.

### 3. Bulk Actions
Hỗ trợ xử lý hàng loạt để tăng hiệu quả quản lý.

### 4. Filters & Search
DataGrid hỗ trợ search và filter mạnh mẽ theo nhiều tiêu chí.

## Lưu ý

1. **Security**: Đảm bảo middleware admin được áp dụng cho tất cả routes
2. **Performance**: DataGrid tự động phân trang, filter server-side
3. **Responsive**: UI tương thích với mobile/tablet
4. **Dark Mode**: Tất cả views hỗ trợ dark mode của Bagisto

## Troubleshooting

### Routes không hoạt động
```bash
php artisan route:list | grep forum
```

### DataGrid không hiển thị
- Check namespace của DataGrid class
- Verify query trong `prepareQueryBuilder()`
- Check permissions

### Views không load
- Verify view path
- Check blade component namespace
- Clear view cache

## Support & Maintenance

- Tất cả code tuân theo chuẩn PSR-12
- Compatible với Bagisto 2.x
- Sử dụng Tailwind CSS classes có sẵn
- Vue 3 components tương thích
