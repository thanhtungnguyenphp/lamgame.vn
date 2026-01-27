# Nguyên tắc phát triển Seller Module - LamGame.vn

## 1. Nguyên tắc chung

### 1.1 Sử dụng Bagisto Repositories
**LUÔN** sử dụng Bagisto repositories thay vì Eloquent trực tiếp cho các operations phức tạp:

```php
// ✅ ĐÚNG
$this->productRepository->create([...]);
$this->productRepository->update([...], $id);

// ❌ SAI - Không lưu được attribute values
Product::create([...]);
$product->update([...]);
```

### 1.2 Two-Step Product Creation
Tạo product trong Bagisto **BẮT BUỘC** 2 bước:

```php
// Step 1: Create basic record
$product = $this->productRepository->create([
    'type' => 'downloadable',
    'sku' => $sku,
    'attribute_family_id' => 1,
]);

// Step 2: Update với attribute values
$this->productRepository->update([
    'name' => '...',
    'description' => '...',
    'price' => '...',
    // ...
], $product->id);
```

### 1.3 Seller ID Assignment
`seller_id` không có trong `$fillable`, phải dùng DB query trực tiếp:

```php
// ✅ ĐÚNG
DB::table('products')->where('id', $product->id)->update([
    'seller_id' => $seller->id,
]);

// ❌ SAI - Không hoạt động
$product->seller_id = $seller->id;
$product->save();
```

### 1.4 Ownership Check
Kiểm tra quyền sở hữu product:

```php
$productSellerId = DB::table('products')->where('id', $id)->value('seller_id');
if ($productSellerId != $seller->id) {
    abort(403, 'Unauthorized');
}
```

---

## 2. Data Format cho Bagisto

### 2.1 Images Upload
```php
$updateData['images'] = [];
foreach ($request->file('images') as $index => $image) {
    $updateData['images'][$index] = $image;  // UploadedFile instance
}
```

### 2.2 Downloadable Links
```php
$updateData['downloadable_links'] = [];
foreach ($request->file('source_files') as $index => $file) {
    $updateData['downloadable_links'][$index] = [
        $locale => [
            'title' => 'File title',  // Locale-specific
        ],
        'price' => 0,
        'type' => 'file',
        'file' => $file,  // UploadedFile instance
        'file_name' => $file->getClientOriginalName(),
        'downloads' => 0,
        'sort_order' => $index,
    ];
}
```

### 2.3 Categories
```php
$updateData['categories'] = [1, 2, 3];  // Array of category IDs
```

### 2.4 Locale & Channel
```php
$updateData['locale'] = core()->getCurrentLocale()->code ?? 'vi';
$updateData['channel'] = core()->getCurrentChannel()->code ?? 'default';
```

---

## 3. Error Handling

### 3.1 Transaction Wrapper
```php
DB::beginTransaction();
try {
    // ... operations
    DB::commit();
    return redirect()->with('success', '...');
} catch (\Exception $e) {
    DB::rollBack();
    \Log::error('Error: ' . $e->getMessage());
    return back()->withInput()->with('error', $e->getMessage());
}
```

### 3.2 Logging
```php
\Log::error('Seller product create error: ' . $e->getMessage(), [
    'seller_id' => $seller->id,
    'trace' => $e->getTraceAsString(),
]);
```

---

## 4. Validation Rules

### 4.1 Product Creation
```php
$request->validate([
    'name' => 'required|string|max:255',
    'short_description' => 'required|string|max:500',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'category_id' => 'required|exists:categories,id',
    'images.*' => 'nullable|image|max:5120',      // 5MB
    'source_files.*' => 'nullable|file|max:102400', // 100MB
]);
```

### 4.2 SKU Generation
```php
$sku = 'SG-' . strtoupper(Str::random(8));
```

### 4.3 URL Key Generation
```php
$urlKey = Str::slug($validated['name']) . '-' . $product->id;
```

---

## 5. Security

### 5.1 Authentication Check
```php
$seller = Auth::guard('customer')->user()->seller;

if (!$seller || !$seller->isActive()) {
    return redirect()->route('seller.pending');
}

if (!$seller->canUploadProduct()) {
    return back()->with('error', 'Bạn không có quyền upload sản phẩm');
}
```

### 5.2 Authorization Check
Mọi operation (edit, update, delete) phải kiểm tra seller ownership.

---

## 6. File Structure

```
app/Http/Controllers/
├── SellerProductController.php    # Main controller

resources/themes/emsaigon/views/seller/
├── layouts/
│   └── master.blade.php           # Seller layout
├── products/
│   ├── index.blade.php            # Product list
│   ├── create.blade.php           # Create form
│   └── edit.blade.php             # Edit form
└── dashboard.blade.php            # Dashboard

docs/
├── seller-product-guide.md        # User guide
├── seller-product-technical.md    # Technical docs
└── seller-development-rules.md    # This file
```

---

## 7. Checklist khi phát triển

- [ ] Sử dụng Bagisto repositories
- [ ] Two-step creation (create + update)
- [ ] Transaction wrapper
- [ ] Error logging
- [ ] Ownership check
- [ ] Validation rules
- [ ] Flash messages
- [ ] Test với các edge cases

---

## Changelog

| Ngày | Thay đổi |
|------|----------|
| 2026-01-18 | Tạo tài liệu nguyên tắc phát triển |
