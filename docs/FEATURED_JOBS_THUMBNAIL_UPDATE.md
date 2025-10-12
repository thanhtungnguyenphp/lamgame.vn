# Cập Nhật Thumbnail cho Featured Jobs Section

## Tổng Quan

Đã cập nhật system để lấy hình thumbnail của mỗi job từ database thay vì sử dụng hardcoded Unsplash URLs. Nếu job không có thumbnail, hệ thống sẽ sử dụng hình ảnh tuyển dụng mặc định.

## Các Thay Đổi Đã Thực Hiện

### 1. HomeController.php

#### a) Thêm constant cho default thumbnails
```php
private const DEFAULT_JOB_THUMBNAILS = [
    'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop&q=80', // Game development office
    'https://images.unsplash.com/photo-1556438064-2d7646166914?w=400&h=250&fit=crop&q=80', // Unity/coding screen  
    'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=400&h=250&fit=crop&q=80', // Programming code
    'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=250&fit=crop&q=80', // Mobile game dev
    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop&q=80', // VR/AR development
];
```

#### b) Cập nhật database query
- Thêm LEFT JOIN với `product_images` table để lấy thumbnail
- Chỉ lấy image đầu tiên (MIN id) với type = 'images'
- Thêm column `pi.path as thumbnail` vào SELECT

#### c) Thêm method getJobThumbnail()
- Xử lý nhiều format path khác nhau (full URL, relative path, storage path)
- Fallback về default recruitment images nếu job không có thumbnail
- Trả về random thumbnail từ default list

#### d) Cập nhật data transformation
- Thêm `'thumbnail' => $this->getJobThumbnail($job->thumbnail)` vào job data array

### 2. resources/views/home/index.blade.php

#### a) Loại bỏ hardcoded $jobImages array
- Xóa array chứa hardcoded Unsplash URLs
- Giữ lại $jobLevels và $badges

#### b) Cập nhật img src trong job cards
- Thay `$jobImages[$index] ?? $jobImages[0]` bằng `$job['thumbnail']`
- Cập nhật tất cả fallback jobs để có consistent image quality (?q=80)

## Database Structure

### Bảng product_images
- `id` - Primary key
- `product_id` - Foreign key tới products table
- `type` - Loại image ('images', 'videos')
- `path` - Đường dẫn tới file image

### Query Logic
```sql
LEFT JOIN product_images as pi ON (
    p.id = pi.product_id 
    AND pi.type = 'images' 
    AND pi.id = (SELECT MIN(id) FROM product_images WHERE product_id = p.id AND type = "images")
)
```

## Luồng Xử Lý

1. **Database Query**: Lấy job data + thumbnail path từ product_images
2. **Data Processing**: Transform job data và xử lý thumbnail path
3. **Thumbnail Resolution**: 
   - Có thumbnail trong DB → Format thành full URL
   - Không có thumbnail → Random select từ default list
4. **View Rendering**: Hiển thị job với thumbnail đã xử lý

## Fallback Strategy

### Khi job có thumbnail trong database:
- Full URL (http/https) → Sử dụng trực tiếp
- Path bắt đầu với `/` → Dùng `asset()`  
- Relative path → Dùng `asset('storage/' . path)`

### Khi job không có thumbnail:
- Random select từ 5 default recruitment images
- Tất cả images đều tối ưu (400x250, crop, q=80)
- Theme phù hợp với tuyển dụng game dev

## Testing

### Kiểm tra syntax:
```bash
php -l app/Http/Controllers/HomeController.php
# Result: No syntax errors detected
```

### Clear cache (nếu cần):
```bash
php artisan config:clear && php artisan cache:clear
```

## Lợi Ích

1. **Dynamic Content**: Jobs có thể có thumbnail riêng từ database
2. **Fallback Robust**: Luôn có image hiển thị dù job không có thumbnail
3. **Performance**: Images được tối ưu với quality setting
4. **Consistency**: Tất cả images có cùng kích thước và format
5. **Maintainable**: Default thumbnails được quản lý trong constant

## Tương Lai

- Có thể thêm logic upload thumbnail khi tạo job
- Có thể tích hợp AI để auto-generate thumbnail
- Có thể cache thumbnail URLs để improve performance