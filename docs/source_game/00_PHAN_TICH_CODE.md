# PHÂN TÍCH TRANG SOURCE-GAME

## 📋 TỔNG QUAN

Trang **Source Game** (`https://lamgame.localhost/source-game`) là trang hiển thị danh sách và chi tiết các source code game có thể tải về. Đây là tính năng bán sản phẩm downloadable (source code game).

---

## 🔗 ROUTES

### File: `routes/web.php`

```php
// Danh sách source game
Route::get('source-game', [LamGamePageController::class, 'sourceGame'])
    ->name('lamgame.source-game');

// Chi tiết source game
Route::get('source-game/{slug}', [LamGamePageController::class, 'sourceGameDetail'])
    ->name('lamgame.source-game.detail');
```

---

## 🎮 CONTROLLER

### File: `app/Http/Controllers/LamGamePageController.php`

#### 1. Method `sourceGame()` - Danh sách Source Game

**Chức năng:**
- Hiển thị danh sách các source code game
- Hỗ trợ tìm kiếm, sắp xếp, phân trang
- Lọc theo category (source-game, source-code-game)

**Input Parameters:**
- `search` - Từ khóa tìm kiếm
- `sort` - Sắp xếp: newest|price-asc|price-desc|name
- `perPage` - Số sản phẩm/trang (mặc định: 12, max: 60)

**Logic:**
1. Tìm category IDs từ slug aliases: `source-game`, `source-code-game`
2. Lấy tất cả category con (descendants) sử dụng nested set model (_lft, _rgt)
3. Query products với type = 'downloadable'
4. Filter theo categories và search keyword
5. Sort theo tham số
6. Paginate kết quả
7. Transform data cho view

**Output Data:**
```php
[
    'featuredSources' => [
        [
            'id' => product_id,
            'title' => 'Tên game',
            'description' => 'Mô tả ngắn',
            'full_description' => 'Mô tả đầy đủ',
            'category' => 'unity|mobile|web|modern',
            'engine' => 'Unity',
            'language' => 'C#',
            'downloads' => 1250,
            'rating' => 4.8,
            'preview_image' => 'url',
            'size' => '25 MB',
            'price' => 0,
            'updated' => '2024-01-15',
            'sku' => 'GAME-001',
            'downloadable_links' => [...],
            'url_key' => 'slug',
            'href' => 'route_url'
        ]
    ],
    'pagination' => [
        'current_page' => 1,
        'last_page' => 5,
        'per_page' => 12,
        'has_more' => true
    ]
]
```

**Fallback Data:**
- Nếu không có sản phẩm, hiển thị 3 sample games mẫu

---

#### 2. Method `sourceGameDetail($slug)` - Chi tiết Source Game

**Chức năng:**
- Hiển thị chi tiết 1 source code game
- Hỗ trợ tải về file
- Hiển thị sản phẩm liên quan

**Input:**
- `$slug` - URL key của product

**Logic:**
1. Tìm product theo url_key trong bảng `product_flat`
2. Nếu không tìm thấy, thử tìm theo product ID hoặc SKU
3. Nếu vẫn không có, hiển thị sample data
4. Load relationships: categories, images, downloadable_links, attribute_values
5. Parse attributes (engine, language, file_size, etc.)
6. Lấy related products cùng category
7. Transform data cho view

**Output Data:**
```php
[
    'sourceGame' => [
        'id' => 1,
        'title' => 'Space Shooter 2D',
        'slug' => 'space-shooter-2d',
        'description' => 'Mô tả ngắn',
        'full_description' => 'Mô tả đầy đủ HTML',
        'price' => 0,
        'is_free' => true,
        'sku' => 'GAME-001',
        'engine' => 'Unity',
        'language' => 'C#',
        'file_size' => '25 MB',
        'downloads_count' => 1250,
        'rating' => 4.8,
        'version' => '1.0',
        'last_updated' => '2024-01-15',
        'created_at' => '2024-01-01',
        'images' => [
            ['url' => '...', 'alt' => '...']
        ],
        'downloadable_links' => [
            [
                'title' => 'Source Code',
                'file_name' => 'game.zip',
                'downloads' => 100,
                'type' => 'file',
                'url' => '...'
            ]
        ],
        'video_demo_url' => 'youtube_url',
        'demo_url' => 'demo_url',
        'author_name' => 'Làm Game Team',
        'author_email' => 'contact@lamgame.localhost',
        'author_bio' => '...',
        'requirements' => 'Unity 2022.3 LTS',
        'features' => ['Feature 1', 'Feature 2'],
        'tags' => ['Unity', '2D'],
        'category_name' => 'Source Game'
    ],
    'relatedSources' => [
        [
            'title' => '...',
            'url' => '...',
            'image' => '...',
            'price' => 0,
            'rating' => 4.5
        ]
    ]
]
```

---

## 🗄️ DATABASE TABLES

### 1. **products**
```sql
- id (PK)
- type (downloadable)
- sku
- company_id (FK)
- created_at
- updated_at
```

