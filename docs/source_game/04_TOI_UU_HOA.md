# TỐI ƯU HÓA SOURCE GAME

## 🚀 PERFORMANCE OPTIMIZATION

### 1. Database Optimization

#### Indexing Strategy
```sql
-- Products table
CREATE INDEX idx_products_type_status ON products(type, status);
CREATE INDEX idx_products_created ON products(created_at DESC);

-- Product flat table
CREATE INDEX idx_pf_search ON product_flat(status, visible_individually, locale);
CREATE INDEX idx_pf_url ON product_flat(url_key, locale);
CREATE INDEX idx_pf_price ON product_flat(price);
CREATE FULLTEXT INDEX idx_pf_fulltext ON product_flat(name, short_description, description);

-- Product categories
CREATE INDEX idx_pc_composite ON product_categories(category_id, product_id);

-- Earnings
CREATE INDEX idx_earnings_seller_status ON source_game_earnings(seller_id, status, created_at);
CREATE INDEX idx_earnings_paid ON source_game_earnings(paid_at);

-- Downloadable links
CREATE INDEX idx_dl_product ON product_downloadable_links(product_id, sort_order);
```

#### Query Optimization
```php
// BAD: N+1 query problem
$products = Product::all();
foreach ($products as $product) {
    echo $product->seller->name; // N queries
}

// GOOD: Eager loading
$products = Product::with('seller')->get();
foreach ($products as $product) {
    echo $product->seller->name; // 1 query
}

// BETTER: Select only needed columns
$products = Product::with('seller:id,name,shop_name')
    ->select('id', 'name', 'price', 'company_id')
    ->get();

// BEST: Use query builder for simple queries
$products = DB::table('products as p')
    ->join('source_game_sellers as s', 'p.company_id', '=', 's.id')
    ->select('p.id', 'p.name', 'p.price', 's.shop_name')
    ->where('p.type', 'downloadable')
    ->get();
```

#### Database Connection Pooling
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'options' => [
        PDO::ATTR_PERSISTENT => true, // Connection pooling
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
],
```

---

### 2. Caching Strategy

#### Multi-layer Caching
```php
// Layer 1: Application cache (Redis)
Cache::remember('source_games:featured', 300, function () {
    return Product::featured()->limit(10)->get();
});

// Layer 2: Query result cache
DB::table('products')
    ->where('type', 'downloadable')
    ->remember(300) // Cache for 5 minutes
    ->get();

// Layer 3: HTTP cache (CDN)
return response($data)
    ->header('Cache-Control', 'public, max-age=3600')
    ->header('ETag', md5($data));
```

#### Cache Invalidation
```php
// app/Observers/ProductObserver.php
class ProductObserver
{
    public function updated(Product $product)
    {
        // Clear related caches
        Cache::forget('source_game:' . $product->url_key);
        Cache::forget('source_games:category:' . $product->category_id);
        Cache::tags(['products', 'seller:' . $product->seller_id])->flush();
    }
}
```

#### Redis Configuration
```php
// config/cache.php
'redis' => [
    'client' => 'phpredis',
    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
        'read_timeout' => 60,
        'persistent' => true, // Persistent connection
    ],
],
```

---

### 3. File Storage Optimization

#### S3 Configuration
```php
// config/filesystems.php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'options' => [
        'CacheControl' => 'max-age=31536000, public',
        'ServerSideEncryption' => 'AES256',
    ],
],
```

#### Image Optimization
```php
// app/Services/ImageOptimizationService.php
use Intervention\Image\Facades\Image;

