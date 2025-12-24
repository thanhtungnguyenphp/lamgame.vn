# Quản lý Categories cho Seller Products

## Vấn đề

**Form create product:** Dropdown danh mục đang rỗng

## Nguyên nhân

### 1. Query filter quá strict
```php
// Code cũ - chỉ lấy categories có slug chứa "source"
$categories = $this->categoryRepository
    ->getModel()
    ->whereHas('translations', function($q) {
        $q->where('slug', 'like', '%source%');
    })
    ->with('translations')
    ->get();
```

**Vấn đề:**
- Nếu không có category nào có slug chứa "source" → rỗng
- Filter quá cụ thể, không linh hoạt

### 2. Database có thể chưa có categories

## Giải pháp

### 1. Sửa query để load tất cả categories

**File:** `app/Http/Controllers/SellerProductController.php`

```php
public function create()
{
    $seller = Auth::guard('customer')->user()->seller;
    
    if (!$seller || !$seller->canUploadProduct()) {
        return redirect()->route('seller.pending')
            ->with('error', 'Bạn không có quyền upload sản phẩm');
    }

    // Load all active categories
    $categories = $this->categoryRepository
        ->getModel()
        ->with('translations')
        ->where('status', 1) // Only active
        ->orderBy('position')
        ->get();

    return view('shop::seller.products.create', compact('categories', 'seller'));
}
```

### 2. Thêm debug info trong view

**File:** `resources/themes/emsaigon/views/seller/products/create.blade.php`

```blade
<select name="category_id" required>
    <option value="">-- Chọn danh mục --</option>
    @if($categories->count() > 0)
        @foreach($categories as $category)
            <option value="{{ $category->id }}">
                {{ $category->translations->first()->name ?? 'Category #' . $category->id }}
            </option>
        @endforeach
    @else
        <option value="" disabled>Chưa có danh mục nào</option>
    @endif
</select>
<small>Tổng: {{ $categories->count() }} danh mục</small>
```

## Quản lý Categories

### Truy cập Admin Panel

**URL:** `https://lamgame.localhost/admin`

**Login:** Admin credentials

### Tạo Category mới

**Path:** Admin → Catalog → Categories

**Steps:**
1. Click "Create Category"
2. Điền thông tin:
   - **Name:** Tên danh mục (VD: Source Game Unity)
   - **Slug:** URL-friendly (VD: source-game-unity)
   - **Status:** Active
   - **Position:** Thứ tự hiển thị
   - **Parent:** Danh mục cha (nếu có)
3. Click "Save"

### Cấu trúc Categories đề xuất

```
📁 Source Game
├── 📁 Unity
│   ├── 2D Games
│   ├── 3D Games
│   └── Mobile Games
├── 📁 Unreal Engine
│   ├── FPS Games
│   ├── RPG Games
│   └── Racing Games
├── 📁 Godot
├── 📁 HTML5 Games
└── 📁 Other Engines
```

### Database Schema

**Table:** `categories`

```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    position INT DEFAULT 0,
    status BOOLEAN DEFAULT 1,
    parent_id INT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (parent_id) REFERENCES categories(id)
);
```

**Table:** `category_translations`

```sql
CREATE TABLE category_translations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    locale VARCHAR(10) DEFAULT 'vi',
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY (category_id, locale)
);
```

## Seeder để tạo Categories mẫu

