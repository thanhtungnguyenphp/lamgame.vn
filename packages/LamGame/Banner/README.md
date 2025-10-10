# LamGame Banner Module for Bagisto

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.0%2B-red.svg)](https://laravel.com)
[![Bagisto Version](https://img.shields.io/badge/Bagisto-2.0%2B-orange.svg)](https://bagisto.com)

A comprehensive banner management system for Bagisto with advanced caching, multi-device support, analytics, and powerful admin interface.

## ✨ Features

### 🎯 Core Features
- **Dynamic Banner Management** - Create, edit, and manage banners with rich content
- **Multi-Device Support** - Target specific devices (desktop, tablet, mobile, all)
- **Position-Based Display** - Place banners in predefined or custom positions
- **Multi-Channel & Multi-Locale** - Support for different channels and languages
- **Time-Based Scheduling** - Set start/end dates for banner campaigns
- **Advanced Caching** - Redis-based caching with automatic invalidation

### 📊 Analytics & Tracking
- **Impression Tracking** - Monitor banner views
- **Click Tracking** - Track banner interactions
- **Performance Analytics** - Click-through rates and performance metrics
- **Admin Dashboard** - Comprehensive analytics interface

### 🛠️ Technical Features
- **RESTful API** - Public API for frontend integration
- **Responsive Images** - Automatic image optimization for different devices
- **Observer Pattern** - Automatic cache invalidation on changes
- **Repository Pattern** - Clean architecture with proper separation
- **Translation Support** - Multi-language content management

## 📦 Installation

### 1. Install Package

```bash
# Navigate to your Bagisto project
cd /path/to/your/bagisto-project

# Add package to composer.json
composer config repositories.lamgame-banner path packages/LamGame/Banner

# Install the package
composer require lamgame/banner
```

### 2. Register Service Provider

Add to `config/app.php`:

```php
'providers' => [
    // ... other providers
    LamGame\Banner\Providers\BannerServiceProvider::class,
],
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Publish Assets (Optional)

```bash
# Publish configuration
php artisan vendor:publish --tag=banner-config

# Publish views for customization
php artisan vendor:publish --tag=banner-views

# Publish language files
php artisan vendor:publish --tag=banner-lang

# Publish assets
php artisan vendor:publish --tag=banner-assets
```

### 5. Configure Environment

Add to your `.env` file:

```env
# Banner Configuration
BANNER_CACHE_TTL=3600
BANNER_CACHE_ENABLED=true
BANNER_TRACK_IMPRESSIONS=true
BANNER_TRACK_CLICKS=true
BANNER_MAX_SIZE=5120
```

## 🚀 Usage

### Frontend API

#### Get Banners by Position

```javascript
// Get homepage hero banners for mobile
fetch('/api/banners/position/homepage_hero?device_type=mobile&locale=vi')
  .then(response => response.json())
  .then(data => {
    console.log(data.data); // Array of banners
  });
```

#### Get All Banners with Filters

```javascript
// Get banners with multiple filters
fetch('/api/banners?position=sidebar&device_type=desktop&channel_id=1&limit=3')
  .then(response => response.json())
  .then(data => {
    data.data.forEach(banner => {
      // Render banner
      renderBanner(banner);
    });
  });
```

#### Track Banner Click

```javascript
// Track banner click
fetch('/api/banners/123/click', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  }
});
```

### Frontend Integration Example

```html
<!-- Banner Container -->
<div id="homepage-hero-banners"></div>

<script>
// Fetch and render homepage hero banners
async function loadBanners() {
  try {
    const response = await fetch('/api/banners/position/homepage_hero?device_type=mobile');
    const data = await response.json();
    
    if (data.success) {
      const container = document.getElementById('homepage-hero-banners');
      
      data.data.forEach(banner => {
        const bannerElement = createBannerElement(banner);
        container.appendChild(bannerElement);
      });
    }
  } catch (error) {
    console.error('Failed to load banners:', error);
  }
}

function createBannerElement(banner) {
  const div = document.createElement('div');
  div.className = `banner-item ${banner.css_classes}`;
  
  if (banner.type === 'image') {
    div.innerHTML = `
      <a href="${banner.link}" target="${banner.target}" 
         onclick="trackClick(${banner.id})">
        <img src="${banner.responsive_images.mobile}" 
             alt="${banner.image_alt}"
             srcset="${banner.responsive_images.mobile} 480w,
                     ${banner.responsive_images.tablet} 768w,
                     ${banner.responsive_images.desktop} 1200w"
             sizes="(max-width: 480px) 480px, (max-width: 768px) 768px, 1200px">
        <div class="banner-content">
          <h2>${banner.title}</h2>
          <div>${banner.content}</div>
        </div>
      </a>
    `;
  }
  
  return div;
}

function trackClick(bannerId) {
  fetch(`/api/banners/${bannerId}/click`, { method: 'POST' });
}

// Load banners on page load
document.addEventListener('DOMContentLoaded', loadBanners);
</script>
```

### PHP Integration

```php
use LamGame\Banner\Repositories\BannerRepository;

class HomeController extends Controller
{
    public function index(BannerRepository $bannerRepository)
    {
        // Get banners for homepage
        $heroBanners = $bannerRepository->getByPosition(
            'homepage_hero',
            'all', // device type
            1,     // channel id
            'vi',  // locale
            3      // limit
        );

        return view('home', compact('heroBanners'));
    }
}
```

```blade
{{-- In your Blade template --}}
@if($heroBanners->count() > 0)
    <div class="hero-banners">
        @foreach($heroBanners as $banner)
            <div class="banner-item {{ $banner['css_classes'] }}">
                @if($banner['type'] === 'image')
                    <a href="{{ $banner['link'] }}" target="{{ $banner['target'] }}">
                        <img src="{{ $banner['image'] }}" alt="{{ $banner['image_alt'] }}">
                        <div class="banner-content">
                            <h2>{{ $banner['title'] }}</h2>
                            {!! $banner['content'] !!}
                        </div>
                    </a>
                @elseif($banner['type'] === 'html')
                    {!! $banner['content'] !!}
                @endif
            </div>
        @endforeach
    </div>
@endif
```

## 🎛️ Admin Interface

### Accessing Banner Management

1. Login to Bagisto Admin Panel
2. Navigate to **Banners** > **Banners** in the sidebar menu
3. Manage banners using the comprehensive CRUD interface

### Admin Features

- **Banner List**: View all banners with filtering and sorting
- **Create/Edit Forms**: Rich form interface with image upload
- **Analytics Dashboard**: Performance metrics and insights
- **Mass Operations**: Bulk enable/disable/delete banners
- **Cache Management**: Clear banner caches with one click

## 🔧 Configuration

### Banner Positions

Configure available positions in `config/banner.php`:

```php
'positions' => [
    'homepage_hero' => 'Homepage Hero',
    'homepage_secondary' => 'Homepage Secondary',
    'sidebar_top' => 'Sidebar Top',
    'sidebar_bottom' => 'Sidebar Bottom',
    'header' => 'Header',
    'footer' => 'Footer',
    'product_detail' => 'Product Detail',
    'category_page' => 'Category Page',
    'checkout' => 'Checkout',
    'custom' => 'Custom Position',
],
```

### Cache Configuration

```php
'cache' => [
    'ttl' => 3600, // Cache TTL in seconds
    'prefix' => 'banner_display:',
    'enabled' => true,
],
```

### Image Settings

```php
'images' => [
    'disk' => 'public',
    'path' => 'banners',
    'max_size' => 5120, // KB
    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'optimize' => true,
],
```

## 📊 Database Schema

### Banners Table

```sql
CREATE TABLE banners (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) DEFAULT 'image',
    position VARCHAR(255) NOT NULL,
    device_type ENUM('all', 'desktop', 'tablet', 'mobile') DEFAULT 'all',
    channel_id BIGINT NULLABLE,
    locale VARCHAR(10) NULLABLE,
    start_date DATETIME NULLABLE,
    end_date DATETIME NULLABLE,
    sort_order INT DEFAULT 0,
    status BOOLEAN DEFAULT true,
    
    title TEXT NULLABLE,
    content LONGTEXT NULLABLE,
    image VARCHAR(255) NULLABLE,
    image_alt TEXT NULLABLE,
    link VARCHAR(255) NULLABLE,
    target ENUM('_self', '_blank') DEFAULT '_self',
    
    css_classes JSON NULLABLE,
    attributes JSON NULLABLE,
    settings JSON NULLABLE,
    
    clicks_count INT DEFAULT 0,
    impressions_count INT DEFAULT 0,
    
    created_at TIMESTAMP NULLABLE,
    updated_at TIMESTAMP NULLABLE
);
```

### Banner Translations Table

```sql
CREATE TABLE banner_translations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    banner_id BIGINT NOT NULL,
    locale VARCHAR(10) NOT NULL,
    
    title TEXT NULLABLE,
    content LONGTEXT NULLABLE,
    image_alt TEXT NULLABLE,
    meta_title TEXT NULLABLE,
    meta_description TEXT NULLABLE,
    settings JSON NULLABLE,
    
    created_at TIMESTAMP NULLABLE,
    updated_at TIMESTAMP NULLABLE,
    
    UNIQUE KEY (banner_id, locale)
);
```

## 🧪 Testing

### Run Tests

```bash
# Run all tests
vendor/bin/phpunit packages/LamGame/Banner/tests