class ImageOptimizationService
{
    public function optimize($file)
    {
        $image = Image::make($file);
        
        // Resize if too large
        if ($image->width() > 1920) {
            $image->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Convert to WebP
        $webp = $image->encode('webp', 85);
        
        // Generate thumbnails
        $thumbnails = [
            'small' => $image->fit(300, 200)->encode('webp', 80),
            'medium' => $image->fit(600, 400)->encode('webp', 85),
            'large' => $image->fit(1200, 800)->encode('webp', 90),
        ];
        
        return [
            'original' => $webp,
            'thumbnails' => $thumbnails,
        ];
    }
}
```

#### CDN Integration
```php
// Use CloudFront for static assets
$cdnUrl = config('app.cdn_url');
$imageUrl = $cdnUrl . '/source-games/' . $product->id . '/image.webp';

// Signed URLs for private files
$url = Storage::disk('s3')->temporaryUrl(
    'source-games/' . $product->id . '/source.zip',
    now()->addHours(24)
);
```

---

### 4. Frontend Optimization

#### Lazy Loading
```javascript
// Lazy load images
<img 
    data-src="/images/product.jpg" 
    class="lazyload"
    alt="Product"
/>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.lazyload');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazyload');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});
</script>
```

#### Code Splitting
```javascript
// webpack.mix.js
mix.js('resources/js/app.js', 'public/js')
   .extract(['vue', 'axios']) // Vendor bundle
   .js('resources/js/seller-dashboard.js', 'public/js') // Separate bundle
   .version();
```

#### Asset Minification
```bash
# Install dependencies
npm install --save-dev terser-webpack-plugin css-minimizer-webpack-plugin

# webpack.mix.js
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

mix.webpackConfig({
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    compress: {
                        drop_console: true,
                    },
                },
            }),
            new CssMinimizerPlugin(),
        ],
    },
});
```

---

### 5. API Optimization

#### Response Compression
```php
// app/Http/Middleware/CompressResponse.php
class CompressResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if ($this->shouldCompress($request, $response)) {
            $response->header('Content-Encoding', 'gzip');
            $response->setContent(gzencode($response->getContent(), 9));
        }
        
        return $response;
    }
    
    private function shouldCompress($request, $response)
    {
        return str_contains($request->header('Accept-Encoding'), 'gzip')
            && $response->getStatusCode() === 200
            && strlen($response->getContent()) > 1024;
    }
}
```

#### API Rate Limiting
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:60,1', // 60 requests per minute
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

// Custom rate limit for authenticated users
Route::middleware('auth:api', 'throttle:1000,1')->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'dashboard']);
});
```

#### Response Pagination
```php
// Use cursor pagination for large datasets
$products = Product::orderBy('created_at', 'desc')
    ->cursorPaginate(20);

// API response
return response()->json([
    'data' => $products->items(),
    'next_cursor' => $products->nextCursor()?->encode(),
    'prev_cursor' => $products->previousCursor()?->encode(),
]);
```

---

### 6. Search Optimization

#### Full-text Search
```php
// Use MySQL full-text search
$products = DB::table('product_flat')
    ->whereRaw('MATCH(name, description) AGAINST(? IN BOOLEAN MODE)', [$search])
    ->get();

// Or use Laravel Scout with Algolia/Meilisearch
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category->name,
        ];
    }
}

// Search
$products = Product::search($query)->paginate(20);
```

#### Search Caching
```php
// Cache search results
$cacheKey = 'search:' . md5($query . $filters);
$results = Cache::remember($cacheKey, 300, function () use ($query, $filters) {
    return Product::search($query)->filter($filters)->get();
});
```

---

### 7. Background Jobs

#### Queue Configuration
```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],
],
```

#### Job Examples
```php
// Process file upload in background
dispatch(new ProcessSourceGameUpload($product, $files));

// Send email notifications
dispatch(new SendProductApprovedEmail($product))->delay(now()->addMinutes(5));

// Generate thumbnails
dispatch(new GenerateThumbnails($product))->onQueue('images');

// Update analytics
dispatch(new UpdateProductAnalytics($product))->onQueue('analytics');
```

#### Job Batching
```php
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

$batch = Bus::batch([
    new ProcessImage($image1),
    new ProcessImage($image2),
    new ProcessImage($image3),
])->then(function (Batch $batch) {
    // All jobs completed successfully
})->catch(function (Batch $batch, Throwable $e) {
    // First batch job failure detected
})->finally(function (Batch $batch) {
    // The batch has finished executing
})->dispatch();
```

---

### 8. Monitoring & Logging

#### Application Monitoring
```php
// Install Laravel Telescope
composer require laravel/telescope

// Or use external services
// Sentry for error tracking
composer require sentry/sentry-laravel

// New Relic for APM
composer require newrelic/newrelic-php-agent
```

