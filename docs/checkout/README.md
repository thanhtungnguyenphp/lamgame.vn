# Checkout Flow Documentation

## Tổng quan

Hệ thống checkout của Bagisto/LamGame sử dụng kiến trúc Single Page Application (SPA) với Vue.js cho frontend và Laravel API cho backend.

## Quy trình Checkout

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Giỏ hàng   │───▶│   Địa chỉ   │───▶│  Vận chuyển │───▶│ Thanh toán  │───▶│  Đặt hàng   │
│  /cart      │    │  billing/   │    │  shipping   │    │  payment    │    │  success    │
│             │    │  shipping   │    │  method     │    │  method     │    │             │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
```

## Routes

### Web Routes (`checkout-routes.php`)
| Route | Name | Controller | Mô tả |
|-------|------|------------|-------|
| GET `/checkout/cart` | `shop.checkout.cart.index` | CartController@index | Trang giỏ hàng |
| GET `/checkout/onepage` | `shop.checkout.onepage.index` | OnepageController@index | Trang checkout |
| GET `/checkout/onepage/success` | `shop.checkout.onepage.success` | OnepageController@success | Trang thành công |

### API Routes (`api.php`)
| Route | Name | Method | Mô tả |
|-------|------|--------|-------|
| `/api/checkout/cart` | `shop.api.checkout.cart.store` | POST | Thêm sản phẩm vào giỏ |
| `/api/checkout/cart` | `shop.api.checkout.cart.update` | PUT | Cập nhật giỏ hàng |
| `/api/checkout/cart` | `shop.api.checkout.cart.destroy` | DELETE | Xóa item |
| `/api/checkout/cart/coupon` | `shop.api.checkout.cart.coupon.apply` | POST | Áp dụng mã giảm giá |
| `/api/checkout/onepage/summary` | `shop.checkout.onepage.summary` | GET | Lấy tóm tắt đơn hàng |
| `/api/checkout/onepage/addresses` | `shop.checkout.onepage.addresses.store` | POST | Lưu địa chỉ |
| `/api/checkout/onepage/shipping-methods` | `shop.checkout.onepage.shipping_methods.store` | POST | Chọn vận chuyển |
| `/api/checkout/onepage/payment-methods` | `shop.checkout.onepage.payment_methods.store` | POST | Chọn thanh toán |
| `/api/checkout/onepage/orders` | `shop.checkout.onepage.orders.store` | POST | Tạo đơn hàng |

## Controllers

### CartController
- **index()**: Hiển thị trang giỏ hàng

### OnepageController (Web)
- **index()**: Hiển thị trang checkout onepage
- **success()**: Hiển thị trang đặt hàng thành công

### OnepageController (API)
- **summary()**: Trả về thông tin giỏ hàng
- **storeAddress()**: Lưu địa chỉ billing/shipping
- **storeShippingMethod()**: Lưu phương thức vận chuyển
- **storePaymentMethod()**: Lưu phương thức thanh toán
- **storeOrder()**: Tạo đơn hàng

## Cart Service (`Webkul\Checkout\Cart`)

### Các phương thức chính:
```php
Cart::getCart()              // Lấy giỏ hàng hiện tại
Cart::addProduct($data)      // Thêm sản phẩm
Cart::updateItems($data)     // Cập nhật số lượng
Cart::removeItem($itemId)    // Xóa item
Cart::saveAddresses($data)   // Lưu địa chỉ
Cart::saveShippingMethod()   // Lưu shipping method
Cart::savePaymentMethod()    // Lưu payment method
Cart::collectTotals()        // Tính tổng tiền
Cart::hasError()             // Kiểm tra lỗi
Cart::deActivateCart()       // Vô hiệu hóa giỏ sau khi đặt hàng
```

## Luồng xử lý chi tiết

### 1. Thêm vào giỏ hàng
```
User click "Add to Cart"
    │
    ▼
POST /api/checkout/cart
    │
    ▼
CartController@store
    │
    ▼
Cart::addProduct($data)
    │
    ├── Validate product
    ├── Check stock
    ├── Create/Update cart item
    └── Return response
```

### 2. Checkout Process
```
GET /checkout/onepage
    │
    ▼
OnepageController@index
    │
    ├── Check cart exists
    ├── Check guest checkout allowed
    ├── Check downloadable items (require login)
    └── Return view
    
    │
    ▼
Vue Component loads
    │
    ▼
GET /api/checkout/onepage/summary
    │
    ▼
User fills address form
    │
    ▼
POST /api/checkout/onepage/addresses
    │
    ├── Save billing address
    ├── Save shipping address (if different)
    ├── If stockable items → Return shipping methods
    └── If digital only → Return payment methods
    
    │
    ▼
POST /api/checkout/onepage/shipping-methods (if applicable)
    │
    ▼
POST /api/checkout/onepage/payment-methods
    │
    ▼
POST /api/checkout/onepage/orders
    │
    ├── Validate order
    ├── Check payment redirect (PayPal, etc.)
    ├── Create order
    ├── Deactivate cart
    └── Redirect to success page
```

## Loại sản phẩm đặc biệt

### Downloadable Products
- Không cần shipping address
- Yêu cầu đăng nhập
- Cần gửi `links[]` khi add to cart
- Download link available sau khi thanh toán

### Virtual Products
- Không cần shipping
- Có thể guest checkout

## Payment Methods

### Cấu hình
```
Admin > Configuration > Sales > Payment Methods
```

### Các phương thức có sẵn:
- **Cash on Delivery (COD)**: Thanh toán khi nhận hàng
- **Money Transfer**: Chuyển khoản ngân hàng
- **PayPal Standard**: Thanh toán qua PayPal

## Validation

### Order Validation (`validateOrder()`)
1. Check customer suspended
2. Check customer active
3. Check minimum order amount
4. Check shipping address (for stockable items)
5. Check billing address
6. Check shipping method selected
7. Check payment method selected

## Events

```php
Event::dispatch('checkout.load.index')           // Khi load trang checkout
Event::dispatch('checkout.cart.add.before')      // Trước khi thêm vào giỏ
Event::dispatch('checkout.cart.add.after')       // Sau khi thêm vào giỏ
Event::dispatch('checkout.order.save.before')    // Trước khi tạo order
Event::dispatch('checkout.order.save.after')     // Sau khi tạo order
```

## Troubleshooting

### Lỗi thường gặp

1. **"Inactive item cannot be added to cart"**
   - Product status = 0 trong product_flat
   - Cần activate product trong Admin

2. **"Downloadable links are missing"**
   - Downloadable product cần có ít nhất 1 link
   - Cần gửi `links[]` trong request

3. **"Route not defined"**
   - Shop routes không được load
   - Kiểm tra ShopServiceProvider

4. **Guest checkout không được**
   - Kiểm tra config `sales.checkout.shopping_cart.allow_guest_checkout`
   - Downloadable products luôn yêu cầu login