# Run specific test suite
vendor/bin/phpunit packages/LamGame/Banner/tests/Unit
vendor/bin/phpunit packages/LamGame/Banner/tests/Feature
```

### Test API Endpoints

```bash
# Test banner API
curl -X GET "http://yoursite.com/api/banners/position/homepage_hero?device_type=mobile" \
     -H "Accept: application/json"

# Test click tracking
curl -X POST "http://yoursite.com/api/banners/1/click" \
     -H "Content-Type: application/json"
```

## 📈 Performance Optimization

### Caching Strategy

- **Redis Caching**: Banners are cached with TTL-based expiration
- **Cache Keys**: `banner_display:pos_{position}:dev_{device}:ch_{channel}:loc_{locale}`
- **Auto Invalidation**: Observer pattern clears relevant caches on CRUD operations

### Database Optimization

- **Composite Indexes**: Optimized queries with proper indexing
- **Eager Loading**: Relationships loaded efficiently
- **Query Scopes**: Reusable query conditions for better performance

## 🔒 Security

- **Input Validation**: Comprehensive validation on all inputs
- **XSS Protection**: HTML content properly escaped
- **CSRF Protection**: All forms protected with CSRF tokens
- **Rate Limiting**: API endpoints protected with throttling
- **Permission System**: Admin access control integration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: This README and inline code comments
- **Issues**: Use GitHub issues for bug reports and feature requests
- **Contact**: dev@lamgame.vn

## 🎯 Roadmap

- [ ] **A/B Testing**: Built-in A/B testing for banner campaigns
- [ ] **Advanced Analytics**: More detailed analytics and reporting
- [ ] **Video Banners**: Enhanced video banner support
- [ ] **AI Optimization**: AI-powered banner optimization
- [ ] **Integration APIs**: Third-party service integrations
- [ ] **Multi-tenant Support**: Support for multi-tenant environments

---

**Made with ❤️ by LamGame Team**