# Hướng dẫn Seller tạo sản phẩm - LamGame.vn

## Mục lục
1. [Tổng quan](#1-tổng-quan)
2. [Yêu cầu trước khi tạo sản phẩm](#2-yêu-cầu-trước-khi-tạo-sản-phẩm)
3. [Quy trình tạo sản phẩm](#3-quy-trình-tạo-sản-phẩm)
4. [Các trường thông tin](#4-các-trường-thông-tin)
5. [Quy trình duyệt sản phẩm](#5-quy-trình-duyệt-sản-phẩm)
6. [Lỗi thường gặp](#6-lỗi-thường-gặp)

---

## 1. Tổng quan

Seller có thể upload source game để bán trên LamGame.vn. Sản phẩm được tạo dưới dạng **Downloadable Product** trong hệ thống Bagisto.

### Flow tạo sản phẩm:
```
Seller đăng nhập → Vào trang tạo sản phẩm → Điền thông tin → Upload files → Submit → Chờ Admin duyệt
```

### URL:
- Trang tạo: `/seller/products/create`
- Danh sách sản phẩm: `/seller/products`

---

## 2. Yêu cầu trước khi tạo sản phẩm

### 2.1 Tài khoản Seller
- Đã đăng ký tài khoản seller
- Tài khoản đã được Admin **approve**
- Trạng thái seller: `active`

### 2.2 Kiểm tra quyền
```php
// Controller kiểm tra:
$seller = Auth::guard('customer')->user()->seller;
if (!$seller || !$seller->canUploadProduct()) {
    return back()->with('error', 'Bạn không có quyền upload sản phẩm');
}
```

---

## 3. Quy trình tạo sản phẩm

### 3.1 Bước 1: Truy cập trang tạo sản phẩm
- Đăng nhập vào tài khoản
- Vào Dashboard Seller
- Click "Thêm sản phẩm mới"

### 3.2 Bước 2: Điền thông tin cơ bản
| Trường | Bắt buộc | Mô tả |
|--------|----------|-------|
| Tên sản phẩm | ✅ | Tối đa 255 ký tự |
| Mô tả ngắn | ✅ | Tối đa 500 ký tự |
| Mô tả chi tiết | ✅ | HTML được hỗ trợ |
| Giá (VNĐ) | ✅ | Số >= 0, nhập 0 nếu miễn phí |
| Danh mục | ✅ | Chọn từ danh sách |

### 3.3 Bước 3: Thông tin kỹ thuật (tùy chọn)
- Game Engine (Unity, Unreal, Godot...)
- Ngôn ngữ lập trình (C#, C++, JavaScript...)
- Phiên bản
- Yêu cầu hệ thống

### 3.4 Bước 4: Upload files
| Loại file | Giới hạn | Định dạng |
|-----------|----------|-----------|
| Hình ảnh | 5MB/ảnh | JPG, PNG, WebP |
| Source files | 100MB/file | ZIP, RAR, 7Z |

### 3.5 Bước 5: Submit
- Click "Tạo sản phẩm"
- Sản phẩm được tạo với trạng thái `status = 0` (Draft)
- Chờ Admin duyệt

---

## 4. Các trường thông tin

### 4.1 Thông tin tự động tạo
| Trường | Giá trị | Mô tả |
|--------|---------|-------|
| SKU | `SG-XXXXXXXX` | Random 8 ký tự |
| URL Key | `{slug-name}-{id}` | Tự động từ tên |
| Type | `downloadable` | Cố định |
| Status | `0` | Draft, chờ duyệt |
| seller_id | ID của seller | Liên kết với seller |

### 4.2 Validation rules
```php
[
    'name' => 'required|string|max:255',
    'short_description' => 'required|string|max:500',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'category_id' => 'required|exists:categories,id',
    'images.*' => 'nullable|image|max:5120',      // 5MB
    'source_files.*' => 'nullable|file|max:102400', // 100MB
]
```

---

## 5. Quy trình duyệt sản phẩm

### 5.1 Trạng thái sản phẩm
| Status | pending_review | Ý nghĩa |
|--------|----------------|---------|
| 0 | false | Nháp (Draft) |
| 0 | true | Chờ duyệt |
| 1 | false | Đã duyệt, hiển thị |

### 5.2 Flow duyệt
```
Seller tạo → status=0 → Admin review → Approve (status=1) hoặc Reject (gửi lý do)
```

---

## 6. Lỗi thường gặp

### 6.1 "Bạn không có quyền upload sản phẩm"
**Nguyên nhân:** Tài khoản seller chưa được approve hoặc bị suspend
**Giải pháp:** Liên hệ Admin để kiểm tra trạng thái tài khoản

### 6.2 "The category_id field is required"
**Nguyên nhân:** Chưa chọn danh mục
**Giải pháp:** Chọn một danh mục từ dropdown

### 6.3 "The images.0 must be an image"
**Nguyên nhân:** File upload không phải định dạng ảnh hợp lệ
**Giải pháp:** Chỉ upload file JPG, PNG, WebP

### 6.4 "The source_files.0 may not be greater than 102400 kilobytes"
**Nguyên nhân:** File source vượt quá 100MB
**Giải pháp:** Nén file hoặc chia nhỏ

### 6.5 Sản phẩm tạo nhưng không hiển thị
**Nguyên nhân:** Sản phẩm đang ở trạng thái Draft (status=0)
**Giải pháp:** Chờ Admin duyệt hoặc liên hệ Admin

---
$model = $this->model->findOrFail($id, $columns);
## Changelog

| Ngày | Phiên bản | Thay đổi |
|------|-----------|----------|
| 2026-01-18 | 1.0 | Tạo tài liệu ban đầu |
