# LamGame.vn - Deployment Guide & Project Analysis

## Project Overview

**Project Name:** LamGame.vn  
**Framework:** Bagisto (Laravel-based e-Commerce Framework)  
**PHP Version:** ^8.2  
**Environment:** Docker & Docker Compose  
**Application Type:** Multi-vendor e-Commerce Platform

---

## 1. Project Architecture

### 1.1 Stack Overview

| Component | Technology | Version |
|-----------|-----------|---------|
| **Framework** | Laravel | 11.0 |
| **Frontend** | Vue.js + Vite | Latest |
| **Database** | MySQL | 8.0 |
| **Cache & Session** | Redis | 7-alpine |
| **Search Engine** | Meilisearch | v1.8 |
| **Email Testing** | Mailpit | v1.18 |
| **Web Server** | Nginx | 1.27-alpine |
| **Container Orchestration** | Docker Compose | 3.3 |

### 1.2 Project Structure

```
lamgame.vn/
├── app/                          # Main application code
│   ├── Console/                  # CLI commands
│   ├── Http/                     # Controllers, Middleware, Requests
│   ├── Models/                   # Eloquent models
│   ├── Services/                 # Business logic services
│   ├── Providers/                # Service providers
│   ├── Repositories/             # Data access layer
│   ├── Helpers/                  # Utility functions
│   ├── Exports/                  # Excel exports
│   ├── Imports/                  # Excel imports
│   ├── Mail/                     # Email templates
│   ├── Listeners/                # Event listeners
│   └── DataGrids/                # Grid configurations
├── packages/                     # Modular packages
│   ├── Webkul/                   # Core Webkul modules
│   │   ├── Admin/               # Admin panel
│   │   ├── Product/             # Product management
│   │   ├── Sales/               # Orders & sales
│   │   ├── Shop/                # Customer shop
│   │   ├── Category/            # Categories
│   │   ├── Attribute/           # Product attributes
│   │   └── [30+ other modules]  # Additional features
│   └── LamGame/                  # Custom modules
│       └── Banner/              # Banner management
├── routes/                       # API & Web routes
├── resources/                    # Views & frontend assets
├── database/                     # Migrations & seeders
├── docker/                       # Docker configuration
│   ├── php/                      # PHP-FPM configuration
│   ├── nginx/                    # Nginx configuration
│   └── [other configs]
├── public/                       # Public assets
├── storage/                      # Files, logs, cache
├── bootstrap/                    # Framework bootstrap
├── config/                       # Configuration files
├── vendor/                       # Composer dependencies
├── node_modules/                 # NPM dependencies
├── docker-compose.yml            # Main compose file
├── .env                          # Environment variables
├── composer.json                 # PHP dependencies
├── package.json                  # Node dependencies
├── Makefile                      # Development commands
└── vite.config.js               # Frontend build config
```

---

## 2. Core Services & Dependencies

### 2.1 Backend Services

#### **MySQL (Database)**
- **Container:** `lg-mysql`
- **Port:** 33069 (mapped to 3306)
- **Database:** `lamgame`
- **Credentials:**
  - User: `lg` / Password: `lg`
  - Root: `root` / Password: `root`
- **Purpose:** Primary data store for all application data
- **Persistence:** External volume `lamgame_lg_dbdata`

#### **Redis (Cache & Session)**
- **Container:** `lg-redis`
- **Port:** 63794 (mapped to 6379)
- **Purpose:** Caching, session storage, job queue support
- **Persistence:** Volume `redis_data`
- **Default Config:** File-based caching in docker-compose

#### **Meilisearch (Search Engine)**
- **Container:** `lg-meili`
- **Port:** 7703 (mapped to 7700)
- **Master Key:** `lamgame_meili_master_key`
- **Purpose:** Fast, typo-tolerant product search
- **Persistence:** Volume `meili_data`

#### **Mailpit (Email Testing)**
- **Container:** `lg-mailpit`
- **Ports:** 8028 (UI), 1025 (SMTP)
- **Purpose:** Development email testing and inspection
- **Access:** http://localhost:8028

