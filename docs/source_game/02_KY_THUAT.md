# TÀI LIỆU KỸ THUẬT SOURCE GAME

## 🗄️ DATABASE SCHEMA

### 1. Products (Existing - Bagisto)
```sql
CREATE TABLE products (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) DEFAULT 'downloadable',
    sku VARCHAR(100) UNIQUE,
    company_id BIGINT NULL,
    created_by_admin_id BIGINT NULL,
    parent_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_sku (sku),
    INDEX idx_company (company_id)
);
```

### 2. Product Flat (Existing - Bagisto)
```sql
CREATE TABLE product_flat (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT,
    locale VARCHAR(10) DEFAULT 'vi',
    channel VARCHAR(50),
    name VARCHAR(255),
    short_description TEXT,
    description LONGTEXT,
    price DECIMAL(12,4),
    url_key VARCHAR(255),
    status TINYINT DEFAULT 1,
    visible_individually TINYINT DEFAULT 1,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    
    INDEX idx_product (product_id),
    INDEX idx_url_key (url_key),
    INDEX idx_status (status),
    FULLTEXT idx_search (name, short_description, description)
);
```

### 3. Product Downloadable Links (Existing)
```sql
CREATE TABLE product_downloadable_links (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT,
    title VARCHAR(255),
    price DECIMAL(12,4) DEFAULT 0,
    type VARCHAR(50), -- file, url
    file VARCHAR(255),
    file_name VARCHAR(255),
    url TEXT,
    sample_file VARCHAR(255),
    sample_url TEXT,
    sample_type VARCHAR(50),
    downloads INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_product (product_id)
);
```

### 4. Source Game Sellers (New - Cần tạo)
```sql
CREATE TABLE source_game_sellers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT UNIQUE,
    shop_name VARCHAR(255),
    shop_slug VARCHAR(255) UNIQUE,
    shop_description TEXT,
    shop_logo VARCHAR(255),
    shop_banner VARCHAR(255),
    
    -- Contact info
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    website VARCHAR(255),
    
    -- Business info
    business_type ENUM('individual', 'company') DEFAULT 'individual',
    tax_id VARCHAR(50),
    bank_name VARCHAR(255),
    bank_account VARCHAR(100),
    bank_holder VARCHAR(255),
    
    -- Status
    status ENUM('pending', 'active', 'suspended', 'banned') DEFAULT 'pending',
    verified TINYINT DEFAULT 0,
    verified_at TIMESTAMP NULL,
    
    -- Stats
    total_products INT DEFAULT 0,
    total_sales INT DEFAULT 0,
    total_revenue DECIMAL(12,2) DEFAULT 0,
    rating_avg DECIMAL(3,2) DEFAULT 0,
    rating_count INT DEFAULT 0,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_customer (customer_id),
    INDEX idx_slug (shop_slug),
    INDEX idx_status (status)
);
```

### 5. Source Game Versions (New)
```sql
CREATE TABLE source_game_versions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT,
    version VARCHAR(50),
    changelog TEXT,
    file_path VARCHAR(255),
    file_size BIGINT,
    downloads INT DEFAULT 0,
    is_current TINYINT DEFAULT 0,
    released_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_product (product_id),
    INDEX idx_version (product_id, version),
    INDEX idx_current (product_id, is_current)
);
```

### 6. Source Game Licenses (New)
```sql
CREATE TABLE source_game_licenses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE,
    name VARCHAR(255),
    description TEXT,
    terms LONGTEXT,
    
    -- Permissions
    commercial_use TINYINT DEFAULT 0,
    modification TINYINT DEFAULT 1,
    distribution TINYINT DEFAULT 0,
    private_use TINYINT DEFAULT 1,
    
    -- Conditions
    include_copyright TINYINT DEFAULT 1,
    include_license TINYINT DEFAULT 1,
    state_changes TINYINT DEFAULT 0,
    
    -- Limitations
    liability TINYINT DEFAULT 0,
    warranty TINYINT DEFAULT 0,
    
    sort_order INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_code (code)
);
```

### 7. Source Game Earnings (New)
```sql
CREATE TABLE source_game_earnings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    seller_id BIGINT,
    order_id BIGINT,
    order_item_id BIGINT,
    product_id BIGINT,
    
    -- Amounts
    product_price DECIMAL(12,2),
    commission_rate DECIMAL(5,2), -- percentage
    commission_amount DECIMAL(12,2),
    seller_amount DECIMAL(12,2),
    
    -- Status
    status ENUM('pending', 'approved', 'paid', 'refunded') DEFAULT 'pending',
    paid_at TIMESTAMP NULL,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_seller (seller_id),
    INDEX idx_order (order_id),
    INDEX idx_status (status),
    INDEX idx_paid (paid_at)
);
```

