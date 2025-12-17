# KẾ HOẠCH PHÁT TRIỂN SOURCE GAME

## 🎯 PHASE 1: FOUNDATION (Hoàn thành)

### ✅ Đã có
- [x] Trang danh sách source games
- [x] Trang chi tiết source game
- [x] Tích hợp Bagisto product system
- [x] Upload và quản lý files
- [x] Cart & checkout flow
- [x] Download system cơ bản
- [x] Responsive design

### 📊 Đánh giá hiện trạng
**Ưu điểm:**
- UI/UX đẹp, chuyên nghiệp
- Tận dụng tốt Bagisto core
- Responsive tốt
- SEO friendly

**Hạn chế:**
- Chưa có seller system
- Chưa có approval workflow
- Chưa có revenue sharing
- Chưa có version control
- Chưa có analytics

---

## 🚀 PHASE 2: SELLER SYSTEM (2-3 tháng)

### Sprint 1: Seller Registration (2 tuần)

#### Tasks:
1. **Database Migration**
```bash
php artisan make:migration create_source_game_sellers_table
php artisan make:migration create_source_game_earnings_table
php artisan make:migration create_source_game_withdrawals_table
```

2. **Models & Relationships**
```php
// app/Models/SourceGameSeller.php
class SourceGameSeller extends Model
{
    public function customer() { return $this->belongsTo(Customer::class); }
    public function products() { return $this->hasMany(Product::class, 'company_id'); }
    public function earnings() { return $this->hasMany(SourceGameEarning::class); }
    public function withdrawals() { return $this->hasMany(SourceGameWithdrawal::class); }
}
```

3. **Seller Registration Form**
- Trang đăng ký seller: `/seller/register`
- Form fields: shop_name, description, contact info, bank details
- Upload shop logo & banner
- Terms & conditions acceptance

4. **Admin Approval**
- Admin panel: Review seller applications
- Approve/Reject with notes
- Email notifications

**Deliverables:**
- [ ] Seller registration page
- [ ] Admin approval interface
- [ ] Email templates
- [ ] Unit tests

---

### Sprint 2: Product Upload (3 tuần)

#### Tasks:
1. **Upload Interface**
```
/seller/products/create
- Product info form
- Multiple image upload (drag & drop)
- Source file upload (with progress bar)
- Category selection
- Attribute fields (engine, language, etc.)
- Price setting
- License selection
```

2. **File Processing**
```php
// app/Services/SourceGameUploadService.php
class SourceGameUploadService
{
    public function processUpload($files)
    {
        // Validate files
        // Virus scan
        // Extract metadata
        // Generate thumbnails
        // Upload to S3
        // Create product record
    }
}
```

3. **Validation & Security**
- File type validation
- Size limits (max 512MB)
- Virus scanning (ClamAV)
- Malware detection
- Content policy check

4. **Preview System**
- Auto-generate screenshots from Unity/Unreal projects
- Video demo upload
- Live demo iframe (optional)

**Deliverables:**
- [ ] Upload form with validation
- [ ] File processing service
- [ ] Security scanning
- [ ] Preview generation
- [ ] Integration tests

---

### Sprint 3: Seller Dashboard (2 tuần)

#### Features:
1. **Overview Stats**
- Total products
- Total sales
- Total revenue
- Pending earnings
- Available balance

2. **Product Management**
- List all products
- Edit product
- Update version
- View analytics per product

3. **Sales History**
- Recent orders
- Sales chart (daily/weekly/monthly)
- Top selling products
- Customer reviews

4. **Earnings & Withdrawals**
- Earnings breakdown
- Withdrawal history
- Request new withdrawal
- Transaction logs

**UI Components:**
```
/seller/dashboard
├── /overview
├── /products
│   ├── /list
│   ├── /create
│   ├── /edit/{id}
│   └── /analytics/{id}
├── /sales
├── /earnings
└── /withdrawals
```

**Deliverables:**
- [ ] Dashboard layout
- [ ] Stats widgets
- [ ] Charts (Chart.js)
- [ ] Product CRUD
- [ ] Withdrawal form

---

### Sprint 4: Revenue Sharing (2 tuần)

#### Implementation:
1. **Commission System**
```php
// config/source_game.php
return [
    'commission' => [
        'default_rate' => 30, // 30% platform fee
        'tiers' => [
            'bronze' => ['min_sales' => 0, 'rate' => 30],
            'silver' => ['min_sales' => 50, 'rate' => 25],
            'gold' => ['min_sales' => 100, 'rate' => 20],
            'platinum' => ['min_sales' => 500, 'rate' => 15],
        ]
    ],
    'withdrawal' => [
        'minimum_amount' => 100000, // 100k VND
        'fee' => 0, // No fee
        'processing_days' => 7,
    ]
];
```