### 2.2 Web Services

#### **PHP-FPM**
- **Container:** `lg-php`
- **Built from:** `docker/php/Dockerfile`
- **Working Dir:** `/var/www/html`
- **Volume Mount:** Project root mounted as cached volume
- **Environment:**
  - `PHP_OPCACHE_VALIDATE_TIMESTAMPS=1` (Development mode)
  - `PHP_IDE_CONFIG=serverName=lg-php` (XDebug support)

#### **Nginx**
- **Container:** `lg-web`
- **Port:** 80 (exposed)
- **Configuration:** `docker/nginx/lamgame.conf`
- **Integration:** Connected to traefik-public network
- **Routing:** Host-based routing via Traefik labels

### 2.3 Third-party Integrations

#### **PayPal**
- **Package:** `paypal/paypal-checkout-sdk`
- **Purpose:** Payment processing

#### **Email (SMTP)**
- **Provider:** smtp2go (SMTP2GO)
- **Host:** `mail.smtp2go.com`
- **Port:** 2525
- **Credentials:** Configured in `.env`

#### **OpenAI**
- **Package:** `openai-php/laravel`
- **Purpose:** AI-powered features (product descriptions, etc.)

#### **Social Login/Share**
- **Modules:** SocialLogin, SocialShare
- **Integration:** Laravel Socialite v5.0

---

## 3. Naming Conventions

### 3.1 Container Naming
```
lg-{component}
├── lg-web        (Nginx web server)
├── lg-php        (PHP application server)
├── lg-mysql      (MySQL database)
├── lg-redis      (Redis cache)
├── lg-meili      (Meilisearch)
└── lg-mailpit    (Mailpit email testing)
```

### 3.2 Database Naming
```
Database: lamgame
User: lg
Prefix: (empty - no table prefix)
Tables follow Laravel convention: snake_case
Example: users, products, orders, categories
```

### 3.3 Laravel/Bagisto Naming

#### **Namespace Conventions**
```php
// Core Bagisto packages
Webkul\Product\
Webkul\Category\
Webkul\Shop\
Webkul\Admin\

// Custom packages
LamGame\Banner\

// Application code
App\Http\Controllers\
App\Models\
App\Services\
App\Repositories\
```

#### **File Naming**
- **Models:** PascalCase (e.g., `Product.php`, `Category.php`)
- **Controllers:** PascalCase + Controller (e.g., `ProductController.php`)
- **Services:** PascalCase + Service (e.g., `ProductService.php`)
- **Traits:** PascalCase + Trait (e.g., `Timestampable.php`)
- **Migrations:** timestamp_action (e.g., `2024_01_29_create_products_table.php`)
- **Seeders:** PascalCase + Seeder (e.g., `ProductSeeder.php`)

#### **Route Naming**
```
Pattern: {domain}.{resource}.{action}
Examples:
- admin.products.index
- admin.products.create
- shop.products.index
- api.products.show
```

#### **Config File Naming**
- Snake case files in `config/` directory
- Examples: `app.php`, `database.php`, `cache.php`

---

## 4. Deployment Steps

### 4.1 Prerequisites

**System Requirements:**
- Docker Engine 20.10+
- Docker Compose 2.0+
- 4GB+ RAM minimum
- 20GB+ disk space

**Required Credentials:**
- SSH access to server
- SMTP credentials (for email)
- PayPal API credentials
- OpenAI API key (if using AI features)

### 4.2 Initial Setup

#### **Step 1: Clone Repository**
```bash
cd /data/www
git clone git@github.com:thanhtungnguyenphp/lamgame.vn.git
cd lamgame.vn
```

#### **Step 2: Environment Configuration**
```bash
# Copy environment template
cp .env.example .env

# Edit environment variables
nano .env
```