### 2. **product_flat**
```sql
- id (PK)
- product_id (FK)
- locale (vi)
- name
- short_description
- description
- price
- url_key
- status
- visible_individually
```

### 3. **product_categories**
```sql
- product_id (FK)
- category_id (FK)
```

### 4. **categories**
```sql
- id (PK)
- _lft (nested set left)
- _rgt (nested set right)
- parent_id
```

### 5. **category_translations**
```sql
- id (PK)
- category_id (FK)
- locale
- name
- slug
```

### 6. **product_images**
```sql
- id (PK)
- product_id (FK)
- type (images)
- path
```

### 7. **product_downloadable_links**
```sql
- id (PK)
- product_id (FK)
- title
- file_name
- type
- url
- downloads
```

### 8. **product_attribute_values**
```sql
- id (PK)
- product_id (FK)
- attribute_id (FK)
- text_value
- integer_value
- date_value
```

### 9. **attributes**
```sql
- id (PK)
- code (game_engine, programming_language, file_size, etc.)
- type
```

### 10. **attribute_options**
```sql
- id (PK)
- attribute_id (FK)
```

### 11. **attribute_option_translations**
```sql
- id (PK)
- attribute_option_id (FK)
- locale
- label
```

---

## 📊 SQL QUERIES CHÍNH

### Query 1: Lấy danh sách source games
```sql
SELECT 
    p.id,
    p.sku,
    p.created_at,
    p.updated_at,
    pf.name,
    pf.description,
    pf.short_description,
    pf.price,
    pf.url_key
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
LEFT JOIN product_categories pc ON p.id = pc.product_id
WHERE p.type = 'downloadable'
  AND pc.category_id IN (category_ids)
  AND pf.status = 1
  AND pf.visible_individually = 1
  AND (
    pf.name LIKE '%search%' OR
    pf.short_description LIKE '%search%' OR
    pf.description LIKE '%search%'
  )
ORDER BY p.created_at DESC
LIMIT 12 OFFSET 0;
```

### Query 2: Lấy chi tiết source game
```sql
SELECT 
    p.*,
    pf.name,
    pf.description,
    pf.short_description,
    pf.price,
    pf.url_key
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
WHERE pf.url_key = 'slug'
  AND p.type = 'downloadable'
  AND pf.status = 1
  AND pf.visible_individually = 1
LIMIT 1;
```

### Query 3: Lấy images
```sql
SELECT path, type
FROM product_images
WHERE product_id = ?
  AND type = 'images'
ORDER BY id ASC;
```

### Query 4: Lấy downloadable links
```sql
SELECT title, file_name, downloads, type, url
FROM product_downloadable_links
WHERE product_id = ?;
```

### Query 5: Lấy attribute values
```sql
SELECT 
    a.code,
    pav.text_value,
    pav.integer_value,
    pav.date_value,
    aot.label as option_label
FROM product_attribute_values pav
JOIN attributes a ON pav.attribute_id = a.id
LEFT JOIN attribute_options ao ON pav.text_value = ao.id
LEFT JOIN attribute_option_translations aot 
    ON ao.id = aot.attribute_option_id 
    AND aot.locale = 'vi'
WHERE pav.product_id = ?
  AND a.code IN (
    'game_engine',
    'programming_language',
    'file_size',
    'downloads_count',
    'rating',
    'version',
    'video_demo_url',
    'demo_url',
    'author_name',
    'author_email',
    'author_bio',
    'requirements',
    'features'
  );
```

### Query 6: Lấy related products
```sql
SELECT 
    p.id,
    p.sku,
    pf.name,
    pf.price,
    pf.url_key
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
LEFT JOIN product_categories pc ON p.id = pc.product_id
WHERE p.type = 'downloadable'
  AND p.id != ?
  AND pc.category_id = ?
  AND pf.status = 1
  AND pf.visible_individually = 1
ORDER BY p.created_at DESC
LIMIT 3;
```

### Query 7: Lấy category với nested set
```sql
-- Lấy base category
SELECT id, _lft, _rgt
FROM categories c
JOIN category_translations ct ON c.id = ct.category_id
WHERE ct.slug IN ('source-game', 'source-code-game')
  AND ct.locale = 'vi';

-- Lấy tất cả descendants
SELECT id
FROM categories
WHERE _lft > ? AND _rgt < ?;
```

---

## 🎨 VIEW FILES

### 1. **Danh sách:** `resources/themes/emsaigon/views/products/source-game-view.blade.php`

**Sections:**
- Header với badges (category, engine, language)
- Stats (rating, downloads, file size)
- Product gallery với thumbnails
- Product details (price, description)
- Action buttons (Add to cart, Buy now)
- Tabs: Description, Features, Technical, Installation, Reviews
- Related products

**Vue Component:** `v-source-game-product`

### 2. **Content:** `resources/themes/emsaigon/views/products/source-game-content.blade.php`

**Reusable sections:**
- Header section
- Main product section
- Tabs section
- Styles
- Scripts

---

## ⚙️ CHỨC NĂNG CHÍNH

