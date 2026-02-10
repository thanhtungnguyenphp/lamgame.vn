# Source Game Detail - TODO

## Chức năng cần phát triển

### 1. Yêu thích (Wishlist)
- [ ] Tạo bảng `product_wishlists` (hoặc dùng Bagisto wishlist có sẵn)
- [ ] API endpoint: `POST /api/wishlist/add` / `DELETE /api/wishlist/remove`
- [ ] Hiển thị trạng thái yêu thích (đã thêm/chưa) trên nút
- [ ] Yêu cầu đăng nhập

### 2. Bộ sưu tập (Collections)
- [ ] Tạo bảng `user_collections` và `collection_items`
- [ ] API endpoint: `POST /api/collections` (tạo), `POST /api/collections/{id}/items` (thêm sản phẩm)
- [ ] Modal chọn/tạo bộ sưu tập khi click nút
- [ ] Yêu cầu đăng nhập

### 3. Bình luận / Reviews
- [ ] Sử dụng bảng `product_reviews` có sẵn của Bagisto
- [ ] Form bình luận trong tab "Bình luận"
- [ ] Hiển thị danh sách bình luận đã duyệt
- [ ] Rating stars (1-5)
- [ ] Yêu cầu đăng nhập + đã mua sản phẩm

### 4. Tác giả / Seller Profile
- [ ] Trang seller profile: `/seller/{shop_slug}`
- [ ] Hiển thị danh sách sản phẩm của seller
- [ ] Thống kê: tổng sản phẩm, tổng lượt mua, rating trung bình
