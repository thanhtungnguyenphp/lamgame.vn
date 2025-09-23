# 🚀 Docker Quick Reference - Lamgame

## ⚡ Lệnh thường dùng

### Container Management
```bash
# Start all services
docker-compose up -d

# Stop all services  
docker-compose down

# Restart service
docker-compose restart lg-web

# View logs
docker-compose logs -f lg-php
docker logs --tail 50 lg-mysql
```

### Status Check
```bash
# Container status
docker ps --filter "name=lg-"

# Resource usage
docker stats --no-stream lg-mysql lg-php lg-web lg-redis lg-vite lg-mailpit

# Network info
docker network inspect traefik-public
docker network inspect lamgame_lgnet
```

### Database
```bash
# MySQL CLI
docker exec -it lg-mysql mysql -u lg -p lamgame

# Backup
docker exec lg-mysql mysqldump -u root -proot lamgame > backup_$(date +%Y%m%d).sql

# Restore
docker exec -i lg-mysql mysql -u root -proot lamgame < backup.sql
```

### Redis
```bash
# Redis CLI
docker exec -it lg-redis redis-cli

# From host
redis-cli -h 127.0.0.1 -p 63794
```

---

## 🌐 Access Points

| Service | URL | Port |
|---------|-----|------|
| **Website** | https://lamgame.localhost | 443 |
| **Traefik** | http://traefik.local:8080 | 8080 |
| **Mailpit** | http://localhost:8028 | 8028 |
| **Vite** | http://localhost:5174 | 5174 |
| **MySQL** | localhost:33069 | 33069 |
| **Redis** | localhost:63794 | 63794 |

---

## 🔧 Quick Fixes

### Container Issues
```bash
# Rebuild container
docker-compose build lg-php --no-cache
docker-compose up -d lg-php

# Container logs
docker logs lg-web --tail 20 -f

# Execute command in container
docker exec -it lg-php bash
```

### Network Issues
```bash
# Check what's using port
lsof -i :33069

# Recreate network
docker network rm lamgame_lgnet
docker-compose up -d
```

### Permission Issues
```bash
# Fix Laravel permissions
docker exec lg-php chown -R www-data:www-data storage bootstrap/cache
docker exec lg-php chmod -R 775 storage bootstrap/cache
```

---

## 📊 Container Info

| Container | Image | Purpose | 
|-----------|-------|---------|
| lg-mysql | mysql:8.0 | Database |
| lg-php | Custom | PHP-FPM |
| lg-web | nginx:1.27-alpine | Web Server |
| lg-redis | redis:7-alpine | Cache |
| lg-vite | node:20-alpine | Dev Server |
| lg-mailpit | axllent/mailpit | Mail Testing |

**Total RAM**: ~720MB  
**Status**: ✅ All Running