#### Custom Metrics
```php
// app/Services/MetricsService.php
class MetricsService
{
    public function track($metric, $value = 1, $tags = [])
    {
        // Send to monitoring service
        app('statsd')->increment($metric, $value, $tags);
        
        // Or log to database
        Metric::create([
            'name' => $metric,
            'value' => $value,
            'tags' => $tags,
            'timestamp' => now(),
        ]);
    }
}

// Usage
app(MetricsService::class)->track('source_game.purchased', 1, [
    'product_id' => $product->id,
    'price' => $product->price,
]);
```

#### Slow Query Logging
```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\DB;

public function boot()
{
    DB::listen(function ($query) {
        if ($query->time > 1000) { // > 1 second
            Log::warning('Slow query detected', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ]);
        }
    });
}
```

---

## 📊 PERFORMANCE BENCHMARKS

### Target Metrics
```
Page Load Time:
- Homepage: < 1.5s
- Product List: < 2s
- Product Detail: < 1.8s
- Seller Dashboard: < 2.5s

API Response Time:
- GET /api/source-games: < 200ms
- GET /api/source-games/{id}: < 150ms
- POST /api/source-games: < 500ms

Database Queries:
- Average query time: < 50ms
- Max queries per request: < 20
- Cache hit rate: > 80%

File Operations:
- Upload speed: > 10MB/s
- Download link generation: < 1s
- Image optimization: < 3s per image
```

### Load Testing
```bash
# Install Apache Bench
apt-get install apache2-utils

# Test homepage
ab -n 1000 -c 10 https://lamgame.localhost/source-game

# Test API endpoint
ab -n 1000 -c 10 -H "Authorization: Bearer TOKEN" \
   https://lamgame.localhost/api/source-games

# Install k6 for advanced testing
brew install k6

# Run load test
k6 run load-test.js
```

```javascript
// load-test.js
import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '2m', target: 100 }, // Ramp up to 100 users
        { duration: '5m', target: 100 }, // Stay at 100 users
        { duration: '2m', target: 0 },   // Ramp down to 0 users
    ],
    thresholds: {
        http_req_duration: ['p(95)<500'], // 95% of requests must complete below 500ms
        http_req_failed: ['rate<0.01'],   // Error rate must be below 1%
    },
};

export default function () {
    let response = http.get('https://lamgame.localhost/source-game');
    check(response, {
        'status is 200': (r) => r.status === 200,
        'response time < 2s': (r) => r.timings.duration < 2000,
    });
    sleep(1);
}
```

---

## 🔧 OPTIMIZATION CHECKLIST

### Database
- [ ] Add indexes on frequently queried columns
- [ ] Optimize slow queries (> 1s)
- [ ] Implement query caching
- [ ] Use eager loading to prevent N+1
- [ ] Enable connection pooling
- [ ] Regular ANALYZE TABLE

### Caching
- [ ] Implement Redis caching
- [ ] Cache database queries
- [ ] Cache API responses
- [ ] Cache rendered views
- [ ] Set appropriate TTL
- [ ] Implement cache warming

### Files
- [ ] Use S3 for file storage
- [ ] Implement CDN (CloudFront)
- [ ] Optimize images (WebP, compression)
- [ ] Generate multiple sizes
- [ ] Use signed URLs for security
- [ ] Implement lazy loading

### Frontend
- [ ] Minify CSS/JS
- [ ] Code splitting
- [ ] Lazy load images
- [ ] Use WebP images
- [ ] Implement service worker
- [ ] Enable browser caching

### API
- [ ] Implement rate limiting
- [ ] Use response compression
- [ ] Optimize payload size
- [ ] Implement pagination
- [ ] Use HTTP/2
- [ ] Enable CORS properly

### Background Jobs
- [ ] Move heavy tasks to queue
- [ ] Use job batching
- [ ] Implement job retry logic
- [ ] Monitor queue health
- [ ] Scale workers as needed

### Monitoring
- [ ] Set up error tracking (Sentry)
- [ ] Monitor performance (New Relic)
- [ ] Log slow queries
- [ ] Track custom metrics
- [ ] Set up alerts
- [ ] Regular performance audits