**Critical environment variables:**
```bash
APP_NAME="LAMGAME"
APP_ENV=production           # Change from 'local' for production
APP_DEBUG=false              # Disable debug in production
APP_KEY=base64:...          # Must be set (generate if needed)
APP_URL=https://lamgame.vn

DB_HOST=lg-mysql
DB_DATABASE=lamgame
DB_USERNAME=lg
DB_PASSWORD=lg

REDIS_HOST=lg-redis
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

#### **Step 3: Create Required Volumes**

```bash
# Create external database volume
docker volume create lamgame_lg_dbdata

# Verify (optional)
docker volume ls | grep lamgame
```

#### **Step 4: Start Docker Services**

```bash
# Build and start containers
docker compose up -d

# Verify services are running
docker compose ps
```

Expected output:
```
NAME              STATUS         PORTS
lg-web            Up 2 minutes   80/tcp
lg-php            Up 2 minutes   
lg-mysql          Up 2 minutes   33069->3306/tcp
lg-redis          Up 2 minutes   63794->6379/tcp
lg-meili          Up 2 minutes   7703->7700/tcp
lg-mailpit        Up 2 minutes   1025/tcp, 8028->8025/tcp
```

#### **Step 5: Install Dependencies**

```bash
# Install PHP dependencies
docker exec lg-php composer install --no-interaction

# Install Node dependencies
docker exec lg-php npm install
```

#### **Step 6: Generate Application Key**

```bash
docker exec lg-php php artisan key:generate
```

#### **Step 7: Run Database Migrations**

```bash
# Run migrations
docker exec lg-php php artisan migrate

# (Optional) Seed demo data
docker exec lg-php php artisan db:seed
```

#### **Step 8: Build Frontend Assets**

```bash
# Build production assets
docker exec lg-php npm run build

# Or for development with hot reload
docker exec lg-php npm run dev
```

#### **Step 9: Create Storage Symlink**

```bash
docker exec lg-php php artisan storage:link
```

#### **Step 10: Set Proper Permissions**

```bash
# May be needed depending on host OS
docker exec lg-php chown -R www-data:www-data storage bootstrap/cache
docker exec lg-php chmod -R 755 storage bootstrap/cache
```

### 4.3 Post-Deployment Configuration

#### **Cache Optimization** (Production)
```bash
docker exec lg-php php artisan config:cache
docker exec lg-php php artisan route:cache
docker exec lg-php php artisan view:cache
docker exec lg-php php artisan optimize
```

#### **Queue Setup** (If using jobs)
```bash
# Current config uses 'sync' driver for development
# For production, update .env:
# QUEUE_CONNECTION=redis
# Then run queue worker:
docker exec lg-php php artisan queue:work
```

#### **Scheduled Tasks** (Cron)
```bash
# Add to server crontab (runs every minute)
* * * * * cd /data/www/lamgame.vn && docker exec lg-php php artisan schedule:run >> /dev/null 2>&1
```

#### **Elasticsearch Setup** (Optional)
If using full-text search instead of Meilisearch:
```bash
# Add to docker-compose.yml and configure in .env
SCOUT_DRIVER=elasticsearch
```

---

## 5. Development Workflow

### 5.1 Using Make Commands

```bash
# View all available commands
make help

# Docker management
make up                # Start services
make down             # Stop services
make restart          # Restart services
make logs             # View logs
make logs-php         # PHP logs only
make status           # Check service status

# Development commands
make shell            # Open PHP container shell
make artisan CMD="migrate"  # Run artisan commands
make composer CMD="install" # Run composer commands
make npm CMD="install"      # Run npm commands

# Database operations
make migrate          # Run migrations
make migrate-fresh    # Reset database
make seed             # Seed database
make fresh            # Fresh migrate + seed
make db-backup        # Backup database
make db-restore FILE="backups/..."  # Restore database

# Cache & optimization
make cache-clear      # Clear all caches
make optimize         # Optimize application

# Build commands
make build            # Build Docker images
make rebuild          # Rebuild without cache
```

### 5.2 Database Management

```bash
# Access MySQL directly
docker exec -it lg-mysql mysql -uroot -proot lamgame

