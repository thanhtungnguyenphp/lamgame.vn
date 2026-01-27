# Hướng dẫn Fix Lỗi Checkout

## Lỗi đã gặp và cách fix

### 1. Route [shop.home.index] not defined

**Nguyên nhân:** Route `/` trong `routes/web.php` override route của Shop package.

**Fix:** Override view trong theme và thay `route('shop.home.index')` bằng `route('home')` hoặc `url('/')`.

```bash
# Copy view to theme
cp packages/Webkul/Shop/src/Resources/views/checkout/cart/index.blade.php \
   resources/themes/emsaigon/views/checkout/cart/index.blade.php

# Replace route
sed -i '' "s/route('shop.home.index')/route('home')/g" \
   resources/themes/emsaigon/views/checkout/cart/index.blade.php
```

### 2. Route [shop.customer.session.index] not defined

**Nguyên nhân:** View được compile trước khi routes load hoặc route bị conflict.

**Fix:** Override component và thay bằng URL trực tiếp:

```bash
# Copy component to theme
cp packages/Webkul/Shop/src/Resources/views/components/products/card.blade.php \
   resources/themes/emsaigon/views/components/products/card.blade.php

# Replace route with URL
sed -i '' "s|route('shop.customer.session.index')|url('/customer/login')|g" \
   resources/themes/emsaigon/views/components/products/card.blade.php
```

### 3. Inactive item cannot be added to cart

**Nguyên nhân:** Product có `status = 0` trong bảng `product_flat`.

**Fix:**
```sql
UPDATE product_flat SET status = 1 WHERE product_id = {ID};
```

Hoặc qua Admin Panel: Catalog > Products > Edit > Enable product.

### 4. Downloadable links are missing for this product

**Nguyên nhân:** 
1. Product type là `downloadable` nhưng không có links
2. Request không gửi `links[]` parameter

**Fix Database:**
```sql
-- Thêm downloadable link
INSERT INTO product_downloadable_links 
(product_id, url, type, price, downloads, sort_order) 
VALUES ({PRODUCT_ID}, 'https://example.com/file.zip', 'url', 0, 0, 0);

-- Thêm translation
INSERT INTO product_downloadable_link_translations 
(product_downloadable_link_id, locale, title) 
VALUES (LAST_INSERT_ID(), 'vi', 'Source Code');
```

**Fix Frontend:**
```html
<!-- Thêm links vào form -->
@php
    $links = DB::table('product_downloadable_links')
        ->where('product_id', $product->id)
        ->pluck('id');
@endphp

@foreach($links as $linkId)
    <input type="hidden" name="links[]" value="{{ $linkId }}">
@endforeach
```

### 5. Product is_saleable = false

**Checklist:**
1. `product_flat.status = 1`
2. `product_attribute_values.boolean_value = 1` (cho attribute `status`)
3. Có price > 0
4. Downloadable: có ít nhất 1 link
5. Simple/Configurable: có inventory

**Debug route:**
```php
// Thêm vào routes/web.php tạm thời
Route::get('/debug-product/{id}', function($id) {
    $product = \Webkul\Product\Models\Product::find($id);
    return response()->json([
        'type' => $product->type,
        'is_saleable' => $product->getTypeInstance()->isSaleable(),
        'flat_status' => DB::table('product_flat')->where('product_id', $id)->value('status'),
        'links_count' => $product->downloadable_links->count(),
    ]);
});
```

## Commands hữu ích

### Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Enable cart config
```bash
php artisan tinker
>>> DB::table('core_config')->updateOrInsert(
    ['code' => 'sales.checkout.shopping_cart.cart_page'],
    ['value' => '1']
);
>>> DB::table('core_config')->updateOrInsert(
    ['code' => 'catalog.products.storefront.buy_now_button_display'],
    ['value' => '1']
);
```

### Activate product
```bash
php artisan tinker
>>> DB::table('product_flat')->where('product_id', 48)->update(['status' => 1]);
>>> $statusAttrId = DB::table('attributes')->where('code', 'status')->value('id');
>>> DB::table('product_attribute_values')->updateOrInsert(
    ['product_id' => 48, 'attribute_id' => $statusAttrId],
    ['boolean_value' => 1]
);
```

## Testing Checkout Flow

### 1. Test Add to Cart
```bash
curl -X POST https://lamgame.localhost/api/checkout/cart \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {token}" \
  -d '{"product_id": 48, "quantity": 1, "links": [1]}'
```

### 2. Test Checkout Summary
```bash
curl https://lamgame.localhost/api/checkout/onepage/summary \
  -H "Accept: application/json"
```

### 3. Manual Test Steps
1. Truy cập `/source-game/{slug}`
2. Click "Thêm vào giỏ hàng"
3. Truy cập `/checkout/cart`
4. Click "Proceed to Checkout"
5. Điền địa chỉ
6. Chọn payment method
7. Click "Place Order"
8. Verify success page
