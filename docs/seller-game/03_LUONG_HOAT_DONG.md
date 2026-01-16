# LUỒNG HOẠT ĐỘNG SELLER GAME

## 1. Luồng đăng ký Seller

```
┌─────────────────────────────────────────────────────────────────┐
│                    ĐĂNG KÝ SELLER                               │
└─────────────────────────────────────────────────────────────────┘

Customer                    System                      Admin
   │                          │                           │
   │ Truy cập /seller/register│                           │
   │─────────────────────────>│                           │
   │                          │                           │
   │ Kiểm tra đăng nhập       │                           │
   │<─────────────────────────│                           │
   │                          │                           │
   │ Điền form đăng ký        │                           │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ Validate & Save           │
   │                          │ status = 'pending'        │
   │                          │                           │
   │                          │ Gửi email thông báo       │
   │                          │──────────────────────────>│
   │                          │                           │
   │ Redirect /seller/pending │                           │
   │<─────────────────────────│                           │
   │                          │                           │
   │                          │                           │ Xem /admin/sellers/pending
   │                          │                           │
   │                          │                           │ Duyệt/Từ chối
   │                          │<──────────────────────────│
   │                          │                           │
   │                          │ Cập nhật status           │
   │                          │ Gửi email kết quả         │
   │<─────────────────────────│                           │
   │                          │                           │
```

## 2. Luồng Upload Sản phẩm

```
┌─────────────────────────────────────────────────────────────────┐
│                    UPLOAD SẢN PHẨM                              │
└─────────────────────────────────────────────────────────────────┘

Seller                      System                      Admin
   │                          │                           │
   │ Truy cập /seller/products/create                     │
   │─────────────────────────>│                           │
   │                          │                           │
   │ Middleware CheckSeller   │                           │
   │ - Kiểm tra seller active │                           │
   │<─────────────────────────│                           │
   │                          │                           │
   │ Điền thông tin sản phẩm  │                           │
   │ - Tên, mô tả, giá        │                           │
   │ - Upload hình ảnh        │                           │
   │ - Upload source file     │                           │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ Validate                  │
   │                          │ Lưu product (status=0)    │
   │                          │ Lưu images                │
   │                          │ Lưu downloadable_links    │
   │                          │                           │
   │ Redirect /seller/products│                           │
   │<─────────────────────────│                           │
   │                          │                           │
   │ Gửi duyệt (submitForReview)                          │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ pending_review = true     │
   │                          │──────────────────────────>│
   │                          │                           │
   │                          │                           │ Duyệt sản phẩm
   │                          │<──────────────────────────│
   │                          │                           │
   │                          │ status = 1 (active)       │
   │<─────────────────────────│                           │
```

## 3. Luồng Mua hàng & Tính doanh thu

```
┌─────────────────────────────────────────────────────────────────┐
│                    MUA HÀNG & DOANH THU                         │
└─────────────────────────────────────────────────────────────────┘

Buyer                       System                      Seller
   │                          │                           │
   │ Mua source game          │                           │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ Tạo Order                 │
   │                          │ Thanh toán thành công     │
   │                          │                           │
   │                          │ SourceGameEarning::       │
   │                          │ createFromOrder($order)   │
   │                          │                           │
   │                          │ Tính toán:                │
   │                          │ - order_amount            │
   │                          │ - platform_fee (30%)      │
   │                          │ - seller_amount (70%)     │
   │                          │                           │
   │                          │ Cập nhật seller stats:    │
   │                          │ - total_sales++           │
   │                          │ - total_revenue +=        │
   │                          │──────────────────────────>│
   │                          │                           │
   │ Download source          │                           │
   │<─────────────────────────│                           │
```

## 4. Luồng Rút tiền

```
┌─────────────────────────────────────────────────────────────────┐
│                    RÚT TIỀN                                     │
└─────────────────────────────────────────────────────────────────┘

Seller                      System                      Admin
   │                          │                           │
   │ Truy cập /seller/withdrawals/create                  │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ Tính available_balance:   │
   │                          │ = total_earnings          │
   │                          │ - total_withdrawn         │
   │                          │ - pending_withdrawals     │
   │                          │                           │
   │ Kiểm tra >= 100,000đ     │                           │
   │<─────────────────────────│                           │
   │                          │                           │
   │ Nhập số tiền muốn rút    │                           │
   │─────────────────────────>│                           │
   │                          │                           │
   │                          │ Validate amount           │
   │                          │ Tạo withdrawal request    │
   │                          │ status = 'pending'        │
   │                          │                           │
   │ Redirect /seller/withdrawals                         │
   │<─────────────────────────│                           │
   │                          │                           │
   │                          │                           │ Xử lý yêu cầu
   │                          │                           │ Chuyển khoản
   │                          │<──────────────────────────│
   │                          │                           │
   │                          │ status = 'completed'      │
   │                          │ processed_at = now()      │
   │<─────────────────────────│                           │
```

## 5. Luồng Admin quản lý Seller

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN QUẢN LÝ SELLER                         │
└─────────────────────────────────────────────────────────────────┘

                            Actions
                               │
        ┌──────────────────────┼──────────────────────┐
        │                      │                      │
        ▼                      ▼                      ▼
   ┌─────────┐           ┌─────────┐           ┌─────────┐
   │ approve │           │ reject  │           │ suspend │
   └────┬────┘           └────┬────┘           └────┬────┘
        │                     │                     │
        ▼                     ▼                     ▼
   status='active'      status='rejected'    status='suspended'
   verified=true        Gửi email lý do      
   Gửi email duyệt                           
        │                                          │
        │                                          │
        └──────────────────────┬───────────────────┘
                               │
                               ▼
                          ┌─────────┐
                          │ activate│
                          └────┬────┘
                               │
                               ▼
                         status='active'
```

## 6. Trạng thái Seller

```
                    ┌─────────────┐
                    │   pending   │
                    └──────┬──────┘
                           │
              ┌────────────┼────────────┐
              │            │            │
              ▼            │            ▼
        ┌─────────┐        │      ┌──────────┐
        │ rejected│        │      │  active  │◄────┐
        └─────────┘        │      └────┬─────┘     │
                           │           │           │
                           │           ▼           │
                           │     ┌───────────┐     │
                           │     │ suspended │─────┘
                           │     └─────┬─────┘  activate
                           │           │
                           │           ▼
                           │     ┌─────────┐
                           └────>│ banned  │
                                 └─────────┘
```

## 7. Trạng thái Withdrawal

```
┌─────────┐     ┌────────────┐     ┌───────────┐
│ pending │────>│ processing │────>│ completed │
└────┬────┘     └────────────┘     └───────────┘
     │
     │
     ▼
┌──────────┐
│ rejected │
└──────────┘
```