2. **Earning Calculation**
```php
// app/Services/EarningService.php
public function calculateEarning($order, $orderItem)
{
    $seller = $orderItem->product->seller;
    $rate = $this->getCommissionRate($seller);
    
    $productPrice = $orderItem->total;
    $commission = $productPrice * ($rate / 100);
    $sellerAmount = $productPrice - $commission;
    
    SourceGameEarning::create([
        'seller_id' => $seller->id,
        'order_id' => $order->id,
        'order_item_id' => $orderItem->id,
        'product_id' => $orderItem->product_id,
        'product_price' => $productPrice,
        'commission_rate' => $rate,
        'commission_amount' => $commission,
        'seller_amount' => $sellerAmount,
        'status' => 'pending',
    ]);
}
```

3. **Withdrawal Processing**
- Seller requests withdrawal
- Admin reviews and approves
- Process payment (bank transfer/PayPal/Momo)
- Update status and notify seller

**Deliverables:**
- [ ] Commission calculation
- [ ] Earning tracking
- [ ] Withdrawal workflow
- [ ] Payment integration
- [ ] Admin panel for payouts

---

## 🎨 PHASE 3: ADVANCED FEATURES (2-3 tháng)

### Sprint 5: Version Control (2 tuần)

#### Features:
1. **Version Management**
- Upload new version
- Version changelog
- Download specific version
- Auto-update notification

2. **Database Schema**
```sql
CREATE TABLE source_game_versions (
    id BIGINT PRIMARY KEY,
    product_id BIGINT,
    version VARCHAR(50),
    changelog TEXT,
    file_path VARCHAR(255),
    file_size BIGINT,
    downloads INT DEFAULT 0,
    is_current TINYINT DEFAULT 0,
    released_at TIMESTAMP
);
```

3. **UI Components**
- Version history list
- Changelog display
- Download version selector
- Update notification banner

**Deliverables:**
- [ ] Version CRUD
- [ ] Changelog editor
- [ ] Version selector UI
- [ ] Update notifications

---

### Sprint 6: License Management (2 tuần)

#### Features:
1. **License Types**
- Personal Use Only
- Commercial Use Allowed
- Open Source (MIT, GPL, etc.)
- Custom License

2. **License Generator**
```php
// Generate unique license key per purchase
$licenseKey = Str::uuid();
License::create([
    'key' => $licenseKey,
    'product_id' => $product->id,
    'customer_id' => $customer->id,
    'order_id' => $order->id,
    'type' => $product->license_type,
    'expires_at' => null, // or specific date
]);
```

3. **License Verification API**
```
POST /api/verify-license
Body: { "key": "xxx", "product_id": 1 }
Response: { "valid": true, "type": "commercial", "expires_at": null }
```

**Deliverables:**
- [ ] License types management
- [ ] License key generation
- [ ] Verification API
- [ ] License display in product

---

### Sprint 7: Enhanced Preview (2 tuần)

#### Features:
1. **Interactive Demo**
- Embed Unity WebGL build
- Embed Unreal HTML5 build
- Video player with chapters
- Screenshot gallery with lightbox

2. **Code Preview**
- Syntax highlighted code snippets
- File structure tree
- README.md viewer
- Documentation viewer

3. **3D Model Viewer**
- Three.js integration
- View 3D assets
- Rotate, zoom, pan

**Deliverables:**
- [ ] WebGL embed
- [ ] Video player
- [ ] Code viewer
- [ ] 3D viewer

---

### Sprint 8: Analytics & Insights (2 tuần)

#### Features:
1. **Product Analytics**
- Views count
- Add to cart rate
- Purchase conversion
- Download count
- Review sentiment

2. **Seller Analytics**
- Revenue trends
- Best performing products
- Customer demographics
- Traffic sources
- Refund rate

3. **Admin Analytics**
- Platform revenue
- Active sellers
- Top categories
- Growth metrics
- User retention

**Deliverables:**
- [ ] Analytics tracking
- [ ] Dashboard charts
- [ ] Reports generation
- [ ] Export to CSV/PDF

---

## ⚡ PHASE 4: OPTIMIZATION (1-2 tháng)

### Sprint 9: Performance Tuning (2 tuần)

#### Tasks:
1. **Database Optimization**
- Add missing indexes
- Optimize slow queries
- Implement query caching
- Database partitioning

2. **Caching Strategy**
- Redis for session & cache
- CDN for static assets
- Browser caching headers
- API response caching

3. **Code Optimization**
- Lazy loading
- Code splitting
- Image optimization (WebP)
- Minification

**Targets:**
- Page load < 2s
- API response < 200ms
- 95+ Lighthouse score

**Deliverables:**
- [ ] Performance audit report
- [ ] Optimization implementation
- [ ] Load testing results
- [ ] Monitoring setup

---

### Sprint 10: SEO & Marketing (2 tuần)

#### Tasks:
1. **SEO Optimization**
- Meta tags optimization
- Structured data (Schema.org)
- Sitemap generation
- Robots.txt
- Canonical URLs

2. **Marketing Tools**
- Email campaigns
- Promotional banners
- Discount codes
- Affiliate program
- Social sharing

