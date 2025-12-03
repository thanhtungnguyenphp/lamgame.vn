# LamGame.vn - Migration to Shared Services

## 📋 Migration Summary

Project đã được tối ưu để sử dụng **shared services architecture** thay vì chạy services riêng.

### ✅ Changes Made

#### 1. **docker-compose.yml**
- ❌ Removed: MySQL, Redis, Meilisearch, Mailpit containers
- ✅ Kept: PHP + Nginx only
- ✅ Changed network: `lamgame-network` → `shared-network`
- ✅ Removed: `traefik-public` external network (not needed)
- ✅ Updated Traefik labels:
  - Added HTTPS support (`websecure` entrypoint)
  - Added TLS configuration
  - Changed domain: `lamgame.vn` → `lamgame.localhost`
- ✅ Updated container names:
  - `lg-php` → `lamgame-php`
  - `lg-web` → `lamgame-web`

#### 2. **.env**
- ✅ `DB_HOST`: `lg-mysql` → `shared-mysql`
- ✅ `DB_USERNAME`: `root` → `lamgame`
- ✅ `DB_PASSWORD`: `root` → `lamgame`
- ✅ `REDIS_HOST`: `redis` → `shared-redis`
- ✅ `MAIL_HOST`: `mail.smtp2go.com` → `shared-mailpit` (development)
- ✅ `MAIL_PORT`: `465` → `1025`
- ✅ `SCOUT_DRIVER`: `elasticsearch` → `meilisearch`
- ✅ `MEILISEARCH_HOST`: Added `http://shared-meili:7700`

#### 3. **nginx/lamgame.conf**
- ✅ `fastcgi_pass`: `php:9000` → `lamgame-php:9000`

#### 4. **Makefile**
- ✅ Completely rewritten for shared services
- ✅ Added database management commands (backup, restore, shell)
- ✅ Added setup command for new installations
- ✅ Simplified commands (no external scripts needed)

## 🚀 Usage

### Prerequisites
Make sure shared services are running:
```bash
cd /Users/Shared/jerry/ohha/shared
make up
```

### Start Project
```bash
cd projects/lamgame.vn
make up
```

### Access
- **HTTP**: http://lamgame.localhost
- **HTTPS**: https://lamgame.localhost

### Common Commands
```bash
make help              # Show all commands
make up                # Start project
make down              # Stop project
make logs              # Show logs
make shell             # Open PHP shell
make migrate           # Run migrations
make artisan CMD="..."  # Run artisan command
```

## 🗄️ Database Setup

### First Time Setup
```bash
# 1. Create database in shared-mysql
cd /Users/Shared/jerry/ohha/shared
make db-create DB=lamgame USER=lamgame PASS=lamgame

# 2. Run migrations
cd projects/lamgame.vn
make migrate
```

### Database Access
```bash
# Option 1: Via project Makefile
make db-shell

# Option 2: Via shared Makefile
cd ../../
docker exec -it shared-mysql mysql -ulamgame -plamgame lamgame
```

## 📦 Before vs After

### Before (Old Architecture)
```yaml
services:
  - lg-mysql      (dedicated MySQL)
  - lg-redis      (dedicated Redis)
  - lg-meili      (dedicated Meilisearch)
  - lg-mailpit    (dedicated Mailpit)
  - lg-php
  - lg-web
```
**Resource usage**: ~600MB RAM

### After (Shared Services)
```yaml
services:
  - lamgame-php
  - lamgame-web

# Uses shared services:
  - shared-mysql (shared by all projects)
  - shared-redis (shared by all projects)
  - shared-meili (shared by all projects)
  - shared-mailpit (shared by all projects)
  - traefik (shared reverse proxy)
```
**Resource usage**: ~150MB RAM per project

**Savings**: ~450MB RAM + simplified management

## 🔧 Troubleshooting

### Cannot connect to database
```bash
# Check if shared-mysql is running
cd ../../
make status

# Check if database exists
make db-list

# Create database if missing
make db-create DB=lamgame USER=lamgame PASS=lamgame
```

### Cannot access via domain
```bash
# Check if traefik is running
cd ../../
make status | grep traefik

# Check /etc/hosts
cat /etc/hosts | grep lamgame

# Add if missing
echo "127.0.0.1 lamgame.localhost" | sudo tee -a /etc/hosts
```

### Project not showing up in Traefik
```bash
# Check if containers are on shared-network
docker network inspect shared-network

# Restart project
make restart
```

## 📝 Important Notes

1. **Database**: Now uses `shared-mysql` on port `33066`
2. **Redis**: Now uses `shared-redis` on port `63791`
3. **Meilisearch**: Now uses `shared-meili` on port `7700`
4. **Mailpit UI**: Access at http://localhost:8025 (shared)
5. **SSL**: Uses existing cert at `../../ssl/lamgame.localhost.pem`
6. **Production Mail**: smtp2go config commented out in .env, uncomment when deploying

## 🔐 Credentials

### Development (Shared MySQL)
- **Host**: shared-mysql (or localhost:33066)
- **Database**: lamgame
- **Username**: lamgame
- **Password**: lamgame
- **Root Password**: root

### Production Mail (smtp2go)
- See commented section in .env

## 🔗 Related Files

- `docker-compose.yml` - Project services
- `.env` - Environment configuration
- `Makefile` - Management commands
- `docker/nginx/lamgame.conf` - Nginx config
- `docker/php/Dockerfile` - PHP image

## 📚 Documentation

- [Shared Services README](../../README.md)
- [Projects README](../README.md)
- [Traefik SSL Config](../../traefik/ssl.yml)
