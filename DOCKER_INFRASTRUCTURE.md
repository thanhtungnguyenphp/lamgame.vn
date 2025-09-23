# 🐳 Docker Infrastructure - Lamgame.vn

**Cập nhật lần cuối**: 23/09/2025 15:36:27  
**Trạng thái**: ✅ All containers running

---

## 📋 Container Overview

### 🚀 Container Status Summary
```bash
# Kiểm tra trạng thái tất cả container
docker ps --filter "name=lg-" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

# Kiểm tra resource usage
docker stats --no-stream lg-mysql lg-php lg-web lg-redis lg-vite lg-mailpit
```

| Container | Image | Status | CPU | Memory | Uptime |
|-----------|-------|--------|-----|--------|--------|
| lg-mysql | mysql:8.0 | ✅ Running | 0.99% | 441.9MB | 26+ hours |
| lg-php | Custom Build | ✅ Running | 0.01% | 56.43MB | Active |
| lg-web | nginx:1.27-alpine | ✅ Running | 0.00% | 10.15MB | Active |
| lg-redis | redis:7-alpine | ✅ Running | 1.04% | 8.51MB | Active |
| lg-vite | node:20-alpine | ✅ Running | 295.59% | 195.6MB | Active |
| lg-mailpit | axllent/mailpit:v1.18 | ✅ Running | 0.00% | 8.49MB | Active |

---

## 🔗 Network Configuration

### Primary Networks

#### 1. **traefik-public** (External)
- **ID**: `d5318383cdfe`
- **Type**: Bridge
- **Subnet**: 172.19.0.0/16
- **Gateway**: 172.19.0.1

**Connected Containers:**
```
traefik     -> 172.19.0.2/16  (Reverse Proxy)
lg-web      -> 172.19.0.4/16  (Nginx Web Server)
vtnp-web    -> 172.19.0.3/16  (Other project)
```

#### 2. **lamgame_lgnet** (Internal)
- **ID**: `1fe267ae34ed`
- **Type**: Bridge  
- **Subnet**: 172.23.0.0/16
- **Gateway**: 172.23.0.1

**Connected Containers:**
```
lg-mysql -> 172.23.0.2/16 (Database)
lg-php   -> 172.23.0.x/16 (PHP-FPM)
lg-redis -> 172.23.0.x/16 (Cache)
lg-mailpit -> 172.23.0.x/16 (Mail)
lg-vite  -> 172.23.0.x/16 (Dev Server)
```

---

## 🗂️ Service Details

### 🗄️ **lg-mysql** (Database)
```yaml
Container: lg-mysql
Image: mysql:8.0
Internal Port: 3306
External Port: 33069 (host)
Network: lamgame_lgnet
Volume: lamgame_lg_dbdata:/var/lib/mysql
```

**Configuration:**
- Database: `lamgame`
- User: `lg` / Password: `lg`
- Root Password: `root`
- Charset: utf8mb4_unicode_ci
- Timezone: Asia/Ho_Chi_Minh

**Connection:**
```bash
# From host
mysql -h 127.0.0.1 -P 33069 -u lg -p lamgame

# From containers
mysql -h lg-mysql -u lg -p lamgame
```

### 🐘 **lg-php** (PHP-FPM)
```yaml
Container: lg-php
Build: ../emsaigon/docker/php/Dockerfile
Internal Port: 9000
Network: lamgame_lgnet
Volume: .:/var/www/html (project root)
```

**Configuration:**
- Working Dir: `/var/www/html`
- PHP Config: `../emsaigon/docker/php/php.ini`
- OPCache: Validation enabled (dev mode)

### 🌐 **lg-web** (Nginx)
```yaml
Container: lg-web
Image: nginx:1.27-alpine
Internal Port: 80
Networks: lamgame_lgnet, traefik-public
Volume: .:/var/www/html, ./docker/nginx/lamgame.conf
```

**Traefik Labels:**
```yaml
traefik.enable: true
traefik.docker.network: traefik-public
# HTTP -> HTTPS Redirect
traefik.http.routers.lamgame-http.rule: Host(`lamgame.localhost`)
traefik.http.routers.lamgame-http.entrypoints: web
traefik.http.routers.lamgame-http.middlewares: lamgame-redirect-https
# HTTPS Router
traefik.http.routers.lamgame-https.rule: Host(`lamgame.localhost`)
traefik.http.routers.lamgame-https.entrypoints: websecure
traefik.http.routers.lamgame-https.tls: true
```

### 🔴 **lg-redis** (Cache/Sessions)
```yaml
Container: lg-redis
Image: redis:7-alpine
Internal Port: 6379
External Port: 63794 (host)
Network: lamgame_lgnet
```

**Connection:**
```bash
# From host
redis-cli -h 127.0.0.1 -p 63794

# From containers  
redis-cli -h lg-redis
```

