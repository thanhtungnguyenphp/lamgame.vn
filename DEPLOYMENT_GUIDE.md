# Hướng Dẫn Cài Đặt Module và Deploy Production - Bagisto E-commerce

## 🚀 Tổng Quan
Dự án này sử dụng **Bagisto Framework** (Laravel E-commerce) với cấu hình Docker đầy đủ cho development và production.

---

## 📦 PHẦN 1: CÀI ĐẶT MODULE

### 1.1 Cài Đặt Dependencies

#### PHP Dependencies (Composer)
```bash
# Development
composer install

# Production (không cài dev dependencies)
composer install --no-dev --optimize-autoloader --no-interaction
```

#### JavaScript Dependencies (NPM)
```bash
# Development
npm install

# Production
npm ci --only=production
```

### 1.2 Cài Đặt Module Custom

#### Tạo Module Mới
```bash
# Tạo package mới
php artisan package:make-provider LamGame/Banner

# Hoặc sử dụng generator
php artisan bagisto:make-package LamGame Banner
```

#### Cấu Trúc Module
```
packages/LamGame/Banner/
├── src/
│   ├── Config/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Routes/
│   ├── Models/
│   ├── Providers/
│   ├── Resources/
│   │   ├── lang/
│   │   ├── views/
│   │   └── assets/
│   └── Repositories/
├── composer.json
└── package.json
```

#### Đăng Ký Module
```php
// config/app.php
'providers' => [
    // ...
    LamGame\Banner\Providers\BannerServiceProvider::class,
],
```

#### Auto-discovery (composer.json)
```json
{
    "extra": {
        "laravel": {
            "providers": [
                "LamGame\\Banner\\Providers\\BannerServiceProvider"
            ]
        }
    }
}
```

### 1.3 Cài Đặt Qua Composer

#### Từ Repository Private
```bash
# Thêm repository vào composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:lamgame/banner-module.git"
        }
    ]
}

# Cài đặt
composer require lamgame/banner
```

#### Từ Package Local
```bash
# Symlink development
composer install --prefer-source

# Build assets
npm run build
```

---

## 🛠️ PHẦN 2: DEVELOPMENT SETUP

### 2.1 Docker Development

#### Setup Initial
```bash
# Cấp quyền thực thi
chmod +x scripts/setup.sh scripts/dev.sh

# Setup environment
make setup
# hoặc
./scripts/setup.sh
```

#### Makefile Commands
```bash
# Khởi động services
make start          # Start tất cả services
make start-dev      # Start với dev tools (PhpMyAdmin, Kibana)

# Quản lý container
make stop           # Dừng services
make restart        # Khởi động lại
make status         # Xem trạng thái

# Development tasks
make shell          # Truy cập container
make logs           # Xem logs
make artisan cmd="migrate"     # Chạy artisan command
make composer cmd="install"   # Chạy composer
make npm cmd="run dev"        # Chạy npm

# Database
make migrate        # Chạy migration
make seed          # Seed database
make fresh         # Fresh migrate + seed
make backup        # Backup database
make restore file="backup.sql"

# Cache & Optimization
make cache-clear    # Clear tất cả cache
make optimize      # Optimize cho production
make reset         # Reset hoàn toàn
```

### 2.2 Environment Configuration

#### .env Development
```bash
# Copy từ example
cp .env.example .env

# Generate key
php artisan key:generate

# Cấu hình database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lamgame_db
DB_USERNAME=root
DB_PASSWORD=secret
```

#### Mobile-First Configuration
```bash
# Responsive/Mobile settings
APP_MOBILE_FIRST=true
RESPONSIVE_BREAKPOINTS="320,768,1024,1200"
MOBILE_VIEWPORT="width=device-width,initial-scale=1.0"
TOUCH_FRIENDLY=true
```

### 2.3 Vite Configuration

#### vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/themes/shop/assets/css/app.css',
                'resources/themes/shop/assets/js/app.js',
                'resources/themes/admin/assets/css/app.css',
                'resources/themes/admin/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
    },
    // Mobile-first optimization
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'mobile-core': ['alpinejs'],
                    'mobile-ui': ['bootstrap'],
                },
            },
        },
    },
});
```

---

## 🚀 PHẦN 3: PRODUCTION DEPLOYMENT

### 3.1 Server Requirements

#### Minimum Server Specs
```bash
# VPS/Server yêu cầu tối thiểu
CPU: 2 cores
RAM: 4GB
Storage: 50GB SSD
OS: Ubuntu 20.04/22.04 LTS
```

#### Software Stack
```bash
# Web server
Nginx 1.18+
# hoặc Apache 2.4+

# PHP
PHP 8.2+ với extensions:
- bcmath, ctype, fileinfo, json, mbstring, openssl
- pdo, tokenizer, xml, curl, gd, intl, zip

# Database
MySQL 8.0+ hoặc MariaDB 10.6+

# Cache & Queue
Redis 6.0+

# Search (optional)
Elasticsearch 8.x
```

### 3.2 Production Deployment với Docker

#### docker-compose.prod.yml
```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: docker/production/Dockerfile
      target: production
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - storage:/var/www/html/storage
      - uploads:/var/www/html/public/uploads
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/production.conf:/etc/nginx/conf.d/default.conf
      - ./public:/var/www/html/public:ro
      - ./ssl:/etc/nginx/ssl:ro
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    command: redis-server --requirepass ${REDIS_PASSWORD}

volumes:
  mysql_data:
  storage:
  uploads:
```

#### Dockerfile Production
```dockerfile
FROM php:8.2-fpm-alpine AS base