### 1. **Tìm kiếm & Lọc**
- Tìm theo keyword trong name, description
- Lọc theo category
- Sort: newest, price-asc, price-desc, name
- Pagination

### 2. **Hiển thị sản phẩm**
- Grid layout responsive
- Preview images với thumbnails
- Badges (category, engine, language)
- Stats (rating, downloads, size)
- Price display
- Quick view

### 3. **Chi tiết sản phẩm**
- Image gallery với zoom
- Full description với HTML
- Tabs: Description, Features, Technical, Installation, Reviews
- Downloadable links
- Related products
- Add to cart / Buy now

### 4. **Download**
- Downloadable links từ database
- Track số lượng downloads
- Support multiple files (source code, docs, assets)

### 5. **Attributes**
- Game Engine (Unity, Unreal, etc.)
- Programming Language (C#, C++, etc.)
- File Size
- Downloads Count
- Rating
- Version
- Video Demo URL
- Demo URL
- Author Info
- Requirements
- Features

---

## 🔄 FLOW HOẠT ĐỘNG

### Flow 1: Xem danh sách
```
User → /source-game
  ↓
LamGamePageController::sourceGame()
  ↓
Query categories (nested set)
  ↓
Query products (downloadable)
  ↓
Filter & Sort
  ↓
Paginate
  ↓
Transform data
  ↓
Return view với featuredSources
```

### Flow 2: Xem chi tiết
```
User → /source-game/{slug}
  ↓
LamGamePageController::sourceGameDetail($slug)
  ↓
Find product by url_key
  ↓
Load relationships (images, links, attributes)
  ↓
Parse attributes
  ↓
Get related products
  ↓
Transform data
  ↓
Return view với sourceGame detail
```

### Flow 3: Add to cart
```
User clicks "Thêm vào giỏ"
  ↓
Vue component: addToCart()
  ↓
POST /api/checkout/cart/store
  ↓
FormData: product_id, quantity, is_buy_now
  ↓
Update mini-cart
  ↓
Show success message
  ↓
Redirect if buy_now = 1
```

---

## 🎯 TÍNH NĂNG ĐẶC BIỆT

### 1. **Nested Set Model cho Categories**
- Sử dụng _lft và _rgt để query hierarchy
- Lấy tất cả descendants một lần query
- Hiệu quả cho category tree

### 2. **Fallback Data**
- Nếu không có products, hiển thị sample data
- Đảm bảo UI không bị trống

### 3. **Multi-locale Support**
- Tất cả text có locale = 'vi'
- Support đa ngôn ngữ

### 4. **Responsive Design**
- Mobile-first approach
- Grid layout tự động adjust
- Touch-friendly controls

### 5. **SEO Optimization**
- Meta tags đầy đủ
- Structured data (Schema.org)
- Open Graph tags
- Twitter Cards
- Breadcrumbs

### 6. **Performance**
- Lazy loading images
- Pagination để giảm load
- Eager loading relationships
- Query optimization

---

## 📦 DEPENDENCIES

### PHP Packages:
- Laravel Framework
- Bagisto Core
- Webkul Product Module
- Webkul Category Module

### JavaScript:
- Vue.js 3
- Axios
- Event Emitter

### CSS:
- Custom styles inline
- Responsive utilities
- Animation effects

---

## 🔐 SECURITY

### 1. **Input Validation**
- Validate search, sort, perPage parameters
- Sanitize user input
- XSS protection

### 2. **Access Control**
- Check product status
- Check visible_individually
- Verify product type

### 3. **File Download**
- Secure download links
- Track downloads
- Prevent unauthorized access

---

## 🐛 ERROR HANDLING

### 1. **Product Not Found**
- Return 404 hoặc sample data
- Graceful fallback

### 2. **Invalid Parameters**
- Default values cho sort, perPage
- Validate ranges

### 3. **Database Errors**
- Try-catch blocks
- Log errors
- User-friendly messages

---

## 📈 OPTIMIZATION TIPS

### 1. **Database**
- Index trên url_key, sku, type
- Index trên category_id
- Cache category tree

### 2. **Query**
- Eager load relationships
- Select only needed columns
- Use pagination

### 3. **Frontend**
- Lazy load images
- Minify CSS/JS
- Use CDN cho assets

### 4. **Caching**
- Cache category tree
- Cache product list
- Cache related products

---

## 🔧 CUSTOMIZATION

### Thêm attribute mới:
1. Tạo attribute trong admin
2. Thêm vào query trong controller
3. Hiển thị trong view

### Thêm filter mới:
1. Thêm parameter trong request
2. Thêm where clause trong query
3. Thêm UI filter trong view

### Thay đổi layout:
1. Override view file
2. Modify CSS trong @pushOnce('styles')
3. Update Vue component nếu cần

---

## 📝 NOTES

- Trang này sử dụng Bagisto product system
- Type = 'downloadable' cho source code
- Support multi-file downloads
- Có thể mở rộng thêm attributes
- UI/UX được tối ưu cho game developers