### ⚡ **lg-vite** (Development Server)
```yaml
Container: lg-vite
Image: node:20-alpine
Internal Port: 5173
External Port: 5174 (host)
Network: lamgame_lgnet
Command: npm install && npm run dev
```

**Features:**
- Hot Module Replacement (HMR)
- File watching with polling
- Asset compilation
- Access: http://localhost:5174

### 📧 **lg-mailpit** (Mail Testing)
```yaml
Container: lg-mailpit
Image: axllent/mailpit:v1.18
Internal Ports: 8025 (UI), 1025 (SMTP)
External Port: 8028 (UI)
Network: lamgame_lgnet
```

**Access:**
- UI: http://localhost:8028
- SMTP: lg-mailpit:1025 (from containers)

---

## 🗃️ Volumes & Data

### Docker Volumes
```bash
# List lamgame volumes
docker volume ls | grep -E "(lamgame|lg_)"
```

| Volume Name | Purpose | Mount Point |
|-------------|---------|-------------|
| lamgame_lg_dbdata | MySQL data | /var/lib/mysql |

### Bind Mounts
```bash
# Project source code
./:/var/www/html:cached

# Configuration files
../emsaigon/docker/php/php.ini:/usr/local/etc/php/conf.d/custom.ini:ro
./docker/nginx/lamgame.conf:/etc/nginx/conf.d/default.conf:ro
```

---

## 🔧 Management Commands

### Start/Stop Services
```bash
# Start all services
cd /Users/Shared/jerry/ohha/lamgame.vn
docker-compose up -d

# Stop all services
docker-compose down

# Restart specific service
docker-compose restart lg-web

# View logs
docker-compose logs -f lg-web
```

### Health Checks
```bash
# Check container status
docker ps --filter "name=lg-"

# Check resource usage
docker stats lg-mysql lg-php lg-web lg-redis lg-vite lg-mailpit

# Check networks
docker network ls | grep -E "(lamgame|traefik)"
docker network inspect traefik-public
docker network inspect lamgame_lgnet
```

### Database Operations
```bash
# Backup database
docker exec lg-mysql mysqldump -u root -proot lamgame > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore database  
docker exec -i lg-mysql mysql -u root -proot lamgame < backup.sql

# Access MySQL CLI
docker exec -it lg-mysql mysql -u lg -p lamgame
```

---

## 🌐 Access URLs

### Production URLs
- **Website (HTTPS)**: https://lamgame.localhost
- **Website (HTTP)**: http://lamgame.localhost → redirects to HTTPS

### Development Tools
- **Traefik Dashboard**: http://traefik.local:8080
- **Mailpit Interface**: http://localhost:8028  
- **Vite Dev Server**: http://localhost:5174

### Direct Database Access
- **MySQL**: localhost:33069
- **Redis**: localhost:63794

---

## ⚙️ Environment Configuration

### Required /etc/hosts entries
```bash
127.0.0.1 lamgame.localhost
127.0.0.1 traefik.local
```

### SSL Certificates
```
../emsaigon/ssl/lamgame.localhost.pem      (Certificate)
../emsaigon/ssl/lamgame.localhost-key.pem  (Private Key)
```

### Project Structure
```
/Users/Shared/jerry/ohha/
├── emsaigon/                    # Shared infrastructure
│   ├── docker-compose.yml      # EMS services  
│   ├── docker-compose.traefik.yml  # Traefik proxy
│   ├── docker/php/             # Shared PHP build
│   ├── ssl/                    # SSL certificates
│   └── traefik/                # Traefik configuration
└── lamgame.vn/                 # Lamgame project
    ├── docker-compose.yml      # Lamgame services
    ├── docker/nginx/           # Nginx config
    └── .env                    # Environment variables
```

---

## 🔄 Troubleshooting

### Common Issues
```bash
# Container won't start
docker-compose logs [container_name]

# Port conflicts
docker ps --filter "publish=33069"  # Check what's using port

# Network issues
docker network prune              # Clean unused networks
docker network create traefik-public  # Recreate if missing

# Permission issues
docker exec -it lg-php ls -la /var/www/html
```

### Performance Tuning
```bash
# Monitor resources
docker stats --no-stream

# Check disk usage
docker system df

# Cleanup unused resources
docker system prune -f
```

---

## 📝 Notes

1. **Vite Container**: High CPU usage (295%) is normal during development
2. **SSL**: Managed by Traefik with automatic HTTPS redirect  
3. **Database**: Persistent data stored in Docker volume
4. **Hot Reload**: Vite provides real-time asset compilation
5. **Mail Testing**: All emails captured by Mailpit for development

---

**Deployment Date**: 23/09/2025  
**Last Updated**: 23/09/2025 15:36:27  
**Status**: ✅ Production Ready