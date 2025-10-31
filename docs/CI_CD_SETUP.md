# CI/CD Setup Guide

## Overview
Dự án sử dụng GitHub Actions để tự động hóa quy trình CI/CD với các workflows sau:

### 1. **CI - Test & Build** (`.github/workflows/ci.yml`)
- Chạy tự động khi push hoặc tạo PR vào branch `main` hoặc `develop`
- **Jobs:**
  - **Test:** Chạy PHPUnit tests, kiểm tra code style với Pint
  - **Build:** Build Docker image và push lên Docker Hub (chỉ khi push vào main)

### 2. **CD - Deploy to Production** (`.github/workflows/deploy.yml`)
- Chạy tự động sau khi CI workflow hoàn thành thành công
- Deploy code lên production server qua SSH
- Chạy migrations, clear cache, optimize, restart services

### 3. **CD - Docker Deploy** (`.github/workflows/docker-deploy.yml`)
- Chạy thủ công (workflow_dispatch)
- Deploy bằng Docker Compose
- Cho phép chọn environment (production/staging)

### 4. **Code Quality** (`.github/workflows/code-quality.yml`)
- Chạy Pint (code style), PHPStan (static analysis)
- Upload test coverage lên Codecov
- Security scan với security-checker

### 5. **Backup Database** (`.github/workflows/backup.yml`)
- Chạy tự động hàng ngày lúc 2 AM UTC
- Backup database và storage/uploads
- Giữ lại backup trong 7 ngày

---

## Setup Instructions

### 1. Cấu hình GitHub Secrets

Vào **Settings > Secrets and variables > Actions** trong GitHub repository và thêm các secrets sau:

#### Required Secrets:
```
SERVER_HOST         # IP hoặc hostname của server (VD: 123.45.67.89)
SERVER_USERNAME     # Username SSH (VD: root)
SERVER_SSH_KEY      # Private SSH key để kết nối server
SERVER_PORT         # SSH port (mặc định: 22)
```

#### Optional Secrets (nếu dùng Docker Hub):
```
DOCKER_USERNAME     # Docker Hub username
DOCKER_PASSWORD     # Docker Hub password hoặc access token
```

#### Optional Secrets (nếu dùng Slack notifications):
```
SLACK_WEBHOOK_URL   # Slack webhook URL để nhận thông báo
```

### 2. Tạo SSH Key cho Deployment

```bash
# Trên máy local, tạo SSH key pair
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy

# Copy public key lên server
ssh-copy-id -i ~/.ssh/github_deploy.pub root@your-server-ip

# Copy private key và paste vào GitHub Secret SERVER_SSH_KEY
cat ~/.ssh/github_deploy
```

### 3. Cấu hình Server

#### 3.1. Cho phép user chạy systemctl không cần password (nếu cần):
```bash
# Trên server, edit sudoers
sudo visudo

# Thêm dòng sau (thay 'root' bằng username của bạn):
root ALL=(ALL) NOPASSWD: /bin/systemctl restart php8.2-fpm
root ALL=(ALL) NOPASSWD: /bin/systemctl reload nginx
```

#### 3.2. Đảm bảo Git được cấu hình:
```bash
cd /data/www/lamgame.vn
git config --global user.name "Deploy Bot"
git config --global user.email "deploy@lamgame.vn"
```

### 4. Test Workflows

#### Test CI workflow:
```bash
git add .
git commit -m "test: CI/CD setup"
git push origin main
```

#### Test manual Docker deploy:
- Vào GitHub > Actions > "CD - Docker Deploy"
- Click "Run workflow"
- Chọn environment và chạy

#### Test backup:
- Vào GitHub > Actions > "Backup Database"
- Click "Run workflow"

---

## Workflow Details

### CI Workflow Flow:
```
Push/PR to main/develop
  ↓
Run Tests (PHPUnit, Pint)
  ↓
Build Docker Image (if main branch)
  ↓
Push to Docker Hub
```

### CD Workflow Flow:
```
CI Success
  ↓
Connect to server via SSH
  ↓
Pull latest code
  ↓
Install dependencies
  ↓
Build assets
  ↓
Run migrations
  ↓
Clear & optimize caches
  ↓
Restart services
  ↓
Send Slack notification
```

---

## Customization

### 1. Thay đổi Branch cho Deployment
Edit `.github/workflows/deploy.yml`:
```yaml
branches:
  - main          # Thay đổi thành branch khác nếu cần
```

### 2. Thêm Environment Variables
Edit deploy script trong `.github/workflows/deploy.yml`:
```yaml
script: |
  cd /data/www/lamgame.vn
  export APP_ENV=production
  export APP_DEBUG=false
  # ... rest of script
```

### 3. Deploy to Multiple Servers
Tạo thêm secrets và jobs cho từng server:
```yaml
deploy-staging:
  # ... 
  with:
    host: ${{ secrets.STAGING_SERVER_HOST }}
```

### 4. Add Rollback Workflow
Tạo `.github/workflows/rollback.yml`:
```yaml
name: Rollback
on:
  workflow_dispatch:
    inputs:
      commit_sha:
        description: 'Commit SHA to rollback to'
        required: true

jobs:
  rollback:
    runs-on: ubuntu-latest
    steps:
      - uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USERNAME }}
          key: ${{ secrets.SERVER_SSH_KEY }}
          script: |
            cd /data/www/lamgame.vn
            git reset --hard ${{ github.event.inputs.commit_sha }}
            composer install --no-dev --optimize-autoloader
            npm ci --only=production
            npm run build
            php artisan migrate --force
            php artisan cache:clear
            sudo systemctl restart php8.2-fpm
```

---

## Monitoring & Troubleshooting

### Check Workflow Status:
- GitHub > Actions tab
- Xem logs chi tiết của từng step

### Common Issues:

#### 1. SSH Connection Failed
```
Error: dial tcp: lookup your-server: no such host
```
**Fix:** Kiểm tra `SERVER_HOST` và `SERVER_SSH_KEY` trong GitHub Secrets

#### 2. Permission Denied
```
Error: Permission denied (publickey)
```
**Fix:** Đảm bảo public key đã được thêm vào `~/.ssh/authorized_keys` trên server

#### 3. Build Failed
```
Error: npm run build failed
```
**Fix:** Kiểm tra `package.json` và dependencies

#### 4. Migration Failed
```
Error: php artisan migrate failed
```
**Fix:** Kiểm tra database connection trong `.env` trên server

### View Logs:
```bash
# Server logs
tail -f /data/www/lamgame.vn/storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

---

## Best Practices

1. **Always test on develop branch first** trước khi merge vào main
2. **Use feature branches** cho development: `feature/new-feature`
3. **Tag releases** để dễ rollback: `git tag v1.0.0`
4. **Monitor deployments** qua Slack hoặc email notifications
5. **Regular backups** - workflow backup chạy tự động hàng ngày
6. **Keep secrets secure** - Không commit secrets vào code

---

## Next Steps

1. ✅ Setup GitHub Secrets
2. ✅ Test SSH connection
3. ✅ Run first deployment
4. 🔄 Setup Slack notifications (optional)
5. 🔄 Configure staging environment (optional)
6. 🔄 Setup monitoring tools (optional)

---

## Support

Nếu gặp vấn đề, kiểm tra:
- GitHub Actions logs
- Server logs (`/data/www/lamgame.vn/storage/logs`)
- Database connection
- File permissions (`storage/`, `bootstrap/cache/`)