### 8. Source Game Withdrawals (New)
```sql
CREATE TABLE source_game_withdrawals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    seller_id BIGINT,
    
    -- Amount
    amount DECIMAL(12,2),
    fee DECIMAL(12,2) DEFAULT 0,
    net_amount DECIMAL(12,2),
    
    -- Payment method
    method ENUM('bank_transfer', 'paypal', 'momo') DEFAULT 'bank_transfer',
    payment_details JSON,
    
    -- Status
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    notes TEXT,
    processed_by BIGINT NULL,
    processed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_seller (seller_id),
    INDEX idx_status (status)
);
```

### 9. Product Attributes (Existing - Extended)
```sql
-- Attributes for source games
INSERT INTO attributes (code, type, validation) VALUES
('game_engine', 'select', NULL),
('programming_language', 'multiselect', NULL),
('file_size', 'text', NULL),
('version', 'text', NULL),
('unity_version', 'text', NULL),
('unreal_version', 'text', NULL),
('min_requirements', 'textarea', NULL),
('recommended_requirements', 'textarea', NULL),
('features', 'textarea', NULL),
('included_assets', 'textarea', NULL),
('documentation_url', 'text', 'url'),
('demo_url', 'text', 'url'),
('video_demo_url', 'text', 'url'),
('github_url', 'text', 'url'),
('support_email', 'text', 'email'),
('license_type', 'select', NULL),
('source_category', 'select', NULL), -- complete_game, template, system, asset
('difficulty_level', 'select', NULL), -- beginner, intermediate, advanced
('last_updated', 'date', NULL);
```

## 🔌 API ENDPOINTS

### Public APIs

#### 1. List Source Games
```
GET /api/source-games
Query params:
  - search: string
  - category: int
  - engine: string
  - language: string
  - price_min: decimal
  - price_max: decimal
  - sort: newest|popular|price_asc|price_desc
  - page: int
  - per_page: int (max 60)

Response:
{
  "data": [
    {
      "id": 1,
      "name": "Space Shooter 2D",
      "slug": "space-shooter-2d",
      "price": 0,
      "thumbnail": "url",
      "rating": 4.8,
      "downloads": 1250,
      "seller": {
        "id": 1,
        "name": "GameDev Studio",
        "slug": "gamedev-studio"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 12,
    "total": 120
  }
}
```

#### 2. Get Source Game Detail
```
GET /api/source-games/{slug}

Response:
{
  "id": 1,
  "name": "Space Shooter 2D",
  "slug": "space-shooter-2d",
  "description": "...",
  "price": 0,
  "images": [...],
  "downloadable_links": [...],
  "attributes": {...},
  "seller": {...},
  "reviews": {...},
  "related": [...]
}
```

### Seller APIs (Authenticated)

#### 3. Create Source Game
```
POST /api/seller/source-games
Headers:
  Authorization: Bearer {token}

Body (multipart/form-data):
  - name: string
  - description: text
  - price: decimal
  - category_id: int
  - images[]: files
  - source_files[]: files
  - attributes: json

Response:
{
  "success": true,
  "data": {
    "id": 1,
    "status": "pending"
  }
}
```

#### 4. Update Source Game
```
PUT /api/seller/source-games/{id}
```

#### 5. Get Seller Dashboard
```
GET /api/seller/dashboard

Response:
{
  "stats": {
    "total_products": 10,
    "total_sales": 150,
    "total_revenue": 15000000,
    "pending_earnings": 5000000,
    "available_balance": 10000000
  },
  "recent_sales": [...],
  "top_products": [...]
}
```

#### 6. Request Withdrawal
```
POST /api/seller/withdrawals
Body:
  - amount: decimal
  - method: string
  - payment_details: json
```

### Admin APIs

#### 7. Review Submissions
```
GET /api/admin/source-games/pending
POST /api/admin/source-games/{id}/approve
POST /api/admin/source-games/{id}/reject
```

## 🔄 WORKFLOWS

### 1. Upload Source Game Workflow
```
Seller uploads → Validation → Virus scan → Create product
    ↓
Set status = 'pending'
    ↓
Notify admin
    ↓
Admin review
    ↓
Approve/Reject
    ↓
If approved: status = 'active', notify seller
If rejected: status = 'rejected', notify seller with reason
```

### 2. Purchase Workflow
```
Buyer adds to cart → Checkout → Payment
    ↓
Payment success
    ↓
Create order
    ↓
Create earning record (status = 'pending')
    ↓
Generate download link (expires in 24h)
    ↓
Send email with download link
    ↓
After 7 days: earning status = 'approved'
    ↓
After 30 days: earning status = 'paid'
```

