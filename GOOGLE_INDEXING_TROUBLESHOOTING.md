# Google Indexing API Troubleshooting

## ❌ Vấn đề hiện tại
- Access token: ✅ OK
- Push URLs: ❌ Tất cả failed (0 success)
- Error message: Không có (bị catch và bỏ qua)

## 🔍 Checklist để kiểm tra

### 1. Google Cloud Console - API Settings
**Link:** https://console.cloud.google.com/apis/dashboard?project=lamgame-474413

☐ **Kiểm tra API đã enable:**
   - Vào "APIs & Services" → "Enabled APIs"
   - Tìm: "**Web Search Indexing API**" (KHÔNG phải "Indexing API")
   - Nếu không thấy → Click "Enable APIs and Services" → Tìm "Web Search Indexing API" → Enable

☐ **Kiểm tra Service Account có đúng quyền:**
   - Vào "IAM & Admin" → "Service Accounts"
   - Tìm: google-index@lamgame-474413.iam.gserviceaccount.com
   - Role phải là: "Editor" hoặc "Owner"

### 2. Google Search Console - Domain Verification
**Link:** https://search.google.com/search-console

☐ **Kiểm tra property đã verify:**
   - Property: https://lamgame.vn hoặc sc-domain:lamgame.vn
   - Status: Verified ✅

☐ **Kiểm tra service account đã được add:**
   - Settings → Users and permissions
   - Tìm: google-index@lamgame-474413.iam.gserviceaccount.com
   - Permission: Owner
   - Status: ✅ (màu xanh)

### 3. API Quota & Limits
**Link:** https://console.cloud.google.com/apis/api/indexing.googleapis.com/quotas?project=lamgame-474413

☐ **Kiểm tra quota:**
   - Requests per day: 200 (free tier)
   - Requests per 100 seconds: 600
   - Không bị exceed

### 4. Test lại với domain khác
Thử push 1 URL đơn giản để test:

```bash
# Test với homepage
docker exec lg-php php -r "
\$client = new Google_Client();
\$client->setAuthConfig('storage/app/google-service-account.json');
\$client->addScope('https://www.googleapis.com/auth/indexing');
\$httpClient = \$client->authorize();

\$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
\$content = json_encode([
    'url' => 'https://lamgame.vn/',
    'type' => 'URL_UPDATED'
]);

\$response = \$httpClient->post(\$endpoint, ['body' => \$content]);
echo \$response->getBody();
"
```

## 🐛 Common Issues

### Issue 1: "Permission denied"
**Nguyên nhân:** Service account chưa được add vào Search Console
**Giải pháp:** Add email `google-index@lamgame-474413.iam.gserviceaccount.com` với quyền Owner

### Issue 2: "API not enabled"
**Nguyên nhân:** Chưa enable Web Search Indexing API
**Giải pháp:** Enable tại https://console.cloud.google.com/apis/library/indexing.googleapis.com?project=lamgame-474413

### Issue 3: "Domain not verified"
**Nguyên nhân:** Domain chưa verify trong Search Console
**Giải pháp:** Verify domain tại https://search.google.com/search-console

### Issue 4: "Invalid URL"
**Nguyên nhân:** URL không thuộc property đã verify
**Giải pháp:** Đảm bảo URL format khớp với property (http vs https, www vs non-www)

## 📝 Next Steps

1. Check tất cả items trong checklist trên
2. Nếu vẫn lỗi, lấy error message chi tiết:
   ```bash
   # Sửa file command để log error
   nano app/Console/Commands/PushToGoogleIndex.php
   # Tìm dòng: $this->error('❌ ' . $url);
   # Thêm: $this->error('Error: ' . $e->getMessage());
   ```
3. Report lại error message cụ thể

## 🔗 Useful Links
- Google Indexing API Docs: https://developers.google.com/search/apis/indexing-api/v3/quickstart
- Search Console: https://search.google.com/search-console
- GCP Console: https://console.cloud.google.com/apis/dashboard?project=lamgame-474413
