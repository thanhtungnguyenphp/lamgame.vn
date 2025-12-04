# Hướng dẫn Deploy Nginx Config

## 1. Backup config hiện tại

```bash
sudo cp /etc/nginx/sites-available/lamgame.vn /etc/nginx/sites-available/lamgame.vn.backup
```

## 2. Cập nhật config

```bash
# Copy file mẫu
sudo cp docs/optimize-seo/nginx-production.conf /etc/nginx/sites-available/lamgame.vn

# Hoặc chỉ thêm redirect rules vào file hiện tại
sudo nano /etc/nginx/sites-available/lamgame.vn
```

Thêm vào trong block `server {}`, sau dòng `charset utf-8;`:

```nginx
# SEO: Redirect index.php URLs to clean URLs
if ($request_uri ~* "^/index\.php/(.*)$") {
    return 301 /$1;
}

# SEO: Redirect .html URLs to clean URLs
if ($request_uri ~* "^/(.*)\.html$") {
    return 301 /$1;
}
```

## 3. Test config

```bash
sudo nginx -t
```

Phải thấy:
```
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

## 4. Reload Nginx

```bash
sudo systemctl reload nginx
```

## 5. Test redirect

```bash
# Test 1: index.php redirect
curl -I https://lamgame.vn/index.php/blog

# Kết quả mong đợi:
# HTTP/1.1 301 Moved Permanently
# Location: https://lamgame.vn/blog

# Test 2: .html redirect
curl -I https://lamgame.vn/test.html

# Kết quả mong đợi:
# HTTP/1.1 301 Moved Permanently
# Location: https://lamgame.vn/test
```

## 6. Rollback (nếu cần)

```bash
sudo cp /etc/nginx/sites-available/lamgame.vn.backup /etc/nginx/sites-available/lamgame.vn
sudo systemctl reload nginx
```

## Lưu ý

- Redirect 301 là permanent, browser sẽ cache
- Clear browser cache khi test
- Monitor error logs: `sudo tail -f /var/log/nginx/error.log`