### 3. Withdrawal Workflow
```
Seller requests withdrawal
    ↓
Check minimum amount (100,000 VND)
    ↓
Check available balance
    ↓
Create withdrawal request (status = 'pending')
    ↓
Admin review
    ↓
Process payment
    ↓
Update status = 'completed'
    ↓
Notify seller
```

## 🔐 SECURITY MEASURES

### File Upload Security
```php
// Validation rules
$rules = [
    'source_files.*' => [
        'required',
        'file',
        'max:524288', // 512MB
        'mimes:zip,rar,7z',
    ],
    'images.*' => [
        'required',
        'image',
        'max:5120', // 5MB
        'mimes:jpg,jpeg,png,webp',
    ]
];

// Virus scan
$scanner = new ClamAV();
if (!$scanner->scan($file)) {
    throw new Exception('File contains malware');
}

// Store with unique name
$filename = Str::uuid() . '.' . $file->extension();
$path = Storage::disk('s3')->putFileAs('source-games', $file, $filename);
```

### Download Link Security
```php
// Generate signed URL with expiry
$url = Storage::disk('s3')->temporaryUrl(
    $path,
    now()->addHours(24),
    [
        'ResponseContentDisposition' => 'attachment; filename="' . $filename . '"',
    ]
);

// Track download
DownloadLog::create([
    'product_id' => $product->id,
    'customer_id' => auth()->id(),
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

### Payment Security
```php
// Verify webhook signature
$signature = request()->header('Stripe-Signature');
$event = \Stripe\Webhook::constructEvent(
    request()->getContent(),
    $signature,
    config('services.stripe.webhook_secret')
);

// Process only verified events
if ($event->type === 'payment_intent.succeeded') {
    // Process order
}
```

## 📊 PERFORMANCE OPTIMIZATION

### Database Indexing
```sql
-- Composite indexes for common queries
CREATE INDEX idx_product_search ON product_flat(status, visible_individually, locale);
CREATE INDEX idx_product_category ON product_categories(category_id, product_id);
CREATE INDEX idx_earnings_seller_status ON source_game_earnings(seller_id, status);

-- Full-text search
ALTER TABLE product_flat ADD FULLTEXT idx_fulltext(name, short_description, description);
```

### Caching Strategy
```php
// Cache category tree (1 hour)
$categories = Cache::remember('source_game_categories', 3600, function () {
    return Category::with('translations')->get();
});

// Cache product list (5 minutes)
$cacheKey = 'source_games:' . md5(json_encode($filters));
$products = Cache::remember($cacheKey, 300, function () use ($filters) {
    return Product::filter($filters)->paginate(12);
});

// Cache product detail (10 minutes)
$product = Cache::remember('source_game:' . $slug, 600, function () use ($slug) {
    return Product::with(['images', 'downloadable_links', 'seller'])
        ->where('url_key', $slug)
        ->firstOrFail();
});
```

### Query Optimization
```php
// Eager loading
$products = Product::with([
    'images' => fn($q) => $q->where('type', 'images')->limit(1),
    'categories.translations',
    'attribute_values.attribute',
    'seller'
])->get();

// Select only needed columns
$products = Product::select([
    'id', 'sku', 'type', 'created_at'
])->get();

// Chunk large datasets
Product::chunk(100, function ($products) {
    foreach ($products as $product) {
        // Process
    }
});
```

## 🧪 TESTING

### Unit Tests
```php
// tests/Unit/SourceGameTest.php
public function test_can_create_source_game()
{
    $seller = Seller::factory()->create();
    $data = [
        'name' => 'Test Game',
        'price' => 100000,
        'description' => 'Test description',
    ];
    
    $product = $seller->createSourceGame($data);
    
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'type' => 'downloadable',
    ]);
}
```

### Feature Tests
```php
// tests/Feature/SourceGamePurchaseTest.php
public function test_can_purchase_source_game()
{
    $user = User::factory()->create();
    $product = Product::factory()->sourceGame()->create();
    
    $response = $this->actingAs($user)
        ->post('/checkout/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    
    $response->assertStatus(200);
    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'customer_id' => $user->id,
    ]);
}
```

## 📈 MONITORING

### Metrics to Track
```php
// Application metrics
Metric::track('source_game.created', 1);
Metric::track('source_game.purchased', 1, ['price' => $price]);
Metric::track('source_game.downloaded', 1);
Metric::track('withdrawal.requested', 1, ['amount' => $amount]);

// Performance metrics
Metric::timing('api.source_games.list', $duration);
Metric::timing('download.generate_link', $duration);

// Error tracking
if ($exception) {
    Sentry::captureException($exception);
}
```

### Health Checks
```php
// routes/api.php
Route::get('/health', function () {
    return [
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'ok' : 'error',
        'cache' => Cache::has('health_check') ? 'ok' : 'error',
        'storage' => Storage::disk('s3')->exists('health_check.txt') ? 'ok' : 'error',
    ];
});
```
