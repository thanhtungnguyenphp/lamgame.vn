# Cấu trúc Database - Checkout

## Bảng chính

### `carts`
Lưu thông tin giỏ hàng

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| customer_email | varchar | Email khách hàng |
| customer_first_name | varchar | Tên |
| customer_last_name | varchar | Họ |
| shipping_method | varchar | Phương thức vận chuyển |
| coupon_code | varchar | Mã giảm giá |
| is_gift | boolean | Là quà tặng |
| items_count | int | Số lượng items |
| items_qty | decimal | Tổng số lượng |
| grand_total | decimal | Tổng tiền |
| base_grand_total | decimal | Tổng tiền (base currency) |
| sub_total | decimal | Tạm tính |
| tax_total | decimal | Thuế |
| discount_amount | decimal | Giảm giá |
| checkout_method | varchar | guest/customer |
| is_active | boolean | Giỏ hàng active |
| customer_id | bigint | FK to customers |
| channel_id | bigint | FK to channels |

### `cart_items`
Chi tiết từng item trong giỏ

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| quantity | int | Số lượng |
| sku | varchar | SKU sản phẩm |
| type | varchar | simple/configurable/downloadable |
| name | varchar | Tên sản phẩm |
| price | decimal | Giá |
| total | decimal | Thành tiền |
| tax_amount | decimal | Thuế |
| discount_amount | decimal | Giảm giá |
| additional | json | Thông tin thêm (options, links) |
| cart_id | bigint | FK to carts |
| product_id | bigint | FK to products |
| parent_id | bigint | FK to cart_items (for variants) |

### `cart_addresses`
Địa chỉ billing/shipping

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| address_type | varchar | billing/shipping |
| first_name | varchar | Tên |
| last_name | varchar | Họ |
| email | varchar | Email |
| phone | varchar | Số điện thoại |
| address | text | Địa chỉ chi tiết |
| city | varchar | Thành phố |
| state | varchar | Tỉnh/Bang |
| country | varchar | Quốc gia |
| postcode | varchar | Mã bưu điện |
| cart_id | bigint | FK to carts |

### `cart_shipping_rates`
Phí vận chuyển

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| carrier | varchar | Nhà vận chuyển |
| carrier_title | varchar | Tên hiển thị |
| method | varchar | Phương thức |
| method_title | varchar | Tên phương thức |
| method_description | text | Mô tả |
| price | decimal | Phí |
| cart_address_id | bigint | FK to cart_addresses |

### `cart_payment`
Thông tin thanh toán

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| method | varchar | Phương thức (cod, paypal, etc) |
| method_title | varchar | Tên hiển thị |
| cart_id | bigint | FK to carts |

## Bảng Orders

### `orders`
Đơn hàng đã đặt

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| increment_id | varchar | Mã đơn hàng (hiển thị) |
| status | varchar | pending/processing/completed/canceled |
| channel_name | varchar | Kênh bán hàng |
| customer_email | varchar | Email |
| customer_first_name | varchar | Tên |
| customer_last_name | varchar | Họ |
| shipping_method | varchar | Phương thức vận chuyển |
| shipping_title | varchar | Tên vận chuyển |
| shipping_amount | decimal | Phí vận chuyển |
| grand_total | decimal | Tổng tiền |
| sub_total | decimal | Tạm tính |
| tax_amount | decimal | Thuế |
| discount_amount | decimal | Giảm giá |
| customer_id | bigint | FK to customers |
| channel_id | bigint | FK to channels |
| cart_id | bigint | FK to carts |

### `order_items`
Chi tiết đơn hàng

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| sku | varchar | SKU |
| type | varchar | Loại sản phẩm |
| name | varchar | Tên |
| qty_ordered | int | Số lượng đặt |
| qty_shipped | int | Số lượng đã giao |
| qty_invoiced | int | Số lượng đã xuất hóa đơn |
| qty_canceled | int | Số lượng đã hủy |
| qty_refunded | int | Số lượng đã hoàn |
| price | decimal | Giá |
| total | decimal | Thành tiền |
| additional | json | Thông tin thêm |
| order_id | bigint | FK to orders |
| product_id | bigint | FK to products |

### `order_addresses`
Địa chỉ đơn hàng

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| address_type | varchar | billing/shipping |
| ... | ... | (tương tự cart_addresses) |
| order_id | bigint | FK to orders |

### `order_payment`
Thanh toán đơn hàng

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| method | varchar | Phương thức |
| method_title | varchar | Tên |
| order_id | bigint | FK to orders |

## Downloadable Products

### `product_downloadable_links`
Links download của sản phẩm

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| url | varchar | URL download (nếu type=url) |
| file | varchar | File path (nếu type=file) |
| file_name | varchar | Tên file |
| type | varchar | url/file |
| price | decimal | Giá thêm cho link này |
| downloads | int | Số lần download cho phép |
| product_id | bigint | FK to products |

### `downloadable_link_purchased`
Links đã mua

| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| product_name | varchar | Tên sản phẩm |
| name | varchar | Tên link |
| url | varchar | URL |
| file | varchar | File |
| type | varchar | url/file |
| download_bought | int | Số lần được download |
| download_used | int | Số lần đã download |
| status | varchar | pending/available/expired |
| customer_id | bigint | FK to customers |
| order_id | bigint | FK to orders |
| order_item_id | bigint | FK to order_items |

## Relationships

```
customers
    │
    ├── carts (1:N)
    │       │
    │       ├── cart_items (1:N)
    │       ├── cart_addresses (1:N)
    │       ├── cart_shipping_rates (1:N)
    │       └── cart_payment (1:1)
    │
    └── orders (1:N)
            │
            ├── order_items (1:N)
            ├── order_addresses (1:N)
            ├── order_payment (1:1)
            ├── invoices (1:N)
            ├── shipments (1:N)
            └── refunds (1:N)
```