# Install dependencies
RUN apk add --no-cache \
    nginx \
    mysql-client \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql

FROM base AS production

# Copy application
COPY . /var/www/html
WORKDIR /var/www/html

# Install composer dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build assets for mobile-first
RUN npm ci --only=production && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Optimize for production
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]
```

### 3.3 Manual Deployment Steps

#### 1. Server Preparation
```bash
# Update server
sudo apt update && sudo apt upgrade -y

# Install required software
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql \
    php8.2-xml php8.2-gd php8.2-mbstring php8.2-curl \
    php8.2-zip php8.2-intl redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### 2. Application Deployment
```bash
# Clone repository
git clone https://github.com/yourusername/lamgame.vn.git /var/www/lamgame.vn
cd /var/www/lamgame.vn

# Set permissions
sudo chown -R www-data:www-data /var/www/lamgame.vn
sudo chmod -R 755 /var/www/lamgame.vn
sudo chmod -R 775 /var/www/lamgame.vn/storage
sudo chmod -R 775 /var/www/lamgame.vn/bootstrap/cache

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --only=production

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --force
php artisan db:seed --force (nếu cần)

# Storage link
php artisan storage:link

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

#### 3. Nginx Configuration (Mobile-First)
```nginx
# /etc/nginx/sites-available/lamgame.vn
server {
    listen 80;
    server_name lamgame.vn www.lamgame.vn;
    root /var/www/lamgame.vn/public;
    index index.php;

    # Mobile-first optimizations
    gzip on;
    gzip_vary on;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
    
    # Browser caching for mobile
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # PHP handling
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Security headers (mobile security)
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options SAMEORIGIN;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
}
```

#### 4. SSL/HTTPS Setup
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d lamgame.vn -d www.lamgame.vn

# Auto-renewal
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

### 3.4 Production Environment (.env.production)
```bash
# Application
APP_NAME="LamGame.vn"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://lamgame.vn

# Mobile-first settings
MOBILE_FIRST=true
RESPONSIVE_IMAGES=true
LAZY_LOADING=true

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lamgame_prod
DB_USERNAME=lamgame_user
DB_PASSWORD=secure_password

# Cache (Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@lamgame.vn
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lamgame.vn

# File Storage (for mobile optimization)
FILESYSTEM_DISK=public
IMAGE_INTERVENTION_DRIVER=gd

# Performance
RESPONSE_CACHE_ENABLED=true
VIEW_CACHE_ENABLED=true
```

---

## ⚡ PHẦN 4: MOBILE-FIRST OPTIMIZATION

### 4.1 Frontend Optimization
```css
/* Mobile-first CSS */
@media screen and (max-width: 768px) {
    .container {
        padding: 0 15px;
        font-size: 14px;
    }
    
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .btn {
        min-height: 44px; /* Touch-friendly */
        font-size: 16px;
    }
}

/* Image optimization */
.lazy-image {
    background: #f0f0f0;
    min-height: 200px;
}

img {
    max-width: 100%;
    height: auto;
}
```

### 4.2 Performance Monitoring
```bash
# Setup monitoring tools
composer require --dev laravel/telescope
php artisan telescope:install
php artisan migrate

# Performance debugging
composer require --dev barryvdh/laravel-debugbar

# Mobile testing tools
npm install --save-dev lighthouse puppeteer
```

### 4.3 Mobile SEO Configuration
```php
// config/seo.php
return [
    'mobile_first' => true,
    'viewport' => 'width=device-width, initial-scale=1.0',
    'responsive_images' => true,
    'amp_enabled' => false,
    'pwa_enabled' => true,
];
```

---

## 🔧 PHẦN 5: MAINTENANCE & MONITORING

### 5.1 Backup Strategy
```bash
# Database backup script
#!/bin/bash
BACKUP_DIR="/var/backups/lamgame"
DATE=$(date +"%Y%m%d_%H%M%S")

# Database backup
mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/lamgame.vn/storage /var/www/lamgame.vn/public/uploads

# Cleanup old backups (keep 7 days)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

### 5.2 Monitoring Scripts
```bash
# Health check script
#!/bin/bash
curl -f -s -o /dev/null https://lamgame.vn/health || {
    echo "Site is down" | mail -s "LamGame.vn Alert" admin@lamgame.vn
}

# Mobile performance check
lighthouse --mobile --output=json --output-path=/tmp/mobile-report.json https://lamgame.vn
```

### 5.3 Update & Maintenance
```bash
# Production update workflow
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci --only=production
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.2-fpm nginx
```

---

## 🚨 TROUBLESHOOTING

### Common Issues
```bash
# Permission issues
sudo chown -R www-data:www-data /var/www/lamgame.vn/storage
sudo chmod -R 775 /var/www/lamgame.vn/storage

# Cache issues
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Database connection
php artisan migrate:status
php artisan db:show

# Mobile rendering issues
npm run build
php artisan view:cache
```

### Performance Issues
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Monitor database
mysql -u root -p -e "SHOW PROCESSLIST;"

# Check Redis
redis-cli ping

# Monitor logs
tail -f /var/log/nginx/access.log
tail -f /var/www/lamgame.vn/storage/logs/laravel.log
```

---

## 📚 Tài Liệu Tham Khảo

- [Bagisto Documentation](https://devdocs.bagisto.com/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Mobile Web Best Practices](https://web.dev/mobile/)
- [Nginx Mobile Configuration](https://nginx.org/en/docs/)

---

**Lưu Ý**: Luôn test trên staging environment trước khi deploy lên production. Đặc biệt chú ý đến mobile experience và performance.