# Dump database
docker exec lg-mysql mysqldump -uroot -proot lamgame > backup.sql

# Restore database
docker exec -i lg-mysql mysql -uroot -proot lamgame < backup.sql
```

### 5.3 Code Development Tips

#### **Key Packages**
- **Webkul/Admin:** Admin panel functionality
- **Webkul/Product:** Product management
- **Webkul/Shop:** Customer-facing shop
- **Webkul/Sales:** Order and sales management
- **LamGame/Banner:** Custom banner module

#### **Configuration Location**
- `config/app.php` - Application config
- `config/database.php` - Database config
- `config/mail.php` - Email config
- `config/cache.php` - Cache config

#### **Database Access**
- Use Eloquent ORM (Laravel models)
- Repository pattern for data access (`app/Repositories/`)
- Services for business logic (`app/Services/`)

#### **Frontend Development**
- Edit Vue components in `resources/views/`
- CSS in `resources/css/`
- JavaScript in `resources/js/`
- Build with Vite: `npm run dev` or `npm run build`

---

## 6. Monitoring & Maintenance

### 6.1 Health Checks

```bash
# Check Docker containers
docker compose ps

# Check service logs
docker compose logs --tail=50

# Check specific service
docker compose logs php --tail=50

# Check database connection
docker exec lg-php php artisan tinker
>>> DB::connection()->getPdo();
```

### 6.2 Performance Monitoring

```bash
# Monitor Docker resources
docker stats

# Check slow queries (MySQL)
docker exec lg-mysql mysql -uroot -proot -e "SHOW PROCESSLIST;" lamgame

# Monitor Redis
docker exec lg-redis redis-cli INFO
```

### 6.3 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Container won't start | Check logs: `docker compose logs service_name` |
| Database connection fails | Verify DB_HOST, DB_USERNAME, DB_PASSWORD in .env |
| Permission denied (storage) | Run: `docker exec lg-php chown -R www-data:www-data storage` |
| Out of disk space | Clean old logs: `docker exec lg-php php artisan logs:clean` |
| Redis connection fails | Check REDIS_HOST matches service name (`lg-redis`) |
| Email not sending | Test with Mailpit at http://localhost:8028 |

---

## 7. Environment-Specific Configuration

### 7.1 Development (.env)
```
APP_ENV=local
APP_DEBUG=true
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
```

### 7.2 Staging (.env)
```
APP_ENV=staging
APP_DEBUG=true
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
MAIL_MAILER=smtp
```

### 7.3 Production (.env)
```
APP_ENV=production
APP_DEBUG=false
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
MAIL_MAILER=smtp
LOG_CHANNEL=stack
```

---

## 8. Useful Commands Reference

```bash
# Application
php artisan tinker                 # Interactive shell
php artisan migrate                # Run migrations
php artisan seed                   # Seed database
php artisan cache:clear            # Clear cache
php artisan queue:work             # Start queue worker
php artisan storage:link           # Create storage symlink

# Optimization
composer dump-autoload --optimize  # Optimize autoloader
php artisan optimize               # Optimize framework
php artisan config:cache           # Cache config
php artisan route:cache            # Cache routes

# Development
php artisan make:model Model       # Create model
php artisan make:migration         # Create migration
php artisan make:seeder Seeder     # Create seeder
php artisan make:controller        # Create controller
php artisan make:job Job           # Create job
php artisan tinker                 # Interactive shell

# Git workflow
git pull                           # Pull latest changes
git checkout -b feature/name       # Create feature branch
git add .                          # Stage changes
git commit -m "message"            # Commit changes
git push origin feature/name       # Push branch
```

---

## 9. Support & Documentation

- **Bagisto Docs:** https://devdocs.bagisto.com
- **Laravel Docs:** https://laravel.com/docs
- **GitHub Repository:** https://github.com/thanhtungnguyenphp/lamgame.vn
- **Bagisto Community:** https://forums.bagisto.com

---

**Last Updated:** January 29, 2026  
**Version:** 1.0
