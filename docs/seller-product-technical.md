# Tài liệu kỹ thuật: Seller Product Creation

## Mục lục
1. [Kiến trúc hệ thống](#1-kiến-trúc-hệ-thống)
2. [Database Schema](#2-database-schema)
3. [Flow xử lý](#3-flow-xử-lý)
4. [API & Controllers](#4-api--controllers)
5. [Bagisto Integration](#5-bagisto-integration)
6. [Troubleshooting](#6-troubleshooting)

---

## 1. Kiến trúc hệ thống

### 1.1 Components
```
┌─────────────────────────────────────────────────────────────┐
│                        Frontend                              │
│  resources/themes/emsaigon/views/seller/products/create.blade.php │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                         Routes                               │
│  routes/web.php → Route::resource('products', SellerProductController) │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       Controller                             │
│  app/Http/Controllers/SellerProductController.php           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Bagisto Repositories                      │
│  - ProductRepository                                         │
│  - ProductImageRepository                                    │
│  - ProductDownloadableLinkRepository                        │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Bagisto Type Classes                      │
│  packages/Webkul/Product/src/Type/Downloadable.php          │
│  packages/Webkul/Product/src/Type/AbstractType.php          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                        Database                              │
│  - products                                                  │
│  - product_attribute_values                                  │
│  - product_images                                            │
│  - product_downloadable_links                               │
│  - product_categories                                        │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Files chính
| File | Mô tả |
|------|-------|
| `app/Http/Controllers/SellerProductController.php` | Controller xử lý CRUD |
| `app/Models/Product.php` | Extended Product model |
| `resources/themes/emsaigon/views/seller/products/create.blade.php` | Form tạo sản phẩm |
| `routes/web.php` | Route definitions |

---

## 2. Database Schema

### 2.1 Bảng `products`
```sql
-- Columns liên quan đến seller
seller_id       BIGINT UNSIGNED NULL  -- FK to source_game_sellers.id
status          TINYINT DEFAULT 0     -- 0=draft, 1=active
type            VARCHAR(255)          -- 'downloadable'
sku             VARCHAR(255) UNIQUE
attribute_family_id  INT UNSIGNED
```

### 2.2 Bảng `product_attribute_values`
Lưu các attribute values (name, description, price, etc.)
```sql
product_id      INT UNSIGNED
attribute_id    INT UNSIGNED
channel         VARCHAR(255) NULL
locale          VARCHAR(255) NULL
text_value      TEXT NULL
integer_value   INT NULL
float_value     DECIMAL NULL
-- ... other value columns
```

### 2.3 Bảng `product_downloadable_links`
```sql
product_id      INT UNSIGNED
title           VARCHAR(255)
type            VARCHAR(255)  -- 'file' or 'url'
file            VARCHAR(255)  -- path to file
file_name       VARCHAR(255)
downloads       INT DEFAULT 0
sort_order      INT DEFAULT 0
```

### 2.4 Migration cho seller_id
```php
// database/migrations/2025_12_17_140948_add_seller_id_to_products_table.php
Schema::table('products', function (Blueprint $table) {
    $table->unsignedBigInteger('seller_id')->nullable()->after('parent_id');
    $table->foreign('seller_id')->references('id')->on('source_game_sellers')->onDelete('set null');
    $table->index('seller_id');
});
```

---

## 3. Flow xử lý

### 3.1 Create Product Flow
```
1. User submit form
       │
       ▼
2. Controller validates input
       │
       ▼
3. ProductRepository->create()
   - Tạo record trong bảng products
   - Chỉ lưu: type, sku, attribute_family_id
   - KHÔNG lưu attribute values
       │
       ▼
4. ProductRepository->update()
   - Lưu attribute values (name, description, price...)
   - Upload images via ProductImageRepository
   - Save downloadable links via ProductDownloadableLinkRepository
   - Sync categories
       │
       ▼
5. Update seller_id trực tiếp (không qua fillable)
   DB::table('products')->where('id', $id)->update(['seller_id' => $seller->id])
       │
       ▼
6. Redirect với success message
```

### 3.2 Tại sao cần 2 bước (create + update)?

Bagisto sử dụng **Type Classes** để xử lý product:
- `AbstractType::create()` - Chỉ tạo record cơ bản
- `AbstractType::update()` - Xử lý attribute values, images, links, categories

```php
// packages/Webkul/Product/src/Type/AbstractType.php

public function create(array $data)
{
    $product = $this->productRepository->getModel()->create($data);
    $product->channels()->sync(core()->getDefaultChannel()->id);
    return $product;  // Chỉ tạo record, không lưu attributes
}

public function update(array $data, $id, $attributes = [])
{
    // ... lưu attribute values
    $this->attributeValueRepository->saveValues($data, $product, ...);
    // ... sync categories
    $product->categories()->sync($data['categories']);
    // ... upload images
    $this->productImageRepository->upload($data, $product, 'images');
    // ...
}
```

---

## 4. API & Controllers

### 4.1 Routes
```php
// routes/web.php
Route::prefix('seller')->name('seller.')->middleware('theme')->group(function () {
    Route::middleware('seller')->group(function () {
        Route::resource('products', SellerProductController::class);
    });
});
```

### 4.2 Controller Methods

#### `store(Request $request)`
```php
public function store(Request $request)
{
    // 1. Validate
    $validated = $request->validate([...]);
    
    // 2. Create product (basic record)
    $product = $this->productRepository->create([
        'type' => 'downloadable',
        'sku' => 'SG-' . strtoupper(Str::random(8)),
        'attribute_family_id' => 1,
    ]);
    
    // 3. Prepare update data
    $updateData = [
        'channel' => $channel,
        'locale' => $locale,
        'name' => $validated['name'],
        'url_key' => $urlKey,
        // ... other attributes
        'categories' => [$validated['category_id']],
        'images' => $request->file('images'),
        'downloadable_links' => [...],
    ];
    
    // 4. Update with attribute values
    $this->productRepository->update($updateData, $product->id);
    
    // 5. Set seller_id
    DB::table('products')->where('id', $product->id)->update([
        'seller_id' => $seller->id,
    ]);
}
```

### 4.3 Downloadable Links Format
```php
$updateData['downloadable_links'] = [
    0 => [
        'vi' => ['title' => 'File name'],  // Locale-specific title
        'price' => 0,
        'type' => 'file',
        'file' => $uploadedFile,  // UploadedFile instance
        'file_name' => 'original-name.zip',
        'downloads' => 0,
        'sort_order' => 0,
    ],
];
```

---

## 5. Bagisto Integration

### 5.1 Product Type: Downloadable
```php
// packages/Webkul/Product/src/Type/Downloadable.php
class Downloadable extends AbstractType
{
    protected $skipAttributes = [
        'length', 'width', 'height', 'weight',
        'depth', 'manage_stock', 'guest_checkout',
    ];
    
    protected $isStockable = false;
}
```

### 5.2 Attribute Family
- ID: 1 (Default)
- Cần có các attributes: name, description, short_description, price, url_key, status, visible_individually

### 5.3 Repositories sử dụng
| Repository | Mục đích |
|------------|----------|
| `ProductRepository` | CRUD products |
| `ProductImageRepository` | Upload/manage images |
| `ProductDownloadableLinkRepository` | Manage download files |
| `CategoryRepository` | Load categories |

---

## 6. Troubleshooting

### 6.1 Product tạo nhưng không có name/description
**Nguyên nhân:** Chỉ gọi `create()` mà không gọi `update()`
**Fix:** Phải gọi `update()` sau `create()` để lưu attribute values

### 6.2 seller_id không được lưu
**Nguyên nhân:** `seller_id` không có trong `$fillable` của Product model
**Fix:** Dùng `DB::table()->update()` trực tiếp

### 6.3 Images không upload
**Nguyên nhân:** Sai format data cho images
**Fix:** Truyền array của UploadedFile objects:
```php
$updateData['images'] = [
    0 => $request->file('images')[0],
    1 => $request->file('images')[1],
];
```

### 6.4 Downloadable links không tạo
**Nguyên nhân:** Sai format data
**Fix:** Phải có locale key cho title:
```php
$updateData['downloadable_links'][0] = [
    'vi' => ['title' => 'Title'],  // ← Locale key required
    'type' => 'file',
    'file' => $uploadedFile,
    // ...
];
```

### 6.5 Categories không sync
**Nguyên nhân:** Truyền sai key
**Fix:** Dùng `categories` (plural) với array:
```php
$updateData['categories'] = [1, 2, 3];  // Array of category IDs
```

---

## Changelog

| Ngày | Thay đổi |
|------|----------|
| 2026-01-18 | Fix SellerProductController để sử dụng đúng Bagisto flow |
| 2026-01-18 | Đổi từ company_id sang seller_id |
| 2026-01-18 | Tạo tài liệu kỹ thuật |