**File:** `database/seeders/CategorySeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Category\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'position' => 1,
                'status' => 1,
                'translations' => [
                    'vi' => [
                        'name' => 'Source Game Unity',
                        'slug' => 'source-game-unity',
                        'description' => 'Source code game Unity',
                    ],
                ],
            ],
            [
                'position' => 2,
                'status' => 1,
                'translations' => [
                    'vi' => [
                        'name' => 'Source Game Unreal',
                        'slug' => 'source-game-unreal',
                        'description' => 'Source code game Unreal Engine',
                    ],
                ],
            ],
            [
                'position' => 3,
                'status' => 1,
                'translations' => [
                    'vi' => [
                        'name' => 'Source Game HTML5',
                        'slug' => 'source-game-html5',
                        'description' => 'Source code game HTML5',
                    ],
                ],
            ],
            [
                'position' => 4,
                'status' => 1,
                'translations' => [
                    'vi' => [
                        'name' => 'Source Game Mobile',
                        'slug' => 'source-game-mobile',
                        'description' => 'Source code game Mobile',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'position' => $categoryData['position'],
                'status' => $categoryData['status'],
            ]);

            foreach ($categoryData['translations'] as $locale => $translation) {
                $category->translations()->create([
                    'locale' => $locale,
                    'name' => $translation['name'],
                    'slug' => $translation['slug'],
                    'description' => $translation['description'],
                ]);
            }
        }
    }
}
```

**Run seeder:**
```bash
php artisan db:seed --class=CategorySeeder
```

## API để lấy Categories

### Controller Method

```php
public function getCategories()
{
    $categories = $this->categoryRepository
        ->getModel()
        ->with('translations')
        ->where('status', 1)
        ->orderBy('position')
        ->get()
        ->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->translations->first()->name ?? 'N/A',
                'slug' => $category->translations->first()->slug ?? '',
                'parent_id' => $category->parent_id,
            ];
        });

    return response()->json($categories);
}
```

### Route

```php
Route::get('api/categories', [CategoryController::class, 'getCategories']);
```

## Troubleshooting

### 1. Dropdown vẫn rỗng sau khi sửa

**Check:**
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Check categories in DB
php artisan tinker
>>> \Webkul\Category\Models\Category::count()
>>> \Webkul\Category\Models\Category::with('translations')->get()
```

### 2. Categories không có translations

**Fix:**
```php
// In controller
$categories = $this->categoryRepository
    ->getModel()
    ->with(['translations' => function($q) {
        $q->where('locale', app()->getLocale());
    }])
    ->where('status', 1)
    ->get();
```

### 3. Muốn filter categories cho seller

**Option 1: By parent category**
```php
$parentCategory = Category::where('slug', 'source-game')->first();
$categories = Category::where('parent_id', $parentCategory->id)
    ->where('status', 1)
    ->get();
```

**Option 2: By custom field**
```php
// Add column: is_for_seller (boolean)
$categories = Category::where('status', 1)
    ->where('is_for_seller', true)
    ->get();
```

**Option 3: By tag/attribute**
```php
$categories = Category::whereHas('attributes', function($q) {
    $q->where('code', 'seller_allowed')->where('value', 1);
})->get();
```

## Best Practices

### 1. Caching
```php
use Illuminate\Support\Facades\Cache;

$categories = Cache::remember('seller_categories', 3600, function() {
    return $this->categoryRepository
        ->getModel()
        ->with('translations')
        ->where('status', 1)
        ->orderBy('position')
        ->get();
});
```

### 2. Eager Loading
```php
// Load relationships để tránh N+1
$categories = Category::with([
    'translations',
    'parent.translations',
    'children.translations'
])->where('status', 1)->get();
```

### 3. Validation
```php
// In ProductRequest
'category_id' => 'required|exists:categories,id,status,1'
```

## Summary

**Vấn đề:** Dropdown categories rỗng

**Nguyên nhân:**
1. Query filter quá strict (chỉ lấy slug có "source")
2. Database có thể chưa có categories

**Giải pháp:**
1. ✅ Sửa query load tất cả active categories
2. ✅ Thêm debug info trong view
3. ✅ Tạo categories qua Admin Panel
4. ✅ Hoặc run seeder để tạo categories mẫu

**Quản lý:**
- Admin Panel: `/admin` → Catalog → Categories
- Seeder: `php artisan db:seed --class=CategorySeeder`
- API: GET `/api/categories`
