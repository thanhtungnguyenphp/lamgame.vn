# DATABASE SCHEMA SELLER GAME

## 1. Tổng quan

Hệ thống Seller Game sử dụng 3 bảng chính:

| Bảng | Mô tả |
|------|-------|
| `source_game_sellers` | Thông tin seller |
| `source_game_earnings` | Doanh thu từ đơn hàng |
| `source_game_withdrawals` | Yêu cầu rút tiền |

## 2. ERD Diagram

```
┌─────────────────────┐       ┌─────────────────────┐
│     customers       │       │      products       │
├─────────────────────┤       ├─────────────────────┤
│ id (PK)             │       │ id (PK)             │
│ ...                 │       │ company_id (FK)     │──┐
└──────────┬──────────┘       │ ...                 │  │
           │                  └─────────────────────┘  │
           │ 1:1                                       │
           │                                           │
           ▼                                           │
┌─────────────────────────────────────────────────┐   │
│            source_game_sellers                   │   │
├─────────────────────────────────────────────────┤   │
│ id (PK)                                          │◄──┘
│ customer_id (FK) → customers.id                  │
│ shop_name, shop_slug, shop_description           │
│ shop_logo, shop_banner                           │
│ contact_email, contact_phone, website            │
│ business_type, tax_id                            │
│ bank_name, bank_account, bank_holder             │
│ status, verified, verified_at                    │
│ total_products, total_sales, total_revenue       │
│ rating_avg, rating_count                         │
└──────────┬──────────────────────────┬────────────┘
           │                          │
           │ 1:N                      │ 1:N
           │                          │
           ▼                          ▼
┌─────────────────────┐    ┌─────────────────────────┐
│ source_game_earnings│    │ source_game_withdrawals │
├─────────────────────┤    ├─────────────────────────┤
│ id (PK)             │    │ id (PK)                 │
│ seller_id (FK)      │    │ seller_id (FK)          │
│ order_id (FK)       │    │ amount                  │
│ order_item_id       │    │ status                  │
│ product_id (FK)     │    │ bank_name               │
│ order_amount        │    │ bank_account            │
│ platform_fee_percent│    │ bank_holder             │
│ platform_fee_amount │    │ note, admin_note        │
│ seller_amount       │    │ transaction_id          │
│ status              │    │ processed_at            │
│ completed_at        │    │ processed_by            │
└─────────────────────┘    └─────────────────────────┘
```

## 3. Chi tiết bảng

### 3.1 source_game_sellers

```sql
CREATE TABLE source_game_sellers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED UNIQUE NOT NULL,
    
    -- Shop info
    shop_name VARCHAR(255) NOT NULL,
    shop_slug VARCHAR(255) UNIQUE NOT NULL,
    shop_description TEXT NULL,
    shop_logo VARCHAR(255) NULL,
    shop_banner VARCHAR(255) NULL,
    
    -- Contact
    contact_email VARCHAR(255) NOT NULL,
    contact_phone VARCHAR(20) NULL,
    website VARCHAR(255) NULL,
    
    -- Business
    business_type ENUM('individual', 'company') DEFAULT 'individual',
    tax_id VARCHAR(50) NULL,
    bank_name VARCHAR(255) NULL,
    bank_account VARCHAR(100) NULL,
    bank_holder VARCHAR(255) NULL,
    
    -- Status
    status ENUM('pending', 'active', 'suspended', 'banned') DEFAULT 'pending',
    verified BOOLEAN DEFAULT FALSE,
    verified_at TIMESTAMP NULL,
    
    -- Stats
    total_products INT DEFAULT 0,
    total_sales INT DEFAULT 0,
    total_revenue DECIMAL(12, 2) DEFAULT 0,
    rating_avg DECIMAL(3, 2) DEFAULT 0,
    rating_count INT DEFAULT 0,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_shop_slug (shop_slug)
);
```

### 3.2 source_game_earnings

```sql
CREATE TABLE source_game_earnings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id BIGINT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NOT NULL,
    order_item_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    
    -- Amounts
    order_amount DECIMAL(12, 2) NOT NULL,
    platform_fee_percent DECIMAL(5, 2) DEFAULT 30.00,
    platform_fee_amount DECIMAL(12, 2) NOT NULL,
    seller_amount DECIMAL(12, 2) NOT NULL,
    
    -- Status
    status ENUM('pending', 'completed', 'refunded') DEFAULT 'pending',
    completed_at TIMESTAMP NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (seller_id) REFERENCES source_game_sellers(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    
    INDEX idx_seller_status (seller_id, status),
    INDEX idx_order_id (order_id)
);
```

