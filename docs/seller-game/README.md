# 📚 TÀI LIỆU SELLER GAME

## Tổng quan

Hệ thống **Seller Game** cho phép người dùng đăng ký trở thành người bán (seller) để upload và bán source code game trên nền tảng Làm Game.

## Mục lục

1. [Tổng quan chức năng](./01_TONG_QUAN.md)
2. [Kiến trúc kỹ thuật](./02_KY_THUAT.md)
3. [Luồng hoạt động](./03_LUONG_HOAT_DONG.md)
4. [API & Routes](./04_API_ROUTES.md)
5. [Database Schema](./05_DATABASE.md)

## Tính năng chính

### Dành cho Seller
- Đăng ký tài khoản seller
- Quản lý thông tin shop
- Upload và quản lý sản phẩm (source code game)
- Xem thống kê doanh thu
- Yêu cầu rút tiền

### Dành cho Admin
- Duyệt/từ chối seller mới
- Quản lý trạng thái seller
- Duyệt sản phẩm
- Xử lý yêu cầu rút tiền

## Liên kết nhanh

| Chức năng | URL |
|-----------|-----|
| Đăng ký Seller | `/seller/register` |
| Dashboard | `/seller/dashboard` |
| Quản lý sản phẩm | `/seller/products` |
| Rút tiền | `/seller/withdrawals` |
| Admin - Sellers | `/admin/sellers` |