3. **Content Marketing**
- Blog integration
- Tutorial videos
- Case studies
- Success stories

**Deliverables:**
- [ ] SEO audit & fixes
- [ ] Marketing automation
- [ ] Content calendar
- [ ] Analytics integration

---

### Sprint 11: Mobile App (Optional - 3 tháng)

#### Features:
1. **React Native App**
- Browse source games
- View details
- Purchase & download
- Seller dashboard
- Push notifications

2. **App Features**
- Offline mode
- Download manager
- In-app purchases
- Biometric authentication

**Deliverables:**
- [ ] iOS app
- [ ] Android app
- [ ] App store submission
- [ ] Marketing materials

---

## 📋 IMPLEMENTATION CHECKLIST

### Phase 2: Seller System
- [ ] Database migrations
- [ ] Seller registration
- [ ] Admin approval workflow
- [ ] Product upload interface
- [ ] File processing & security
- [ ] Seller dashboard
- [ ] Revenue sharing system
- [ ] Withdrawal workflow
- [ ] Email notifications
- [ ] Unit & integration tests

### Phase 3: Advanced Features
- [ ] Version control
- [ ] License management
- [ ] Enhanced preview system
- [ ] Analytics dashboard
- [ ] Reporting tools

### Phase 4: Optimization
- [ ] Performance tuning
- [ ] SEO optimization
- [ ] Marketing tools
- [ ] Mobile app (optional)

---

## 🎯 SUCCESS METRICS

### Phase 2 Goals
- [ ] 20+ registered sellers
- [ ] 100+ source games uploaded
- [ ] 50+ successful transactions
- [ ] < 5% rejection rate
- [ ] 4.5+ seller satisfaction

### Phase 3 Goals
- [ ] 50+ sellers
- [ ] 300+ source games
- [ ] 200+ transactions/month
- [ ] $5,000+ monthly revenue
- [ ] 4.8+ average rating

### Phase 4 Goals
- [ ] 100+ sellers
- [ ] 500+ source games
- [ ] 500+ transactions/month
- [ ] $20,000+ monthly revenue
- [ ] 95+ Lighthouse score

---

## 💰 BUDGET ESTIMATE

### Development Costs
- Phase 2: 2-3 months × $3,000/month = $6,000-$9,000
- Phase 3: 2-3 months × $3,000/month = $6,000-$9,000
- Phase 4: 1-2 months × $3,000/month = $3,000-$6,000

**Total: $15,000 - $24,000**

### Infrastructure Costs (Monthly)
- AWS S3: $50-$200
- AWS CloudFront CDN: $50-$150
- Database: $50-$100
- Redis: $20-$50
- Email service: $20-$50
- Monitoring: $20-$50

**Total: $210-$600/month**

### Third-party Services
- Payment gateway: 2.9% + $0.30 per transaction
- Virus scanning: $50-$100/month
- Analytics: $0-$200/month

---

## 🚦 RISK MANAGEMENT

### Technical Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| File upload failures | High | Medium | Implement retry logic, chunked upload |
| Payment gateway issues | High | Low | Multiple payment options, fallback |
| Security breaches | Critical | Low | Regular audits, penetration testing |
| Performance degradation | Medium | Medium | Monitoring, auto-scaling |

### Business Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Low seller adoption | High | Medium | Marketing, incentives, onboarding |
| Copyright disputes | High | Medium | Verification process, DMCA policy |
| Refund abuse | Medium | Low | Clear refund policy, fraud detection |
| Competition | Medium | High | Unique features, better UX |

---

## 📅 TIMELINE SUMMARY

```
Month 1-2: Phase 2 Sprint 1-2 (Seller registration & upload)
Month 3-4: Phase 2 Sprint 3-4 (Dashboard & revenue sharing)
Month 5-6: Phase 3 Sprint 5-6 (Version control & licenses)
Month 7-8: Phase 3 Sprint 7-8 (Preview & analytics)
Month 9-10: Phase 4 Sprint 9-10 (Optimization & SEO)
Month 11-13: Phase 4 Sprint 11 (Mobile app - optional)
```

**Total Duration: 10-13 months**

---

## 🎓 TRAINING & DOCUMENTATION

### Seller Training
- [ ] Video tutorials
- [ ] Written guides
- [ ] FAQ section
- [ ] Live webinars
- [ ] Support chat

### Developer Documentation
- [ ] API documentation
- [ ] Code examples
- [ ] Architecture diagrams
- [ ] Deployment guide
- [ ] Troubleshooting guide

---

## 🔄 MAINTENANCE PLAN

### Daily
- Monitor error logs
- Check payment status
- Review new submissions
- Respond to support tickets

### Weekly
- Database backup
- Security scan
- Performance review
- Content updates

### Monthly
- Feature updates
- Bug fixes
- Analytics review
- User feedback analysis
- Financial reconciliation

### Quarterly
- Security audit
- Performance optimization
- Feature planning
- User surveys
- Market research
