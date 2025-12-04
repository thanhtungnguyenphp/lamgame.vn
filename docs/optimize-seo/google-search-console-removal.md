# Hướng dẫn Submit URL Removal trong Google Search Console

## Bước 1: Truy cập Google Search Console
1. Đăng nhập vào https://search.google.com/search-console
2. Chọn property: lamgame.vn

## Bước 2: Submit URL Removal

### Loại 1: Xóa tất cả URL có index.php
1. Vào **Removals** (menu bên trái)
2. Click **New Request**
3. Chọn **Remove all URLs with this prefix**
4. Nhập: `https://lamgame.vn/index.php/`
5. Click **Next** → **Submit Request**

### Loại 2: Xóa trang phân trang (page > 1)
Không thể xóa hàng loạt bằng wildcard, nên sử dụng robots.txt đã cấu hình.
Google sẽ tự động deindex trong 2-4 tuần.

### Loại 3: Xóa auth pages
1. Vào **Removals**
2. Click **New Request**
3. Chọn **Remove all URLs with this prefix**
4. Nhập: `https://lamgame.vn/auth/`
5. Click **Next** → **Submit Request**

6. Lặp lại với: `https://lamgame.vn/index.php/auth/`

## Bước 3: Request Re-indexing cho Clean URLs

Sau khi redirect đã hoạt động, submit lại các URL clean:
1. Vào **URL Inspection**
2. Nhập URL clean (ví dụ: `https://lamgame.vn/blog?tag=unity-3d`)
3. Click **Request Indexing**

## Bước 4: Monitor

Kiểm tra sau 1 tuần:
- **Coverage Report**: Số lượng indexed pages giảm
- **Removals**: Status của các request
- **Performance**: Traffic không bị ảnh hưởng

## Timeline dự kiến

- **Ngay lập tức**: Redirect hoạt động
- **1-3 ngày**: URL removal được xử lý
- **1-2 tuần**: Google bắt đầu deindex
- **2-4 tuần**: Hoàn tất deindex

## Lưu ý quan trọng

⚠️ **Không xóa URL có traffic cao** trước khi kiểm tra redirect hoạt động
⚠️ **Backup sitemap** trước khi cập nhật
⚠️ **Monitor 404 errors** trong Search Console
