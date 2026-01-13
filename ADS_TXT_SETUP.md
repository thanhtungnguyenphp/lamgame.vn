# ads.txt Setup and Troubleshooting

## Problem Identified

**Google AdSense Status:** "Trạng thái tệp ads.txt không tìm thấy"

**Root Cause:** File `ads.txt` không tồn tại trong thư mục `public/`

## Solution Applied

### 1. Created ads.txt file
**Location:** `/data/www/lamgame.vn/public/ads.txt`

**Content:**
```
google.com, pub-5812352607411986, DIRECT, f08c47fec0942fa0
```

### 2. Format Explanation
```
<DOMAIN>, <PUBLISHER_ID>, <RELATIONSHIP>, <TAG_ID>
```

- **DOMAIN:** `google.com` - Ad system domain
- **PUBLISHER_ID:** `pub-5812352607411986` - Your AdSense publisher ID
- **RELATIONSHIP:** `DIRECT` - Direct relationship (not through reseller)
- **TAG_ID:** `f08c47fec0942fa0` - Google's certification authority ID

## Verification Results

### ✅ File Accessibility
```bash
curl https://lamgame.vn/ads.txt
# Output: google.com, pub-5812352607411986, DIRECT, f08c47fec0942fa0
```

### ✅ HTTP Response
- **Status:** HTTP/2 200 OK
- **Content-Type:** text/plain; charset=utf-8
- **Content-Length:** 59 bytes
- **Last-Modified:** 2026-01-06 08:21:22 GMT

### ✅ Format Validation
- Domain: google.com ✅
- Publisher ID: pub-5812352607411986 ✅
- Relationship: DIRECT ✅
- TAG-ID: f08c47fec0942fa0 ✅

### ✅ User Agent Tests
Tested with:
- Normal browser ✅
- Googlebot ✅
- AdsBot-Google ✅

All returned correct content.

### ✅ robots.txt Check
- ads.txt is NOT blocked by robots.txt ✅
- No Disallow rule for ads.txt ✅

## Why It Works Now

1. **File exists** in correct location (`public/ads.txt`)
2. **Nginx serves it directly** as static file (no Laravel routing)
3. **Correct Content-Type** (`text/plain`) automatically set
4. **Proper format** follows IAB Tech Lab ads.txt spec
5. **Accessible to crawlers** (no robots.txt blocking)

## Google AdSense Timeline

1. **Now:** ads.txt file is live and accessible
2. **24-72 hours:** Google will crawl and verify ads.txt
3. **Status update:** AdSense dashboard will show "ads.txt found"
4. **After verification:** Can proceed with ad units

## Monitoring

### Check ads.txt status
```bash
# Check file
curl https://lamgame.vn/ads.txt

# Check headers
curl -I https://lamgame.vn/ads.txt

# Validate format
python3 /tmp/validate_ads_txt.py
```

### Google AdSense Dashboard
- Visit: https://www.google.com/adsense
- Navigate to: Sites → Your site → ads.txt status
- Expected: "ads.txt found" (after Google crawls)

## Important Notes

### Do NOT edit these fields:
- ✅ Keep `google.com` as domain
- ✅ Keep `pub-5812352607411986` as publisher ID
- ✅ Keep `DIRECT` as relationship
- ✅ Keep `f08c47fec0942fa0` as TAG-ID

### Adding more ad networks (if needed):
```bash
# Example: Add another ad network
echo "example.com, pub-123456789, RESELLER, abc123def456" >> public/ads.txt
```

### File must be:
- Plain text (UTF-8)
- Located at root: https://lamgame.vn/ads.txt
- Publicly accessible (HTTP 200)
- Content-Type: text/plain

## Troubleshooting

### If ads.txt still not found in AdSense:

1. **Wait 24-72 hours** for Google to re-crawl
2. **Verify URL** in browser: https://lamgame.vn/ads.txt
3. **Check Cloudflare cache:**
   - Purge cache in Cloudflare dashboard
4. **Request re-crawl** in Google Search Console

### Force Cloudflare to update:
```bash
# Purge ads.txt from Cloudflare cache
curl -X POST "https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/purge_cache" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  --data '{"files":["https://lamgame.vn/ads.txt"]}'
```

## ads.txt Specification

**Standard:** IAB Tech Lab ads.txt v1.0.2
**Documentation:** https://iabtechlab.com/ads-txt/

**Required fields:**
1. Domain of ad system
2. Publisher's account ID
3. Type of account/relationship (DIRECT or RESELLER)
4. TAG-ID (optional but recommended for Google)

## Testing Tools

### Online validators:
- https://adstxt.guru/
- https://www.adstxt-validator.com/

### Command line:
```bash
# Check file
curl https://lamgame.vn/ads.txt

# Check with Googlebot UA
curl -H "User-Agent: Googlebot" https://lamgame.vn/ads.txt

# Validate format
python3 /tmp/validate_ads_txt.py
```

## Backup

File location: `/data/www/lamgame.vn/public/ads.txt`

To restore:
```bash
echo "google.com, pub-5812352607411986, DIRECT, f08c47fec0942fa0" > public/ads.txt
```

## Related Files

- **Meta tag:** `resources/views/layouts/master.blade.php` (line 26)
- **robots.txt:** `public/robots.txt` (no blocking)
- **nginx config:** `docker/nginx/lamgame.conf` (serves static files)

## Status Summary

| Check | Status |
|-------|--------|
| File exists | ✅ Yes |
| Public URL accessible | ✅ Yes |
| HTTP 200 response | ✅ Yes |
| Content-Type correct | ✅ text/plain |
| Format valid | ✅ Yes |
| robots.txt allows | ✅ Yes |
| Googlebot can access | ✅ Yes |
| AdsBot can access | ✅ Yes |

**Overall:** ✅ ads.txt is correctly configured and accessible

**Next:** Wait for Google to crawl and verify (24-72 hours)