### 3.3 source_game_withdrawals

```sql
CREATE TABLE source_game_withdrawals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id BIGINT UNSIGNED NOT NULL,
    
    -- Amount
    amount DECIMAL(12, 2) NOT NULL,
    
    -- Status
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    
    -- Bank info (snapshot)
    bank_name VARCHAR(255) NOT NULL,
    bank_account VARCHAR(100) NOT NULL,
    bank_holder VARCHAR(255) NOT NULL,
    
    -- Notes
    note TEXT NULL,
    admin_note TEXT NULL,
    
    -- Processing
    transaction_id VARCHAR(255) NULL,
    processed_at TIMESTAMP NULL,
    processed_by BIGINT UNSIGNED NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (seller_id) REFERENCES source_game_sellers(id) ON DELETE CASCADE,
    
    INDEX idx_seller_status (seller_id, status)
);
```

## 4. Migrations

### 4.1 Migration 1: Sellers

```
database/migrations/2025_12_16_174321_create_source_game_sellers_table.php
```

### 4.2 Migration 2: Earnings & Withdrawals

```
database/migrations/2025_12_23_104400_create_earnings_withdrawals_tables.php
```

## 5. Relationships

### 5.1 Customer ↔ Seller (1:1)

```php
// Customer model
public function seller()
{
    return $this->hasOne(SourceGameSeller::class, 'customer_id');
}

// SourceGameSeller model
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

### 5.2 Seller ↔ Products (1:N)

```php
// SourceGameSeller model
public function products()
{
    return $this->hasMany(Product::class, 'company_id', 'id');
}
```

### 5.3 Seller ↔ Earnings (1:N)

```php
// SourceGameSeller model
public function earnings()
{
    return $this->hasMany(SourceGameEarning::class, 'seller_id');
}

// SourceGameEarning model
public function seller()
{
    return $this->belongsTo(SourceGameSeller::class, 'seller_id');
}
```

### 5.4 Seller ↔ Withdrawals (1:N)

```php
// SourceGameSeller model
public function withdrawals()
{
    return $this->hasMany(SourceGameWithdrawal::class, 'seller_id');
}

// SourceGameWithdrawal model
public function seller()
{
    return $this->belongsTo(SourceGameSeller::class, 'seller_id');
}
```

## 6. Indexes

| Bảng | Index | Columns | Mục đích |
|------|-------|---------|----------|
| source_game_sellers | idx_status | status | Filter by status |
| source_game_sellers | idx_shop_slug | shop_slug | Lookup by slug |
| source_game_earnings | idx_seller_status | seller_id, status | Tính doanh thu |
| source_game_earnings | idx_order_id | order_id | Lookup by order |
| source_game_withdrawals | idx_seller_status | seller_id, status | Tính số dư |

## 7. Queries thường dùng

### 7.1 Tính số dư khả dụng

```sql
SELECT 
    (SELECT COALESCE(SUM(seller_amount), 0) 
     FROM source_game_earnings 
     WHERE seller_id = ? AND status = 'completed')
    -
    (SELECT COALESCE(SUM(amount), 0) 
     FROM source_game_withdrawals 
     WHERE seller_id = ? AND status IN ('completed', 'pending', 'processing'))
AS available_balance;
```

### 7.2 Thống kê doanh thu theo tháng

```sql
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(seller_amount) as revenue,
    COUNT(*) as orders_count
FROM source_game_earnings
WHERE seller_id = ? 
  AND status = 'completed'
  AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
GROUP BY month
ORDER BY month;
```

### 7.3 Top sản phẩm bán chạy

```sql
SELECT 
    p.id,
    pf.name,
    COUNT(e.id) as sales_count,
    SUM(e.seller_amount) as revenue
FROM products p
JOIN product_flat pf ON p.id = pf.product_id
LEFT JOIN source_game_earnings e ON p.id = e.product_id
WHERE p.company_id = ?
GROUP BY p.id, pf.name
ORDER BY sales_count DESC
LIMIT 10;
```
