# TỔNG QUAN CHỨC NĂNG SELLER GAME

## 1. Mô tả

Seller Game là hệ thống cho phép thành viên trở thành người bán source code game trên nền tảng Làm Game. Hệ thống bao gồm:

- **Seller Portal**: Giao diện quản lý dành cho người bán
- **Admin Management**: Quản lý seller từ phía admin
- **Earnings System**: Hệ thống tính toán và chi trả doanh thu

## 2. Các vai trò

### 2.1 Customer (Khách hàng)
- Đăng ký tài khoản seller
- Chờ admin duyệt

### 2.2 Seller (Người bán)
- Quản lý thông tin shop
- Upload sản phẩm (source code game)
- Xem thống kê doanh thu
- Yêu cầu rút tiền

### 2.3 Admin
- Duyệt/từ chối đăng ký seller
- Quản lý trạng thái seller (active/suspended/banned)
- Duyệt sản phẩm
- Xử lý yêu cầu rút tiền

## 3. Trạng thái Seller

| Status | Mô tả |
|--------|-------|
| `pending` | Đang chờ admin duyệt |
| `active` | Đã được duyệt, có thể bán hàng |
| `suspended` | Tạm ngưng hoạt động |
| `banned` | Bị cấm vĩnh viễn |

## 4. Quy trình đăng ký Seller

```
Customer đăng nhập
       ↓
Truy cập /seller/register
       ↓
Điền thông tin shop + ngân hàng
       ↓
Submit → Status = pending
       ↓
Admin duyệt/từ chối
       ↓
Nếu duyệt → Status = active
       ↓
Seller có thể upload sản phẩm
```

## 5. Thông tin Seller cần cung cấp

### Thông tin Shop
- Tên shop (shop_name)
- Mô tả shop (shop_description)
- Logo shop (shop_logo)
- Banner shop (shop_banner)

### Thông tin liên hệ
- Email liên hệ (contact_email)
- Số điện thoại (contact_phone)
- Website (website)

### Thông tin kinh doanh
- Loại hình (individual/company)
- Mã số thuế (tax_id) - bắt buộc nếu là company

### Thông tin ngân hàng
- Tên ngân hàng (bank_name)
- Số tài khoản (bank_account)
- Tên chủ tài khoản (bank_holder)

## 6. Phí nền tảng

- **Platform fee**: 30% doanh thu
- **Seller nhận**: 70% doanh thu

Ví dụ: Sản phẩm bán 1,000,000đ
- Platform fee: 300,000đ
- Seller nhận: 700,000đ

## 7. Điều kiện rút tiền

- Số dư tối thiểu: 100,000đ
- Thời gian xử lý: 3-5 ngày làm việc
- Chuyển khoản qua thông tin ngân hàng đã đăng ký
