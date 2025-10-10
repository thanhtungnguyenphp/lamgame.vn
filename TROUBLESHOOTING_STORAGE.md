# 🔧 Laravel Storage Permissions Troubleshooting Guide

## Common Storage Permission Errors

### 1. **View Compilation Error**
```
file_put_contents(/path/to/storage/framework/views/xxx.php): Failed to open stream: No such file or directory
```

**Causes:**
- Missing storage directories
- Incorrect file permissions  
- Web server running as different user than file owner
- SELinux restrictions (on some Linux systems)

**Solutions:**
```bash
# Quick fix - run our permission script
./fix-permissions.sh

# Manual fix
mkdir -p storage/framework/{cache,views,sessions}
mkdir -p storage/{logs,app/public}
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# For production (more secure)
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux
chown -R _www:_www storage bootstrap/cache          # macOS
```

### 2. **Log File Permission Error**
```
Unable to create configured logger. Using emergency logger. The stream or file "storage/logs/laravel.log" could not be opened
```

**Solution:**
```bash
mkdir -p storage/logs
chmod 777 storage/logs
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
```

### 3. **Session Storage Error**
```
Session store not set on request
```

**Solution:**
```bash
mkdir -p storage/framework/sessions
chmod 777 storage/framework/sessions
php artisan config:cache
```

### 4. **File Upload Error**
```
The file "xxx" was not uploaded due to an unknown error
```

**Solution:**
```bash
mkdir -p storage/app/public
chmod 777 storage/app/public
php artisan storage:link
```

---

## Platform-Specific Solutions

### 🍎 macOS Development

```bash
# Web server usually runs as _www user
sudo chown -R _www:_www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# For development, allow both users to write
chmod -R 777 storage bootstrap/cache
```

### 🐧 Linux Production

```bash
# Web server usually runs as www-data user
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# For uploads and logs
sudo chmod -R 775 storage/app storage/logs
```

### 🐳 Docker Environment

```bash
# In Dockerfile
RUN chmod -R 777 storage bootstrap/cache

# Or in docker-compose.yml
volumes:
  - ./storage:/var/www/html/storage:rw
  - ./bootstrap/cache:/var/www/html/bootstrap/cache:rw
```

---

## Advanced Troubleshooting

### Check Current Permissions
```bash
# Check directory permissions
ls -la storage/framework/
ls -la bootstrap/

# Check web server user
ps aux | grep -E "(apache|nginx|php-fpm)"

# Test write permissions
echo "test" > storage/framework/views/test.txt && rm storage/framework/views/test.txt
```

### SELinux Issues (CentOS/RHEL)
```bash
# Check SELinux status
sestatus

# Allow httpd to write to storage
setsebool -P httpd_unified 1
chcon -R -t httpd_exec_t storage/
chcon -R -t httpd_exec_t bootstrap/cache/
```

### Docker Permission Issues
```bash
# Run container with correct user ID
docker run -u $(id -u):$(id -g) your-image

# Or set permissions in entrypoint
#!/bin/sh
chown -R www-data:www-data storage bootstrap/cache
chmod -R 777 storage bootstrap/cache
exec "$@"
```

---

## Prevention Best Practices

### 1. **Development Environment**
```bash
# Add to your .bashrc or .zshrc
alias fix-laravel-permissions='chmod -R 777 storage bootstrap/cache && php artisan view:clear'

# Add to git hooks (pre-commit)
#!/bin/sh
if [ -d "storage" ]; then
    chmod -R 777 storage bootstrap/cache
fi
```

### 2. **Deployment Script**
```bash
#!/bin/bash
# deploy.sh
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci --only=production && npm run build

# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Clear caches
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Restart services
sudo systemctl restart php8.2-fpm nginx
```

### 3. **Monitoring**
```bash
# Add to crontab for automatic fixing
0 */6 * * * /path/to/your/project/fix-permissions.sh >/dev/null 2>&1

# Health check script
#!/bin/bash
if [ ! -w "storage/framework/views" ]; then
    echo "Storage not writable!" | mail -s "Laravel Storage Alert" admin@yoursite.com
    /path/to/fix-permissions.sh
fi
```

---

## Quick Reference Commands

```bash
# Emergency fix (run in project root)
mkdir -p storage/framework/{cache,views,sessions} storage/{logs,app/public} bootstrap/cache
chmod -R 777 storage bootstrap/cache
php artisan view:clear

# Production fix
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Test permissions
touch storage/framework/views/test && rm storage/framework/views/test
```

---

## When to Run Permission Fixes

- ✅ After git pull/clone
- ✅ After composer install
- ✅ After server migration
- ✅ When switching between development and production
- ✅ After changing web server configuration
- ✅ When getting permission errors in logs

Remember: **777 permissions are for development only!** Use more restrictive permissions in production for security.